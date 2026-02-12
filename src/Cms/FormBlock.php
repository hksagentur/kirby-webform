<?php

namespace Webform\Cms;

use Kirby\Cms\Block;
use Webform\Cms\Contracts\Factory;

class FormBlock extends Block implements Factory
{
    use Concerns\HasAction;
    use Concerns\HasForm;
}
