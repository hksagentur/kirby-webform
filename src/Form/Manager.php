<?php

namespace Webform\Form;

class Manager
{
    protected static ?Manager $instance = null;

    /** @var array<string, Form> */
    protected array $forms = [];

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ??= new static();
    }

    public function form(string $path): ?Form
    {
        return $this->forms[$path] ??= Form::tryLoadFromConfig($path);
    }
}
