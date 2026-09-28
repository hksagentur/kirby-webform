<?php

namespace Webform\Form;

use Kirby\Toolkit\A;
use Webform\Form\Concerns\BelongsToChannel;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Flash;
use Webform\Toolkit\Payload;

class FormInput extends Payload
{
    use BelongsToChannel;

    public function __construct(
        protected string $channel,
        /** @var array<string, mixed> */
        protected array $data,
    ) {
    }

    public static function create(string $channel, Arrayable|array $data): static
    {
        $data = $data instanceof Arrayable
            ? $data->toArray()
            : $data;

        return new static($channel, $data);
    }

    public static function fromSession(?string $channel = null): ?static
    {
        $channel ??= static::latestChannel();

        if (! $channel) {
            return null;
        }

        $data = Flash::get(static::channelKeyFor($channel, 'input'));

        if (! is_array($data)) {
            return null;
        }

        return static::create($channel, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data($key, $default);
    }

    public function all(?array $keys = null): array
    {
        if (is_null($keys)) {
            return $this->data;
        }

        return $this->only($keys);
    }

    public function flash(): static
    {
        $this->flashChannel();
        $this->flashInput();

        return $this;
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->data, $key, $default);
    }

    protected function flashInput(): void
    {
        Flash::put(static::channelKeyFor($this->channel, 'input'), $this->data());
    }
}
