<?php

namespace Webform\Form;

use Kirby\Cms\Url;
use Kirby\Toolkit\Str;
use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\FileUpload;
use Webform\Template\ViewComponent;
use Webform\Validation\ValidatedInput;

class Form extends ViewComponent
{
    use Concerns\BelongsToBlock;
    use Concerns\BelongsToModel;
    use Concerns\CanBeTraversed;
    use Concerns\CanBeValidated;
    use Concerns\DispatchesEvents;
    use Concerns\EvaluatesClosures;
    use Concerns\GeneratesCsrfTokens;
    use Concerns\HasActions;
    use Concerns\HasChildren;
    use Concerns\HasConfig;
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
        return [
            'form' => $this,
            'model' => $this->getModel(),
            'block' => $this->getBlock(),
        ];
    }

    public function getSnippetContext(): array
    {
        return [
            'form' => $this,
            'model' => $this->getModel(),
            'block' => $this->getBlock(),
        ];
    }

    public function submit(ValidatedInput $input): void
    {
        /** @var FormSubmission $submission */
        $submission = $this->applyFilters('submit:before', [
            'form' => $this,
            'submission' => $this->createFormSubmission($input),
        ], 'submission');

        foreach ($this->getActions() as $action) {
            $action->execute($submission);
        }

        $this->fireEvent('submit:after', [
            'form' => $this,
            'submission' => $submission,
        ]);
    }

    protected function createFormSubmission(ValidatedInput $input): FormSubmission
    {
        $challengeFields = $this->getFields()->whereInstanceOf(ProvidesChallenge::class);
        $uploadFields = $this->getFields()->whereInstanceOf(FileUpload::class);

        $files = $input->only($uploadFields->fieldNames());

        $data = $input->except([
            ...$challengeFields->fieldNames(),
            ...$uploadFields->fieldNames(),
        ]);

        return new FormSubmission($data, $files);
    }
}
