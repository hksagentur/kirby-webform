<?php

namespace Webform\Form;

use Webform\Form\Form;
use Webform\Session\TransientData;

class Manager
{
    protected static ?self $instance = null;

    protected array $forms = [];

    protected ?MessageBag $messageBag = null;
    protected ?TransientData $transientData = null;

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ?? new static();
    }

    public function form(string $path): Form
    {
        return $this->forms[$path] ??= Form::from($path);
    }

    public function transient(): TransientData
    {
        return $this->transientData ??= TransientData::instance();
    }

    public function status(): ?StatusMessage
    {
        $message = $this->transient()->get('webform.form.status');

        if (! $message) {
            return null;
        }

        return new StatusMessage($message);
    }

    public function errors(): MessageBag
    {
        return $this->messageBag ??= new MessageBag(
            $this->transient()->get('webform.form.errors', [])
        );
    }
}
