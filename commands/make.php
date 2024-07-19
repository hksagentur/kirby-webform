<?php

use Kirby\CLI\CLI;
use Kirby\Data\PHP;
use Kirby\Filesystem\F;
use Kirby\Toolkit\Str;

return [
    'description' => 'Create the configuration file for a new webform',
    'args' => [
        'id' => [
            'description' => 'The unique ID of the webform',
            'required' => false,
        ],
        'name' => [
            'description' => 'The display name of the webform',
            'required' => false,
        ],
    ],
    'command' => static function (CLI $cli): int {
        $id = $cli->argOrPrompt('id', 'Please enter the unique ID of the webform');
        $name = $cli->argOrPrompt('name', 'Please enter a display name for the webform');

        $root = $cli->root('webforms') ?? $cli->root('site') . '/forms';
        $file = $root . '/' . $id . '.php';

        if (F::exists($file) && Str::lower($cli->prompt('Do you want to overwrite the existing file (Y/n)?')) !== 'y') {
            $cli->error('A configuration for a webform with the ID "' . $id . '" already exists.');
            return 1;
        }

        $configuration = [
            'id' => $id,
            'name' => $name,
            'fields' => [
                'name' => [
                    'rules' => ['required'],
                    'message' => ['Your name is required'],
                ],
                'email' => [
                    'rules' => ['required', 'email'],
                    'message' => ['Your email address is required', 'Please enter a valid email address'],
                ],
            ],
        ];

        if (! PHP::write($file, $configuration)) {
            $cli->error('Could not write to "' . $file . '"');
            return 1;
        }

        $cli->success('Created a new configuration file "' . $file . '"');

        $cli->bold('What to do next:');
        $cli->out('1. Add a snippet that renders your webform and embed it in your page');
        $cli->out('2. Add an email template for your form submissions');
        $cli->out('3. Add a preset to the email configuration for your webform');
        $cli->out('4. Adjust the validation rules in your form configuration');

        return 0;
    },
];
