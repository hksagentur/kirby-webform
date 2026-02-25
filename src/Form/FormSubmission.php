<?php

namespace Webform\Form;

use Kirby\Filesystem\File;
use Webform\Http\UploadedFile;
use Webform\Toolkit\A;
use Webform\Toolkit\Payload;

readonly class FormSubmission extends Payload
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $data = [],
        /** @var array<string, UploadedFile[]> */
        protected array $files = [],
    ) {
    }

    public function all(?array $keys = null): array
    {
        $data = array_replace_recursive($this->data(), $this->files());

        if (is_null($keys)) {
            return $data;
        }

        $values = [];

        foreach ($keys as $key) {
            A::set($values, $key, A::get($data, $key));
        }

        return $values;
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->data, $key, $default);
    }

    /** @return ($key is null ? array<string, UploadedFile[]> : UploadedFile[]) */
    public function files(?string $key = null): array
    {
        return A::get($this->files, $key, []);
    }

    /** @return array<string, File[]> */
    public function storeFilesIn(string $directory): array
    {
        $files = [];

        foreach ($this->files as $field => $temporaryFiles) {
            foreach ($temporaryFiles as $temporaryFile) {
                if ($file = $temporaryFile->storeIn($directory)) {
                    $files[$field][] = $file;
                }
            }
        }

        return $files;
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default);
    }
}
