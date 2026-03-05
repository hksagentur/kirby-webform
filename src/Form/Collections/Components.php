<?php

namespace Webform\Form\Collections;

use Stringable;
use Webform\Form\Components\Component;
use Webform\Form\Components\Contracts\HasChildren;
use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Button;
use Webform\Form\Components\Field;
use Webform\Form\Form;
use Webform\Toolkit\Collection;
use Webform\Toolkit\Htmlable;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Component
 *
 * @extends Collection<TKey, TValue>
 */
class Components extends Collection implements Htmlable, Stringable
{
    /**
     * @var ?static<TKey, TValue>
     */
    protected ?self $index = null;

    /**
     * @param array<TKey, TValue>  $components
     */
    public function __construct(
        protected array $components = []
    ) {
    }

    public function form(Form $form): static
    {
        return $this->each(fn (Component $component) => $component->form($form));
    }

    /**
     * @return ?TValue
     */
    public function find(string $key): ?Component
    {
        return $this->findBy('key', $key);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->components;
    }

    /**
     * @return Components<TKey, Button>
     */
    public function actions(): Components
    {
        return $this->whereInstanceOf(Button::class)->filter(fn (Button $component) => $component->hasAction());
    }

    /**
     * @return Challenges<TKey, TValue&ProvidesChallenge>
     */
    public function challenges(): Challenges
    {
        return $this->whereInstanceOf(ProvidesChallenge::class)->pipeInto(Challenges::class);
    }

    /**
     * @return Fields<TKey, Field>
     */
    public function fields(): Fields
    {
        return $this->whereInstanceOf(Field::class)->pipeInto(Fields::class);
    }

    /**
     * @return string[]
     */
    public function componentKeys(): array
    {
        return array_filter($this->pluck('key'));
    }

    /**
     * @return string[]
     */
    public function fieldNames(): array
    {
        return array_filter($this->fields()->pluck('name'));
    }

    /**
     * @return static<TKey, TValue>
     */
    public function index(): static
    {
        return $this->index ??= $this->pipe(static function (self $collection) {
            $components = [];

            foreach ($collection as $component) {
                $components[] = $component;

                if ($component instanceof HasChildren) {
                    $components = [
                        ...$components,
                        ...$component->getChildren()->index(),
                    ];
                }
            }

            return new static($components);
        });
    }

    public function toString(): string
    {
        return $this->toHtml();
    }

    public function toHtml(): string
    {
        return implode("\n", $this->map(fn (Component $component) => $component->toHtml()));
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
