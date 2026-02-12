<?php

namespace Webform\Form;

use JsonSerializable;
use Kirby\Cms\App;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Filesystem\File;
use Kirby\Toolkit\Str;
use Stringable;
use Webform\Exception\FileUploadException;

class UploadedFile implements JsonSerializable, Stringable
{
    public function __construct(
        protected ?string $path,
        protected ?string $name,
        protected int $error = UPLOAD_ERR_OK,
    ) {}

    public static function from(array $file): static
    {
        return new static(
            path: $file['tmp_name'] ?? NULL,
            name: $file['name'] ?? NULL,
            error: $file['error'] ?? UPLOAD_ERR_OK,
        );
    }

    public static function tryFrom(array $file): ?static
    {
        if (isset($file['tmp_name'], $file['name'])) {
            return static::from($file);
        }

        return null;
    }

    public static function getMaxFileSize(): int|float
    {
        $postMaxSize = Str::toBytes(\ini_get('post_max_size')) ?: \PHP_INT_MAX;
        $uploadMaxFileSize = Str::toBytes(\ini_get('upload_max_filesize')) ?: \PHP_INT_MAX;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getName(): ?string
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

    public function move(string $directory, ?string $name = null): File
    {
        $name ??= $this->getName();

        if (! $this->isValid()) {
            throw new FileUploadException([
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
        return $this->getPath() ?? '';
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
