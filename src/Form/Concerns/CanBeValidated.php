<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;
use Webform\Form\Collections\Fields;
use Webform\Http\UploadedFile;
use Webform\Validation\ValidatedInput;
use Webform\Validation\Validator;

trait CanBeValidated
{
    protected ?ValidatedInput $validated = null;

    abstract public function getFields(): Fields;

    public function validated(): ?ValidatedInput
    {
        return $this->validated;
    }

    public function validate(?array $input = null): ValidatedInput
    {
        $request = App::instance()->request();

        $input ??= array_replace_recursive(
            $request->data(),
            UploadedFile::fromRequest($request),
        );

        /** @var Validator $validator */
        $validator = $this->apply('validate:before', [
            'validator' => $this->createValidator($input),
        ], 'validator');

        $validated = $validator->validate();

        $this->dispatch('validate:after', [
            'validator' => $validator,
        ]);

        return $this->validated = $validated;
    }

    protected function createValidator(array $input): Validator
    {
        $rules = [];
        $messages = [];
        $placeholders = [];

        foreach ($this->getFields() as $field) {
            $rules[$field->getName()] = $field->getValidationRules();
            $messages[$field->getName()] = $field->getValidationMessages();
            $placeholders[$field->getName()] = $field->getMessageContext();
        }

        return new Validator($input, $rules, $messages, $placeholders);
    }
}
