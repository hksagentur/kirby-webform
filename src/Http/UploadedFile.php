<?php

namespace Webform\Http;

use InvalidArgumentException;
use JsonSerializable;
use Kirby\Cms\App;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Filesystem\File;
use Kirby\Http\Request;
use Kirby\Http\Request\Files;
use Kirby\Toolkit\Str;
use Stringable;
use Throwable;
use Webform\Http\Exception\FileUploadException;

class UploadedFile implements JsonSerializable, Stringable
{
    public function __construct(
        protected string $path,
        protected string $name,
        protected int $error = UPLOAD_ERR_OK,
    ) {
    }

    public static function from(string|array|self $file): static
    {
        return match (true) {
            $file instanceof static => $file,
            is_string($file) => new static(
                path: $file,
                name: basename($file),
            ),
            is_array($file) && isset($file['tmp_name'], $file['name']) => new static(
                path: $file['tmp_name'],
                name: $file['name'],
                error: $file['error'] ?? UPLOAD_ERR_OK,
            ),
            default => throw new InvalidArgumentException(
                'Invalid file input'
            ),
        };
    }

    public static function tryFrom(string|array|self $file): ?static
    {
        try {
            return static::from($file);
        } catch (Throwable) {
            return null;
        }
    }

    /** @return array<string, static[]> */
    public static function fromRequest(Request $files): array
    {
        return static::fromFiles($files->files());
    }

    /** @return array<string, static[]> */
    public static function fromFiles(Files $files): array
    {
        $uploadedFiles = [];

        foreach ($files->toArray() as $field => $files) {
            if (is_array($files) && array_is_list($files)) {
                $uploadedFiles[$field] = array_map(static::from(...), $files);
            } else {
                $uploadedFiles[$field][] = static::from($files);
            }
        }

        return $uploadedFiles;
    }

    public static function getMaxFileSize(): int|float
    {
        $postMaxSize = Str::toBytes(ini_get('post_max_size')) ?: PHP_INT_MAX;
        $uploadMaxFileSize = Str::toBytes(ini_get('upload_max_filesize')) ?: PHP_INT_MAX;

        return min($postMaxSize, $uploadMaxFileSize);
    }

    public function isSuccessful(): bool
    {
        return $this->error === UPLOAD_ERR_OK;
    }

    public function isFile(): bool
    {
        return $this->error !== UPLOAD_ERR_NO_FILE;
    }

    public function isValid(): bool
    {
        return $this->isSuccessful() && is_uploaded_file($this->path);
    }

    public function isReadable(): bool
    {
        return $this->isSuccessful() && is_readable($this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getSize(): int
    {
        return F::size($this->path);
    }

    public function getMimeType(): ?string
    {
        return F::mime($this->path);
    }

    public function read(): string
    {
        return F::read($this->path);
    }

    public function delete(): bool
    {
        return F::remove($this->path);
    }

    public function storeIn(string $directory): File
    {
        return $this->storeAs($directory);
    }

    public function storeAs(string $directory, ?string $name = null): File
    {
        $name ??= $this->getName();

        if (! $this->isValid()) {
            throw new FileUploadException([
                'key' => 'hksagentur.webform.upload.default',
                'file' => $this,
            ]);
        }

        $extension = F::extension($name);

        if (empty($extension) || in_array($extension, ['tmp', 'temp'])) {
            $extension = F::mimeToExtension($this->getMimeType());
            $name = F::safeName($name).'.'.$extension;
        } else {
            $name = F::safeName($name);
        }

        $directory = App::instance()->root('base').'/'.$directory;
        $target = $directory.'/'.uniqid().'_'.$name;

        Dir::make($directory, recursive: true);

        $status = @move_uploaded_file($this->getPath(), $target);

        if (! $status) {
            throw new FileUploadException([
                'key' => 'hksagentur.webform.upload.cantMove',
                'file' => $this,
            ]);
        }

        @chmod($target, 0666 & ~umask());

        return new File(['root' => $target]);
    }

    public function toString(): string
    {
        return $this->getPath();
    }

    public function toFile(): File
    {
        return new File(['root' => $this->path]);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getMimeType(),
            'tmp_name' => $this->getPath(),
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'error' => $this->getError(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
