<?php

use Kirby\Toolkit\A;
use Uniform\Form;

return [
    'webform.email:before' => function (Form $form, array $options = []) {
        return A::merge($options, [
            'data' => [
                'submission' => $form->data(),
            ],
        ]);
    },
];
