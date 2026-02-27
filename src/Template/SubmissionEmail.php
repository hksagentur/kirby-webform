<?php

namespace Webform\Template;

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Webform\Form\Form;
use Webform\Form\FormSubmission;
use Webform\Http\UploadedFile;

readonly class SubmissionEmail extends ViewModel
{
    public function __construct(
        protected Form $form,
        protected FormSubmission $submission,
    ) {
    }

    public static function create(Form $form, FormSubmission $submission): static
    {
        return new static($form, $submission);
    }

    public function getDefaultLabel(string $key): string
    {
        return Str::ucfirst($key);
    }

    public function getLabel(string $key): string
    {
        $field = $this->form->getFields()->find($key);

        if (! $field) {
            return $this->getDefaultLabel($key);
        }

        return $field->getLabel() ?? $this->getDefaultLabel($key);
    }

    public function formatValue(mixed $value): string
    {
        return match (true) {
            in_array($value, ['', [], null], true) => '–',
            in_array($value, [true, 'yes', 'true', 'on'], true) => '✓',
            in_array($value, [false, 'no', 'false', 'off'], true) => '✗',
            is_array($value) => A::join(A::map($value, $this->formatValue(...))),
            is_a($value, File::class) => $value->url(),
            is_a($value, Page::class) => $value->url(),
            is_a($value, UploadedFile::class) => $value->getName(),
            default => (string) $value,
        };
    }

    public function rows(): array
    {
        $rows = [];

        foreach ($this->submission->all() as $key => $value) {
            if (! Str::startsWith($key, '_')) {
                $rows[] = [
                    'label' => $this->getLabel($key),
                    'value' => $this->formatValue($value),
                ];
            }
        }

        return $rows;
    }

    public function toArray(): array
    {
        return [
            'rows' => $this->rows(),
        ];
    }
}
