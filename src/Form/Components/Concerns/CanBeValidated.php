<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Webform\Form\Components\Component;
use Webform\Form\Components\Contracts\CanBeLengthConstrained;
use Webform\Form\Components\Contracts\CanBeRequired;

trait CanBeValidated
{
    protected array $validationRules = [];
    protected array $validationMessages = [];

    public function getValidationMessages(): array
    {
        $messages = [];

        foreach ($this->validationMessages as $key => $message) {
            $messages[$key] = $this->evaluate($message);
        }

        return $messages;
    }

    public function getValidationRules(): array
    {
        $rules = [];

        if ($this instanceof CanBeRequired) {
            if ($this->isRequired()) {
                $rules['required'] = [];
            }
        }

        if ($this instanceof CanBeLengthConstrained) {
            if ($length = $this->getLength()) {
                $rules['size'] = [$length, '=='];
            }

            if ($minLength = $this->getMinLength()) {
                $rules['minLength'] = [$minLength];
            }

            if ($maxLength = $this->getMaxLength()) {
                $rules['maxLength'] = [$maxLength];
            }
        }

        foreach ($this->validationRules as [$rule, $condition]) {
            if (! $this->evaluate($condition)) {
                continue;
            }

            $rule = $this->evaluate($rule);

            if (is_array($rule)) {
                $rules = [...$rules, ...$rule];
            } else {
                $rules[$rule] = [];
            }
        }

        return $rules;
    }

    public function accepted(bool|Closure $condition = true): static
    {
        return $this->rule('accepted', $condition);
    }

    public function after(string|Closure $date, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($date) {
            return ['after' => [$component->evaluate($date)]];
        }, $condition);
    }

    public function afterOrEqual(string|Closure $date, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($date) {
            return ['afterOrEqual' => [$component->evaluate($date)]];
        }, $condition);
    }

    public function alpha(bool|Closure $condition = true): static
    {
        return $this->rule('alpha', $condition);
    }

    public function alphaNum(bool|Closure $condition = true): static
    {
        return $this->rule('alphanum', $condition);
    }

    public function before(string|Closure $date, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($date) {
            return ['before' => [$component->evaluate($date)]];
        }, $condition);
    }

    public function beforeOrEqual(string|Closure $date, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($date) {
            return ['beforeOrEqual' => [$component->evaluate($date)]];
        }, $condition);
    }

    public function between(int|float|Closure $min, int|float|Closure $max, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($min, $max) {
            return ['between' => [$component->evaluate($min), $component->evaluate($max)]];
        }, $condition);
    }

    public function contains(string|Closure $needle, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($needle) {
            return ['contains' => [$component->evaluate($needle)]];
        }, $condition);
    }

    public function date(bool|Closure $condition = true): static
    {
        return $this->rule('date', $condition);
    }

    public function denied(bool|Closure $condition = true): static
    {
        return $this->rule('denied', $condition);
    }

    public function different(mixed $value, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($value) {
            return ['different' => [$component->evaluate($value)]];
        }, $condition);
    }

    public function doesntContain(string|Closure $needle, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($needle) {
            return ['notContains' => [$component->evaluate($needle)]];
        }, $condition);
    }

    public function email(bool|Closure $condition = true): static
    {
        return $this->rule('email', $condition);
    }

    public function empty(bool|Closure $condition = true): static
    {
        return $this->rule('empty', $condition);
    }

    public function endsWith(string|Closure $needle, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($needle) {
            return ['endsWith' => [$component->evaluate($needle)]];
        }, $condition);
    }

    public function exists(string|Closure $collection, string|Closure|null $column = null): static
    {
        return $this->rule(static function (Component $component) use ($collection, $column) {
            $collection = $component->evaluate($collection);
            $column = $component->evaluate($column);

            if (! $column) {
                return ['exists' => [$collection]];
            }

            return ['exists' => [$collection, $column]];
        }, static fn (Component $component): bool => (bool) $component->evaluate($collection));
    }

    public function filename(bool|Closure $condition = true): static
    {
        return $this->rule('filename', $condition);
    }

    public function in(array|Closure $values, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($values) {
            return ['in' => [$component->evaluate($values)]];
        }, $condition);
    }

    public function integer(bool|Closure $condition = true): static
    {
        return $this->rule('integer', $condition);
    }

    public function ip(bool|Closure $condition = true): static
    {
        return $this->rule('ip', $condition);
    }

    public function json(bool|Closure $condition = true): static
    {
        return $this->rule('json', $condition);
    }

    public function less(float|Closure $max, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($max) {
            return ['less' => [$component->evaluate($max)]];
        }, $condition);
    }

    public function match(string|Closure $pattern, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($pattern) {
            return ['match' => [$component->evaluate($pattern)]];
        }, $condition);
    }

    public function max(float|Closure $max, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($max) {
            return ['max' => [$component->evaluate($max)]];
        }, $condition);
    }

    public function min(float|Closure $min, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($min) {
            return ['min' => [$component->evaluate($min)]];
        }, $condition);
    }

    public function maxWords(int|Closure $max, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($max) {
            return ['maxWords' => [$component->evaluate($max)]];
        }, $condition);
    }

    public function minWords(int|Closure $min, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($min) {
            return ['minWords' => [$component->evaluate($min)]];
        }, $condition);
    }

    public function more(float|Closure $min, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($min) {
            return ['more' => [$component->evaluate($min)]];
        }, $condition);
    }

    public function notEmpty(bool|Closure $condition = true): static
    {
        return $this->rule('notEmpty', $condition);
    }

    public function notIn(array|Closure $values, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($values) {
            return ['notIn' => [$component->evaluate($values)]];
        }, $condition);
    }

    public function num(bool|Closure $condition = true): static
    {
        return $this->rule('num', $condition);
    }

    public function requiredIf(bool|Closure $required = true, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($required) {
            return ['requiredIf' => [$component->evaluate($required)]];
        }, $condition);
    }

    public function requiredUnless(bool|Closure $required = true, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($required) {
            return ['requiredUnless' => [$component->evaluate($required)]];
        }, $condition);
    }

    public function same(mixed $value, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($value) {
            return ['same' => [$component->evaluate($value)]];
        }, $condition);
    }

    public function startsWith(string|Closure $needle, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($needle) {
            return ['startsWith' => [$component->evaluate($needle)]];
        }, $condition);
    }

    public function tel(bool|Closure $condition = true): static
    {
        return $this->rule('tel', $condition);
    }

    public function time(bool|Closure $condition = true): static
    {
        return $this->rule('time', $condition);
    }

    public function url(bool|Closure $condition = true): static
    {
        return $this->rule('url', $condition);
    }

    public function uuid(string|Closure|null $type = null, bool|Closure $condition = true): static
    {
        return $this->rule(static function (Component $component) use ($type) {
            $type = $component->evaluate($type);

            if ($type) {
                return ['uuid' => [$type]];
            }

            return ['uuid' => []];
        }, $condition);
    }

    public function rule(string|Closure $name, bool|Closure $condition = true): static
    {
        $this->validationRules[] = [$name, $condition];

        return $this;
    }

    public function rules(array|Closure $rules, bool|Closure $condition): static
    {
        if ($rules instanceof Closure) {
            return $this->rule($rules, $condition);
        }

        $this->validationRules = [
            ...$this->validationRules,
            ...array_map(static fn (string $rule) => [$rule, $condition], $rules)
        ];

        return $this;
    }

    public function validationMessages(array $messages): static
    {
        $this->validationMessages = [
            ...$this->validationMessages,
            ...$messages,
        ];

        return $this;
    }
}
