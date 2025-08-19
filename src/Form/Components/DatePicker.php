<?php

namespace Webform\Form\Components;

class DatePicker extends DateTimePicker
{
    public function hasTime(): bool
    {
        return false;
    }
}
