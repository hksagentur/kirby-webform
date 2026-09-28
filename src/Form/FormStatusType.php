<?php

namespace Webform\Form;

use Kirby\Toolkit\I18n;
use Webform\Toolkit\Concerns\InvokableCases;

enum FormStatusType: string
{
    use InvokableCases;

    case Invalid = 'invalid';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';

    public static function of(self|string $type): ?static
    {
        return $type instanceof self
            ? $type
            : static::tryFrom($type);
    }

    public function title(): string
    {
        return I18n::translate("hksagentur.webform.status.title.{$this->slug()}") ?? '';
    }

    public function message(): string
    {
        return I18n::translate("hksagentur.webform.status.message.{$this->slug()}") ?? '';
    }

    public function slug(): string
    {
        return $this->value;
    }

    public function role(): string
    {
        return match ($this) {
            static::Warning => 'region',
            default => 'alert',
        };
    }
}
