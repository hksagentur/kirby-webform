<?php

namespace Webform\Template\Concerns;

use Closure;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;

trait CanFormatMessages
{
    protected array|Closure $messageData = [];

    abstract public function getMessageContext(): array;

    public function getMessageData(): array
    {
        return $this->evaluate($this->messageData);
    }

    public function messageData(array|Closure $data): static
    {
        $this->messageData = $data;

        return $this;
    }

    public function translateMessage(string $key, array $data = [], ?string $locale = null): string
    {
        return I18n::template(
            key: $key,
            replace: [
                ...$this->getMessageContext(),
                ...$this->getMessageData(),
                ...$data,
            ],
            locale: $locale,
        );
    }

    public function formatMessage(?string $text, array $data = []): string
    {
        return Str::template(
            string: $text,
            data: [
                ...$this->getMessageContext(),
                ...$this->getMessageData(),
                ...$data,
            ],
        );
    }
}
