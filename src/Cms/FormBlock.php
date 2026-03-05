<?php

namespace Webform\Cms;

use Kirby\Cms\Block;

class FormBlock extends Block implements Contracts\HasAction, Contracts\HasForm
{
    use Concerns\HasAction;
    use Concerns\HasForm;
}
