<?php

namespace Webform\Validation;

use Closure;
use Kirby\Toolkit\A;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\V;
use ReflectionFunction;
use stdClass;

class Validator
{
    protected ?Messages $errors = null;

    protected array $data = [];
    protected array $rules = [];
    protected array $placeholders = [];

    protected array $customMessages = [];

    public function __construct(
        array $data,
        array $rules,
        array $messages = [],
        array $placeholders = [],
    ) {
        $this->data = $data;
        $this->rules = $rules;
        $this->placeholders = $placeholders;

        $this->customMessages = $messages;
    }

    public function isValidated(): bool
    {
        return $this->errors !== null;
    }

    public function isNotValidated(): bool
    {
        return ! $this->isValidated();
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getValue(string $field, mixed $default = null): mixed
    {
        return A::get($this->data, $field, $default);
    }

    public function getMessageData(string $field, array $default = []): array
    {
        return A::get($this->placeholders, $field, $default);
    }

    public function valid(): array
    {
        if ($this->isNotValidated()) {
            $this->passes();
        }

        return array_diff_key($this->data, array_flip($this->errors->keys()));
    }

    public function invalid(): array
    {
        if ($this->isNotValidated()) {
            $this->passes();
        }

        return array_intersect_key($this->data, array_flip($this->errors->keys()));
    }

    public function errors(): Messages
    {
        if ($this->isNotValidated()) {
            $this->passes();
        }

        return $this->errors;
    }

    public function passes(): bool
    {
        $this->errors = new Messages();

        foreach ($this->rules as $field => $rules) {
            $value = $this->getValue($field);

            $filled = (
                $value !== null &&
                $value !== '' &&
                $value !== []
            );

            foreach ($rules as $rule => $parameters) {
                if ($rule === 'required') {
                    if ($filled) {
                        continue;
                    }
                } elseif ($rule === 'requiredIf') {
                    if ($filled || V::notEmpty(...$parameters)) {
                        continue;
                    }
                } elseif ($rule === 'requiredUnless') {
                    if ($filled || V::empty(...$parameters)) {
                        continue;
                    }
                } elseif (A::has(['file', 'mimeType', 'minFileSize', 'maxFileSize', 'image', 'document', 'video'], $rule)) {
                    if (A::every($value, fn (mixed $file): bool => V::$rule($file, ...$parameters))) {
                        continue;
                    }
                } elseif ($filled) {
                    if (V::$rule($value, ...$parameters)) {
                        continue;
                    }
                } else {
                    continue;
                }

                $this->addMessage($field, $rule, [$value, ...$parameters]);
            }
        }

        return $this->errors->isEmpty();
    }

    public function fails(): bool
    {
        return ! $this->passes();
    }

    public function validate(): ValidatedInput
    {
        if ($this->fails()) {
            throw new ValidationException([
                'validator' => $this,
                'errors' => $this->errors,
            ]);
        }

        return $this->validated();
    }

    /**
     *  @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function validated(): ValidatedInput
    {
        if ($this->isNotValidated() && $this->fails()) {
            throw new ValidationException([
                'validator' => $this,
                'errors' => $this->errors,
            ]);
        }

        $data = [];

        foreach ($this->rules as $field => $rules) {
            $value = $this->getValue($field, $default = new stdClass());

            if ($value !== $default) {
                $data[$field] = $value;
            }
        }

        return new ValidatedInput($data);
    }

    protected function getMessage(string $field, string $rule): string
    {
        $normalizedField = Str::camel($field);
        $normalizedRule = Str::camel($rule);

        $message = A::get($this->customMessages, "{$normalizedField}.{$normalizedRule}");

        if ($message !== null) {
            return $message;
        }

        $keys = [
            "hksagentur.webform.validation.message.{$normalizedField}.{$normalizedRule}",
            "hksagentur.webform.validation.message.{$normalizedRule}",
            "hksagentur.webform.validation.message.{$normalizedField}",
        ];

        foreach ($keys as $key) {
            if ($message = I18n::translate($key)) {
                return $message;
            }
        }

        return I18n::translate("hksagentur.webform.validation.message.fallback", "Validation rule failed [{$rule}]");
    }

    protected function getValidator(string $name): ?Closure
    {
        return A::get(V::validators(), $name);
    }

    protected function getValidatorParameters(mixed $validator, array $values = []): array
    {
        $arguments = [];

        if (! ($validator instanceof Closure)) {
            return $arguments;
        }

        $parameters = (new ReflectionFunction($validator))->getParameters();

        if (! $parameters) {
            return $arguments;
        }

        foreach ($parameters as $index => $parameter) {
            $value = $values[$index] ?? ($parameter->isOptional() ? $parameter->getDefaultValue() : null);

            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $arguments[$parameter->getName()] = $value;
        }

        return $arguments;
    }

    protected function formatMessage(string $message, string $field, string $rule, array $parameters = []): string
    {
        if (! Str::matches($message, '/{{1,2}.*?}{1,2}/')) {
            return $message;
        }

        $validator = $this->getValidator($rule);

        $data = A::merge(
            $this->getValidatorParameters($validator, $parameters),
            $this->getMessageData($field),
        );

        return Str::template($message, $data, ['fallback' => '?']);
    }

    protected function addMessage(string $field, string $rule, array $parameters = []): void
    {
        $this->errors->add($field, $this->formatMessage(
            message: $this->getMessage($field, $rule),
            field: $field,
            rule: $rule,
            parameters: $parameters,
        ));
    }
}
