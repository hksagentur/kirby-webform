<?php

namespace Webform\Form;

class FormFactory
{
    protected static ?self $instance = null;

    /** @var array<string, Form> */
    protected array $forms = [];

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ??= new static();
    }

    public function createFromConfig(string $path): ?Form
    {
        return $this->forms[$path] ??= Form::tryLoadFromConfig($path);
    }
}
