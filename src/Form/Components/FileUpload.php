<?php

namespace Webform\Form\Components;

use Closure;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;
use Webform\Http\UploadedFile;

class FileUpload extends Field
{
    protected string $snippet = 'webform/file';

    protected bool|Closure $isMultiple = false;

    protected int|Closure|null $minSize = null;
    protected int|Closure|null $maxSize = null;

    protected int|Closure|null $minFiles = null;
    protected int|Closure|null $maxFiles = null;

    protected array|Closure|null $acceptedFileTypes = null;
    protected array|Closure|null $acceptedFileExtensions = null;

    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->isMultiple);
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
        $mimeTypes = $this->evaluate($this->acceptedFileTypes);

        if ($mimeTypes !== null) {
            return $mimeTypes;
        }

        return array_map(
            F::extensionToMime(...),
            $this->evaluate($this->acceptedFileExtensions) ?? [],
        );
    }

    public function getAcceptedFileExtensions(): ?array
    {
        $fileExtensions = $this->evaluate($this->acceptedFileExtensions);

        if ($fileExtensions !== null) {
            return $fileExtensions;
        }

        return array_map(
            F::mimeToExtension(...),
            $this->evaluate($this->acceptedFileTypes) ?? []
        );
    }

    public function multiple(bool|Closure $condition = true): static
    {
        $this->isMultiple = $condition;

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

    public function acceptedFileTypes(array|Closure|null $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    public function acceptedFileExtensions(array|Closure|null $extendsions): static
    {
        $this->acceptedFileExtensions = $extendsions;

        return $this;
    }

    public function getValidationRules(): array
    {
        $rules = parent::getValidationRules();

        $rules['list'] = [];

        if ($minFiles = $this->getMinFiles()) {
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

    public function getMessageContext(): array
    {
        return parent::getMessageContext() + [
            'minFiles' => $this->getMinFiles() ?? 0,
            'maxFiles' => $this->getMaxFiles() ?? 0,
            'minSize' => F::niceSize($this->getMinSize() ?? 0),
            'maxSize' => F::niceSize($this->getMaxSize() ?? UploadedFile::getMaxFileSize()),
            'fileTypes' => A::join($this->getAcceptedFileTypes()),
            'fileExtensions' => A::join($this->getAcceptedFileExtensions()),
        ];
    }
}
