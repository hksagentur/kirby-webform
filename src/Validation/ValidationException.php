<?php

namespace Webform\Validation;

use Kirby\Exception\Exception;

class ValidationException extends Exception
{
    protected ?Messages $errors = null;
    protected ?Validator $validator = null;

    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
             'key' => 'hksagentur.webform.validation',
             'fallback' => 'The given data was invalid.',
             'httpCode' => 422,
         ];

        $this->errors = new Messages();

        $this->withErrors($arguments['errors'] ?? []);
        $this->withValidator($arguments['validator'] ?? null);

        parent::__construct($arguments);
    }

    public function getErrors(): ?Messages
    {
        return $this->errors;
    }

    public function withErrors(array|Messages $errors): static
    {
        $this->errors->merge($errors);

        return $this;
    }

    public function getValidator(): ?Validator
    {
        return $this->validator;
    }

    public function withValidator(?Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }
}
