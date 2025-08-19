<?php

namespace Webform\Form;

use BackedEnum;
use DateTime;
use DateTimeZone;
use stdClass;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Filesystem\File;
use Webform\Support\A;

class FormSubmission
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $data = [],
        /** @var array<string, File[]> */
        protected array $files = [],
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

    public function isEmptyString(string|array $keys): bool
    {
        foreach (A::wrap($keys) as $key) {
            $value = $this->data($key);

            if (is_bool($value) || is_array($value) || trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    public function data(string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return $this->data;
        }

        return A::get($this->data, $key, $default);
    }

    public function files(string $key = null): array
    {
        if (is_null($key)) {
            return $this->files;
        }

        return A::get($this->files, $key, []);
    }

    public function file(string $key): ?File
    {
        return A::first($this->files($key));
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

    public function date(string $key, ?string $format = null, string $timezone = null): ?DateTime
    {
        if ($this->isNotFilled($key)) {
            return null;
        }

        $date = $this->data($key);
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

    public function all(array $keys = null): array
    {
        $input = array_replace_recursive($this->data(), $this->files());

        if (is_null($keys)) {
            return $input;
        }

        $results = [];

        foreach ($keys as $key) {
            A::set($results, $key, A::get($input, $key));
        }

        return $results;
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

        $data = $this->all();
        $placeholder = new stdClass();

        foreach ($keys as $key) {
            $value = A::get($data, $key, $placeholder);

            if ($value !== $placeholder) {
                A::set($results, $key, $value);
            }
        }

        return $results;
    }
}
