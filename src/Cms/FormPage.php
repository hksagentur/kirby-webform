<?php

namespace Webform\Cms;

use Kirby\Cms\Page;

class FormPage extends Page implements Contracts\HasAction, Contracts\HasForm
{
    use Concerns\HasAction;
    use Concerns\HasForm;
}
