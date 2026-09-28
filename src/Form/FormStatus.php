<?php

namespace Webform\Form;

use InvalidArgumentException;
use JsonSerializable;
use Kirby\Cms\App;
use Stringable;
use Webform\Form\Concerns\BelongsToChannel;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Flash;
use Webform\Toolkit\Htmlable;
use Webform\Toolkit\Jsonable;
use Webform\Validation\Messages;

/**
 * @implements Arrayable<string, mixed>
 */
class FormStatus implements Arrayable, Htmlable, Jsonable, JsonSerializable, Stringable
{
    use BelongsToChannel;

    public function __construct(
        protected string $channel,
        protected FormStatusType $type,
        protected string $message,
        protected Messages $errors,
    ) {
    }

    public static function create(
        string $channel,
        FormStatusType|string $type = FormStatusType::Success,
        ?string $message = null,
        Arrayable|array|null $errors = null,
    ): static {
        $status = FormStatusType::of($type);

        if (! $status) {
            throw new InvalidArgumentException("Unknown status type [{$type}].");
        }

        return new static(
            channel: $channel,
            type: $status,
            message: $message ?? $status->message(),
            errors: Messages::from($errors),
        );
    }

    public static function fromSession(?string $channel = null): ?static
    {
        $channel ??= static::latestChannel();

        if (! $channel) {
            return null;
        }

        $value = Flash::get(static::channelKeyFor($channel, 'status'));

        if (! is_array($value)) {
            return null;
        }

        $type = FormStatusType::tryFrom($value['type'] ?? '');

        if (! $type) {
            return null;
        }

        return static::create(
            channel: $channel,
            type: $type,
            message: $value['message'] ?? null,
            errors: Flash::get(static::channelKeyFor($channel, 'errors'), []),
        );
    }

    public function hasErrors(): bool
    {
        return $this->is(FormStatusType::Invalid);
    }

    public function isSuccess(): bool
    {
        return $this->is(FormStatusType::Success);
    }

    public function isWarning(): bool
    {
        return $this->is(FormStatusType::Warning);
    }

    public function isError(): bool
    {
        return $this->is(FormStatusType::Error);
    }

    public function is(FormStatusType|string $type): bool
    {
        return $this->type === FormStatusType::of($type);
    }

    public function getType(): FormStatusType
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getErrors(): Messages
    {
        return $this->errors;
    }

    public function flash(): static
    {
        $this->flashChannel();
        $this->flashStatus();
        $this->flashErrors();

        return $this;
    }

    public function toString(): string
    {
        return $this->message;
    }

    public function toHtml(?Form $form = null): string
    {
        return App::instance()->snippet('webform/status', [
            'status' => $this,
            'form' => $form,
        ], return: true);
    }

    public function toArray(): array
    {
        return [
            'channel' => $this->channel,
            'type' => $this->type->slug(),
            'message' => $this->message,
            'errors' => $this->getErrors()->toArray(),
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    protected function flashStatus(): void
    {
        Flash::put(static::channelKeyFor($this->channel, 'status'), [
            'type' => $this->type->slug(),
            'message' => $this->message,
        ]);
    }

    protected function flashErrors(): void
    {
        $errors = $this->getErrors();

        if (! $errors->hasAny()) {
            return;
        }

        Flash::put(static::channelKeyFor($this->channel, 'errors'), $errors->toArray());
    }
}
