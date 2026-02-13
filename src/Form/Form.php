<?php

namespace Webform\Form;

use Kirby\Cms\App;
use Kirby\Cms\Url;
use Webform\Support\ViewComponent;

class Form extends ViewComponent
{
    use Concerns\BelongsToBlock;
    use Concerns\BelongsToModel;
    use Concerns\CanBeValidated;
    use Concerns\GeneratesCsrfTokens;
    use Concerns\HandlesFileUploads;
    use Concerns\HasActions;
    use Concerns\HasComponents;
    use Concerns\HasConfig;

    protected string $snippet = 'webform/form';

    public static function create(): static
    {
        return new static();
    }

    public static function from(string $path): static
    {
        return Config::create($path)->readOrFail();
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
        return Url::to("webform/{$this->getId()}");
    }

    public function submit(ValidatedInput $input): void
    {
        $input = App::instance()->apply('webform.submit:before', [
            'form' => $this,
            'input' => $input,
        ], 'input');

        foreach ($this->getActions() as $action) {
            $action->execute($input);
        }

        App::instance()->trigger('webform.submit:after', [
            'form' => $this,
            'input' => $input,
        ]);
    }

    protected function resolveDefaultEvaluationData(): array
    {
        return [
            'model' => $this->model,
            'block' => $this->block,
            'form' => $this,
        ];
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [
            'model' => $this->model,
            'block' => $this->block,
            'form' => $this,
            'childComponents' => $this->getComponents(depth: 1),
        ];
    }
}
