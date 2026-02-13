<?php

namespace Webform\Form;

use ArrayAccess;
use ArrayIterator;
use BackedEnum;
use DateTime;
use DateTimeZone;
use IteratorAggregate;
use JsonSerializable;
use stdClass;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use LogicException;
use Stringable;
use Webform\Support\A;

readonly class ValidatedInput implements ArrayAccess, IteratorAggregate, JsonSerializable, Stringable
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $input = [],
    ) {}

    public function isFilled(string|array $keys): bool
    {
        foreach (A::wrap($keys) as $key) {
            if ($this->isEmptyString($key)) {
                return false;
            }
        }

        return true;
    }

    public function isNotFilled(string|array $keys): bool
    {
        foreach (A::wrap($keys) as $key) {
            if (! $this->isEmptyString($key)) {
                return false;
            }
        }

        return true;
    }

    public function exists(string|array $keys): bool
    {
        $placeholder = new stdClass();

        foreach (A::wrap($keys) as $key) {
            if ($this->input($key, $placeholder) === $placeholder) {
                return false;
            }
        }

        return true;
    }

    public function input(string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return $this->input;
        }

        return A::get($this->input, $key, $default);
    }

    public function boolean(string $key, bool $default = false): bool
    {
        return filter_var($this->input($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public function integer(string $key, int $default = 0): int
    {
        return intval($this->input($key, $default));
    }

    public function float(string $key, float $default = 0.0): float
    {
        return floatval($this->input($key, $default));
    }

    public function date(string $key, ?string $format = null, ?string $timezone = null): ?DateTime
    {
        if ($this->isNotFilled($key)) {
            return null;
        }

        $date = $this->input($key);
        $timezone = new DateTimeZone($timezone ?? 'UTC');

        if (is_null($format)) {
            return new DateTime($date, $timezone);
        }

        return DateTime::createFromFormat($format, $date, $timezone);
    }

    public function enum(string $key, string $enumClass): ?BackedEnum
    {
        if ($this->isNotFilled($key) || ! is_subclass_of($enumClass, BackedEnum::class)) {
            return null;
        }

        return $enumClass::tryFrom($this->input($key));
    }

    public function page(string $key): ?Page
    {
        if ($this->isNotFilled($key)) {
            return null;
        }

        return App::instance()->site()->find($this->input($key));
    }

    public function all(array $keys = null): array
    {
        $input = $this->input();

        if (is_null($keys)) {
            return $input;
        }

        $data = [];

        foreach ($keys as $key) {
            A::set($data, $key, $this->input($key));
        }

        return $data;
    }

    public function except(array $keys): array
    {
        $results = $this->all();

        A::forget($results, $keys);

        return $results;
    }

    public function only(array $keys): array
    {
        $results = [];

        $placeholder = new stdClass();

        foreach ($keys as $key) {
            $value = $this->input($key, $placeholder);

            if ($value !== $placeholder) {
                A::set($results, $key, $value);
            }
        }

        return $results;
    }

    public function merge(array $data): static
    {
        return new static(array_merge(
            $this->input(),
            $data,
        ));
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function asUrlParameters(): string
    {
        return http_build_query($this->toArray(), '', '&', PHP_QUERY_RFC3986);
    }

    public function asFormParameters(): string
    {
        return http_build_query($this->toArray(), '', '&', PHP_QUERY_RFC1738);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->input());
    }

    public function offsetExists(mixed $key): bool
    {
        return $this->exists($key);
    }

    public function offsetGet(mixed $key): mixed
    {
        return $this->input($key);
    }

    public function offsetSet(mixed $key, mixed $value): void
    {
        throw new LogicException(sprintf(
            'Cannot modify validated input. Attempted to set [%s] to [%s].',
            $key,
            get_debug_type($value),
        ));
    }

    public function offsetUnset(mixed $key): void
    {
        throw new LogicException(sprintf(
            'Cannot modify validated input. Attempted to unset [%s].',
            $key,
        ));
    }

    public function jsonSerialize(): array
    {
        return $this->all();
    }

    public function __isset(string $name): bool
    {
        return $this->exists($name);
    }

    public function __get(string $name): mixed
    {
        return $this->input($name);
    }

    public function __call(string $name, array $arguments = []): mixed
    {
        return $this->input($name);
    }

    public function __debugInfo(): array
    {
        return $this->input();
    }

    public function __toString(): string
    {
        return $this->asFormParameters();
    }

    protected function isEmptyString(string $key): bool
    {
        $value = $this->input($key);

        if (is_bool($value) || is_array($value) || trim((string) $value) !== '') {
            return false;
        }

        return true;
    }
}
