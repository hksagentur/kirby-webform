<?php

namespace Webform\Toolkit;

use JsonSerializable;
use Kirby\Toolkit\Html;
use Stringable;

/**
 * @implements Arrayable<int, Option>
 */
readonly class Option implements Arrayable, Htmlable, Jsonable, Stringable, JsonSerializable
{
    public function __construct(
        public string $value,
        public string $label,
        public bool $selected = false,
    ) {
    }

    public function isSelected(): bool
    {
        return $this->selected;
    }

    public function isNotSelected(): bool
    {
        return ! $this->isSelected();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function label(): string
    {
        return $this->label;
    }

    /** @return array{value: string, label: string, selected: bool} */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
            'selected' => $this->selected,
        ];
    }

    /** @return array{string,string} */
    public function toEntry(): array
    {
        return [$this->value, $this->label];
    }

    /** @return array{string,string} */
    public function toPair(): array
    {
        return $this->toEntry();
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toHtml(): string
    {
        return Html::tag('option', $this->label, attr: [
            'value' => $this->value,
            'selected' => $this->selected,
        ]);
    }

    public function toString(): string
    {
        return $this->toHtml();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /** @return array{value: string, label: string, selected: bool} */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
