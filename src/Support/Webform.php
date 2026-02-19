<?php

namespace Webform\Support;

use Kirby\Toolkit\Facade;
use Webform\Form\Form;
use Webform\Form\Manager;

/**
 * @method static ?Form form(string $path)
 *
 * @see Manager
 */
class Webform extends Facade
{
    public static function instance(): Manager
    {
        return Manager::instance();
    }
}
