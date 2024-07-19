<?php

use Kirby\Cms\App;
use Kirby\Cms\Structure;
use Kirby\Filesystem\F;

return function (App $kirby): Structure {
    $forms = [];

    foreach (glob(($kirby->root('webforms') ?? $kirby->root('site') . '/forms') . '/*.php') as $file) {
        $forms[F::name($file)] = F::load($file, allowOutput: false);
    }

    return new Structure($forms);
};
