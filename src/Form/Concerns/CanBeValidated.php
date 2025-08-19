<?php

namespace Webform\Form\Concerns;

use Kirby\Toolkit\A;
use Webform\Form\Validator;

trait CanBeValidated
{
    protected ?Validator $validator = null;

    public function validate(): array
    {
        return $this->getValidator()->validate();
    }

    public function validated(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->getValidator()->validated(), $key, $default);
    }

    protected function getValidator(): Validator
    {
        return $this->validator ??= method_exists($this, 'validator')
            ? $this->validator()
            : $this->createDefaultValidator();
    }

    protected function createDefaultValidator(): Validator
    {
        $data = [];
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($this->getFields() as $field) {
            $data[$field->getName()] = $field->getValue();
            $rules[$field->getName()] = $field->getValidationRules();
            $messages[$field->getName()] = $field->getValidationMessages();
            $attributes[$field->getName()] = $field->getValidationAttribute();
        }

        return new Validator($data, $rules, $messages, $attributes);
    }
}
