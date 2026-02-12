<?php

namespace Webform\Form;

use InvalidArgumentException;
use Kirby\Cms\App;
use Kirby\Cms\Blocks;
use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Filesystem\F;
use RuntimeException;
use Webform\Support\ViewComponent;

class Form extends ViewComponent
{
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
        return $this->getConfigPath();
    }

    public function getName(): string
    {
        return F::name($this->getConfigPath());
    }

    public function getActionUrl(): string
    {
        return Url::to("webform/{$this->getConfigPath()}");
    }

    public function submit(?Page $referrer = null): void
    {
        $actions = array_filter([
            ...$this->getActions(),
            ...$this->getReferrerActions($referrer),
        ]);

        if (empty($actions)) {
            throw new RuntimeException(sprintf(
                'Form [%s] does not provide any actions to perform.',
                $this->getId(),
            ));
        }

        $submission = new FormSubmission(
            data: $this->validate(),
            files: $this->saveUploadedFiles(),
        );

        foreach ($actions as $action) {
            $action->execute($submission);
        }
    }

    protected function getContentBlocks(?Page $page): Blocks
    {
        $field = $page?->content()->get(
            App::instance()->option('hksagentur.webform.referrer.blocks', 'blocks')
        );

        if (! $field->exists() || $field->isEmpty()) {
            return new Blocks();
        }

        try {
            return $field->toBlocks();
        } catch (InvalidArgumentException) {
            return new Blocks();
        }
    }

    protected function getWebformBlocks(?Page $page): Blocks
    {
        return $this->getContentBlocks($page)
            ->filterBy('type', 'webform')
            ->filterBy('formId', $this->getId());
    }

    protected function getReferrerActions(?Page $page): array
    {
        $actions = [];

        /** @var \Webform\Cms\WebformBlock */
        foreach ($this->getWebformBlocks($page) as $block) {
            $actions[] = $block->action()->form($this);
        }

        return $actions;
    }

    protected function resolveDefaultEvaluationData(): array
    {
        return [
            'form' => $this,
        ];
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [
            'form' => $this,
            'childComponents' => $this->getComponents(depth: 1),
        ];
    }
}
