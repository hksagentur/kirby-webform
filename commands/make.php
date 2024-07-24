<?php

use Kirby\CLI\CLI;
use Kirby\Data\PHP;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;

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
    'command' => static function (CLI $cli): void {
        $plugin = $cli->kirby()->plugin('hksagentur/webform');

        $formId = $cli->argOrPrompt('id', 'Please enter the unique ID of the webform:');
        $formName = $cli->argOrPrompt('name', 'Please enter a display name for the webform:');

        $projectRoot = $cli->root('base');
        $configRoot = $cli->root('webforms') ?? $cli->root('site') . '/forms';
        $snippetRoot = $cli->root('snippets') ?? $cli->root('site') . '/snippets';

        $configFile = $configRoot . '/' . $formId . '.php';
        $snippetFile = $snippetRoot . '/forms/' . $formId . '.php';

        // Copy the example configuration if it does not exist
        $config = A::merge(PHP::read($plugin->root() . '/stubs/config.php'), [
            'id' => $formId,
            'name' => $formName,
        ]);

        if (! F::exists($configFile) && PHP::write($configFile, $config)) {
            $cli->success('Created form config (.' . F::relativepath($configFile, $projectRoot) . ')');
        } else {
            $cli->error('Could not create form config (.' . F::relativepath($configFile, $projectRoot) . '). File already exists.');
        }

        // Copy the example snippet if it does not exist
        if (! F::exists($snippetFile) && F::copy($plugin->root() . '/stubs/snippet.php', $snippetFile)) {
            $cli->success('Created form snippet (.' . F::relativepath($snippetFile, $projectRoot) . ')');
        } else {
            $cli->error('Could not create form snippet (.' . F::relativepath($snippetFile, $projectRoot) . '). File already exists.');
        }
    },
];
