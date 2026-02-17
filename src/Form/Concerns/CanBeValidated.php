<?php

namespace Webform\Form\Concerns;

use Webform\Form\Components;
use Webform\Form\ValidatedInput;
use Webform\Form\Validator;

trait CanBeValidated
{
    protected ?Validator $validator = null;

    public function getValidator(): Validator
    {
        return $this->validator ??= method_exists($this, 'validator')
            ? $this->validator()
            : $this->createDefaultValidator();
    }

    public function validate(): ValidatedInput
    {
        return $this->getValidator()->validate();
    }

    protected function createDefaultValidator(): Validator
    {
        $data = [];
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($this->getChildren()->getIndex()->getFields() as $field) {
            $data[$field->getName()] = $field->getValue();
            $rules[$field->getName()] = $field->getValidationRules();
            $messages[$field->getName()] = $field->getValidationMessages();
            $attributes[$field->getName()] = $field->getValidationAttribute();
        }

        return new Validator($data, $rules, $messages, $attributes);
    }
}
