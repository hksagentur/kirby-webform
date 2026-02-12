<?php

namespace Webform\Exception;

use Kirby\Exception\Exception;
use Webform\Form\MessageBag;
use Webform\Form\Validator;

class ValidationException extends Exception
{
    protected ?MessageBag $errors = null;
    protected ?Validator $validator = null;

    public function __construct(array $arguments = [])
    {
        parent::__construct(array_merge([
            'key' => 'hksagentur.webform.validation',
            'fallback' => 'The given data was invalid.',
            'httpCode' => 422,
        ], $arguments));

        $this->errors = new MessageBag();

        $this->withErrors($arguments['errors'] ?? []);
        $this->withValidator($arguments['validator'] ?? null);
    }

    public function getErrors(): ?MessageBag
    {
        return $this->errors;
    }

    public function withErrors(array|MessageBag $errors): static
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
