<?php

use Kirby\Cms\App;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;
use Uniform\Form;

return function (App $kirby, string $id): Form {
    $root = $kirby->root('webforms') ?? $kirby->root('site') . '/forms';
    $file = $root . '/' . $id . '.php';

    $options = F::load($file, fallback: [], allowOutput: false) ?: [];

    return new Form(
        rules: A::get($options, 'fields', []),
        sessionKey: A::get($options, 'sessionKey', $id),
    );
};
