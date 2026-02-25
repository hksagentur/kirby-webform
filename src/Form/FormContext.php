<?php

namespace Webform\Form;

use Kirby\Cms\S;
use Webform\Validation\Message;
use Webform\Validation\Messages;

readonly class FormContext
{
    protected ?Message $status;
    protected Messages $errors;

    public function __construct(?Message $status = null, ?Messages $errors = null)
    {
        $this->status = $status;
        $this->errors = $errors ?? new Messages();
    }

    public static function fromSession(string $key): static
    {
        $status = Message::tryFrom(
            S::get("webform.form.{$key}.status")
        );

        $errors = Messages::from(
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

    public function getStatus(): ?Message
    {
        return $this->status;
    }

    public function getErrors(): Messages
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
