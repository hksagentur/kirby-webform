<?php

namespace Webform\Form;

use Kirby\Cms\Url;
use Kirby\Toolkit\Str;
use UnexpectedValueException;
use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\FileUpload;
use Webform\Template\ViewComponent;
use Webform\Validation\ValidatedInput;

class Form extends ViewComponent
{
    use Concerns\CanBeValidated;
    use Concerns\CanDispatchEvent;
    use Concerns\CanGenerateCsrfTokens;
    use Concerns\EvaluatesClosures;
    use Concerns\HasChildren;
    use Concerns\HasConfig;
    use Concerns\HasContext;
    use Concerns\HasErrors;
    use Concerns\HasStatus;

    protected string $snippet = 'webform/form';

    public static function create(): static
    {
        return new static();
    }

    public static function loadFromConfig(string $path): ?static
    {
        return FormRepository::instance()->getByPath($path);
    }

    public function getKey(): string
    {
        return Str::slug($this->config->getName(), '_');
    }

    public function getId(): string
    {
        return $this->config->getPath();
    }

    public function getName(): string
    {
        return $this->config->getName();
    }

    public function getActionUrl(): string
    {
        return Url::to("webforms/{$this->getId()}");
    }

    public function getEvaluationContext(): array
    {
        return array_merge($this->getContext()->all(), [
            'form' => $this,
        ]);
    }

    public function getSnippetContext(): array
    {
        return array_merge($this->getContext()->all(), [
            'form' => $this,
            'children' => $this->getChildren()->visible(),
        ]);
    }

    public function submit(ValidatedInput $validated, ?string $operation = null): void
    {
        $action = $operation
            ? $this->getActions()->find($operation)
            : $this->getActions()->first();

        if (! $action) {
            throw new UnexpectedValueException(sprintf(
                'Invalid or missing form operation: %s',
                is_string($operation) ? $operation : get_debug_type($operation),
            ));
        }

        /** @var FormSubmission $submission */
        $submission = $this->apply('submit:before', [
            'form' => $this,
            'submission' => $this->createFormSubmission($validated),
        ], 'submission');

        if ($action->trigger(['submission' => $submission]) === false) {
            return;
        }

        $this->dispatch('submit:after', [
            'form' => $this,
            'submission' => $submission,
        ]);
    }

    protected function createFormSubmission(ValidatedInput $validated): FormSubmission
    {
        $fields = $this->getFields();

        $challenges = $fields->whereInstanceOf(ProvidesChallenge::class);
        $uploads = $fields->whereInstanceOf(FileUpload::class);

        $files = $validated->only($uploads->fieldNames());

        $data = $validated->except([
            ...$challenges->fieldNames(),
            ...$uploads->fieldNames(),
        ]);

        return new FormSubmission($data, $files, $this);
    }
}
