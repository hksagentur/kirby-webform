<?php

namespace Webform\Form;

use Kirby\Cms\App;
use Kirby\Cms\Url;
use Kirby\Toolkit\Str;
use Webform\Support\ViewComponent;

class Form extends ViewComponent
{
    use Concerns\BelongsToBlock;
    use Concerns\BelongsToModel;
    use Concerns\CanBeValidated;
    use Concerns\GeneratesCsrfTokens;
    use Concerns\HandlesFileUploads;
    use Concerns\HasActions;
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

    public static function loadFromConfig(string $path): static
    {
        return Config::create($path)->readOrFail();
    }

    public static function tryLoadFromConfig(string $path): ?static
    {
        return Config::create($path)->read();
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

    public function submit(ValidatedInput $input): void
    {
        $submission = FormSubmission::fromInput(
            data: $input->except(
                $this->getChallenges()->fieldNames()
            ),
            files: $this->getUploadedFiles(),
        );

        /** @var FormSubmission $submission */
        $submission = App::instance()->apply('webform.submit:before', [
            'form' => $this,
            'submission' => $submission,
        ], 'submission');

        foreach ($this->getActions() as $action) {
            $action->execute($submission);
        }

        App::instance()->trigger('webform.submit:after', [
            'form' => $this,
            'submission' => $submission,
        ]);
    }

    protected function resolveDefaultEvaluationData(): array
    {
        return [
            'form' => $this,
            'model' => $this->getModel(),
            'block' => $this->getBlock(),
        ];
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [
            'form' => $this,
            'model' => $this->getModel(),
            'block' => $this->getBlock(),
        ];
    }
}
