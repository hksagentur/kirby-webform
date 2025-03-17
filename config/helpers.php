<?php

use Uniform\Form;
use Uniform\Guards\CalcGuard;
use Uniform\Guards\HoneypotGuard;
use Uniform\Guards\HoneytimeGuard;
use Webform\Form\Manager as FormManager;

if (! function_exists('form')) {
    /**
     * Load a webform from a given configuration path.
     */
    function form(?string $configPath = null): Form|FormManager
    {
        if (! is_null($configPath)) {
            return FormManager::instance()->form($configPath);
        }

        return FormManager::instance();
    }
}

if (! function_exists('spam_protection_field')) {
    /**
     * Render the spam protection field for a given form.
     */
    function spam_protection_field(string $configPath): ?string
    {
        $config = form()->config($configPath);

        return match (true) {
            in_array(HoneypotGuard::class, $config->guards()) => honeypot_field(
                name: $config->get('honeypot.field')
            ),
            in_array(HoneytimeGuard::class, $config->guards()) => honeytime_field(
                key: $config->get('honeytime.key'),
                name: $config->get('honeytime.field'),
            ),
            in_array(CalcGuard::class, $config->guards()) => captcha_field(
                name: $config->get('calc.field'),
            ),
            default => null
        };
    }
}
