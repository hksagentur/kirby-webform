<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;
use Webform\Form\Components;
use Webform\Form\ValidatedInput;
use Webform\Form\Validator;

trait CanBeValidated
{
    abstract public function getChildren(): Components;

    public function validate(?array $input = null): ValidatedInput
    {
        $input ??= App::instance()->request()->data();

        /** @var Validator $validator */
        $validator = App::instance()->apply('webform.validate:before', [
            'form' => $this,
            'validator' => $this->createValidator($input),
        ], 'validator');

        $validated = $validator->validate();

        App::instance()->trigger('webform.validate:after', [
            'form' => $this,
            'validator' => $validator,
        ]);

        return $validated;
    }

    protected function createValidator(array $input): Validator
    {
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($this->getChildren()->getIndex()->getFields() as $field) {
            $rules[$field->getName()] = $field->getValidationRules();
            $messages[$field->getName()] = $field->getValidationMessages();
            $attributes[$field->getName()] = $field->getValidationAttribute();
        }

        return new Validator($input, $rules, $messages, $attributes);
    }
}
