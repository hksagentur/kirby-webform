<?php

namespace Webform\Form\Components;

use Closure;
use Kirby\Cms\R;
use Kirby\Filesystem\File;
use Webform\Form\UploadedFile;

class FileUpload extends Field
{
    protected string $snippet = 'webform/file';

    protected bool|Closure $isMultiple = false;

    protected string|Closure $directory = 'storage/uploads';

    protected int|Closure|null $minSize = null;
    protected int|Closure|null $maxSize = null;

    protected int|Closure|null $minFiles = null;
    protected int|Closure|null $maxFiles = null;

    protected array|Closure|null $acceptedFileTypes = null;

    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->isMultiple);
    }

    public function getDirectory(): ?string
    {
        return $this->evaluate($this->directory);
    }

    public function getMinSize(): ?int
    {
        return $this->evaluate($this->minSize);
    }

    public function getMaxSize(): ?int
    {
        return $this->evaluate($this->maxSize);
    }

    public function getMinFiles(): ?int
    {
        return $this->evaluate($this->minFiles);
    }

    public function getMaxFiles(): ?int
    {
        return $this->evaluate($this->maxFiles);
    }

    public function getAcceptedFileTypes(): ?array
    {
        return $this->evaluate($this->acceptedFileTypes);
    }

    public function multiple(bool|Closure $condition = true): static
    {
        $this->isMultiple = $condition;

        return $this;
    }

    public function directory(string|Closure $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function minSize(int|Closure|null $size): static
    {
        $this->minSize = $size;

        return $this;
    }

    public function maxSize(int|Closure|null $size): static
    {
        $this->maxSize = $size;

        return $this;
    }

    public function minFiles(int|Closure|null $count): static
    {
        $this->minFiles = $count;

        return $this;
    }

    public function maxFiles(int|Closure|null $count): static
    {
        $this->maxFiles = $count;

        return $this;
    }

    public function acceptedFileTypes(array|Closure $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    /** @return UploadedFile[] */
    public function getDefaultValue(): array
    {
        return $this->evaluate($this->defaultValue) ?? [];
    }

    /** @return UploadedFile[] */
    public function getOldValue(): array
    {
        return [];
    }

    /** @return UploadedFile[] */
    public function getValue(): array
    {
        $files = R::file($this->getName()) ?? [];

        if (! array_is_list($files)) {
            $files = [$files];
        }

        $files = array_map(UploadedFile::tryFrom(...), $files);
        $files = array_filter($files);

        return $files;
    }

    public function getValidationRules(): array
    {
        $rules = parent::getValidationRules();

        $rules['list'] = [];

        if ($minFiles = $this->getMaxFiles()) {
            $rules['min'] = [$minFiles];
        }

        if ($maxFiles = $this->getMaxFiles()) {
            $rules['max'] = [$maxFiles];
        }

        $rules['file'] = [$this->isRequired()];

        if ($acceptedFileTypes = $this->getAcceptedFileTypes()) {
            $rules['mimeType'] = [$acceptedFileTypes];
        }

        if ($minSize = $this->getMinSize()) {
            $rules['minFileSize'] = [$minSize];
        }

        if ($maxSize = $this->getMaxSize()) {
            $rules['maxFileSize'] = [$maxSize];
        }

        return $rules;
    }

    /** @return File[] */
    public function saveUploadedFiles(): array
    {
        $files = [];

        foreach ($this->getValue() as $file) {
            if ($file->isFile()) {
                $files[] = $file->move($this->getDirectory());
            }
        }

        return $files;
    }
}
