<?php

namespace Webform\Form;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Kirby\Filesystem\File;
use Kirby\Toolkit\Str;
use Webform\Support\A;
use Webform\Support\Concerns\InteractsWithData;

readonly class FormSubmission implements IteratorAggregate, JsonSerializable
{
    use InteractsWithData;

    public function __construct(
        /** @var array<string, mixed> */
        protected array $data = [],
        /** @var array<string, File[]> */
        protected array $files = [],
    ) {
    }

    public static function fromInput(array $data, array $files = []): static
    {
        $data = A::filter($data, static function ($value, $key) {
            return ! Str::startsWith($key, '_');
        });

        return new static($data, $files);
    }

    public function all(?array $keys = null): array
    {
        $data = array_replace_recursive($this->data(), $this->fileNames());

        if (is_null($keys)) {
            return $data;
        }

        $values = [];

        foreach ($keys as $key) {
            A::set($values, $key, A::get($data, $key));
        }

        return $values;
    }

    public function file(?string $key = null): ?File
    {
        $files = $this->files($key);

        if (is_null($key)) {
            return A::first(A::first($files));
        }

        return A::first($files);
    }

    /**
     * @return ($key is null ? array<array-key, File[]> : array<array-key, File>)
     */
    public function files(?string $key = null): array
    {
        if (is_null($key)) {
            return $this->files;
        }

        return A::get($this->files, $key);
    }

    /**
     * @return ($key is null ? array<array-key, string[]> : array<array-key, string>)
     */
    public function fileNames(?string $key = null): array
    {
        $files = $this->files($key);

        if (is_null($key)) {
            return A::map($files, fn (array $files) => A::map($files, $this->getFileName(...)));
        }

        return A::map($files, $this->getFileName(...));
    }

    /**
     * @return ($key is null ? array<array-key, string[]> : array<array-key, string>)
     */
    public function filePaths(?string $key = null): array
    {
        $files = $this->files($key);

        if (is_null($key)) {
            return A::map($files, fn (array $files) => A::map($files, $this->getFilePath(...)));
        }

        return A::map($files, $this->getFilePath(...));
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    public function jsonSerialize(): array
    {
        return $this->all();
    }

    public function __isset(string $name): bool
    {
        return $this->exists($name);
    }

    public function __get(string $name): mixed
    {
        return $this->data($name);
    }

    public function __call(string $name, array $arguments = []): mixed
    {
        return $this->data($name, ...$arguments);
    }

    public function __debugInfo(): array
    {
        return $this->all();
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->data, $key, $default);
    }

    protected function getFilePath(File $file): string
    {
        return $file->root();
    }

    protected function getFileName(File $file): string
    {
        return $file->filename();
    }
}
