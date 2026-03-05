<?php

namespace Webform\Template;

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Webform\Form\FormSubmission;
use Webform\Http\UploadedFile;

readonly class SubmissionEmail extends ViewModel
{
    public function __construct(
        protected FormSubmission $submission,
    ) {
    }

    public static function create(FormSubmission $submission): static
    {
        return new static($submission);
    }

    public function rows(): array
    {
        $rows = [];

        foreach ($this->submission->all() as $key => $value) {
            $rows[] = [
                'label' => $this->formatLabel($key),
                'value' => $this->formatValue($value),
            ];
        }

        return $rows;
    }

    public function toArray(): array
    {
        return [
            'rows' => $this->rows(),
        ];
    }

    protected function formatLabel(string $key): string
    {
        $field = $this->submission->getForm()?->getFields()->find($key) ?? null;

        if ($field !== null) {
            return $field->getLabel() ?? Str::studly($key);
        }

        return Str::studly($key);
    }

    protected function formatValue(mixed $value): string
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
}
