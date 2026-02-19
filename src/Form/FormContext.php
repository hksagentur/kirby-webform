<?php

namespace Webform\Form;

use Kirby\Cms\S;

readonly class FormContext
{
    protected ?StatusMessage $status;
    protected MessageBag $errors;

    public function __construct(?StatusMessage $status = null, ?MessageBag $errors = null)
    {
        $this->status = $status;
        $this->errors = $errors ?? new MessageBag();
    }

    public static function fromSession(string $key): static
    {
        $status = StatusMessage::tryFrom(
            S::get("webform.form.{$key}.status")
        );

        $errors = MessageBag::from(
            S::get("webform.form.{$key}.errors", [])
        );

        return new static($status, $errors);
    }

    public function hasStatus(): bool
    {
        return $this->status !== null;
    }

    public function hasErrors(): bool
    {
        return $this->errors->isNotEmpty();
    }

    public function getStatus(): ?StatusMessage
    {
        return $this->status;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'errors' => $this->errors,
        ];
    }
}
