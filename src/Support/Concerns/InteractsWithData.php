<?php

namespace Webform\Support\Concerns;

use BackedEnum;
use DateTime;
use DateTimeZone;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use stdClass;
use Webform\Support\A;

trait InteractsWithData
{
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
            if ($this->data($key, $placeholder) === $placeholder) {
                return false;
            }
        }

        return true;
    }

    public function boolean(string $key, bool $default = false): bool
    {
        return filter_var($this->data($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public function integer(string $key, int $default = 0): int
    {
        return intval($this->data($key, $default));
    }

    public function float(string $key, float $default = 0.0): float
    {
        return floatval($this->data($key, $default));
    }

    public function date(string $key, ?string $format = null, ?string $timezone = null): ?DateTime
    {
        if ($this->isNotFilled($key)) {
            return null;
        }

        $date = $this->data($key);

        if (! $date) {
            return null;
        }

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

        return $enumClass::tryFrom($this->data($key));
    }

    public function page(string $key): ?Page
    {
        if ($this->isNotFilled($key)) {
            return null;
        }

        return App::instance()->site()->find($this->data($key));
    }

    abstract public function all(?array $keys = null): array;

    public function except(array $keys): array
    {
        $results = $this->all();

        A::forget($results, $keys);

        return $results;
    }

    public function only(array $keys): array
    {
        $results = [];

        $data = $this->all();

        foreach ($keys as $key) {
            $value = A::get($data, $key, $placeholder = new stdClass());

            if ($value !== $placeholder) {
                A::set($results, $key, $value);
            }
        }

        return $results;
    }

    abstract protected function data(?string $key = null, mixed $default = null): mixed;

    protected function isEmptyString(string $key): bool
    {
        $value = $this->data($key);

        if (is_bool($value) || is_array($value) || trim((string) $value) !== '') {
            return false;
        }

        return true;
    }
}
