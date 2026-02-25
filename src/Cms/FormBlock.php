<?php

namespace Webform\Cms;

use Kirby\Cms\Block;

class FormBlock extends Block implements Contracts\HasActions, Contracts\HasForm
{
    use Concerns\HasActions;
    use Concerns\HasForm;
}
