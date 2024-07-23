<?php

use Kirby\Cms\App;
use Webform\Form\FormConfigCollection;

return function (App $kirby): FormConfigCollection {
    return FormConfigCollection::fromDirectory(
        $kirby->root('webforms') ?? $kirby->root('site') . '/forms'
    );
};
