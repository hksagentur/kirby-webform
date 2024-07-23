<?php

use Kirby\Cms\App;
use Uniform\Actions\DumpAction;
use Uniform\Actions\EmailAction;
use Uniform\Actions\LogAction;
use Uniform\Actions\WebhookAction;
use Uniform\Form;
use Uniform\Guards\CalcGuard;
use Uniform\Guards\HoneypotGuard;
use Uniform\Guards\HoneytimeGuard;

return function (FormPage $page, App $kirby): array {
    $plugin = $kirby->plugin('hksagentur/webform');

    $form = new Form(
        rules: $page->config()->fields(),
        sessionKey: $page->config()->id()
    );

    if ($page->isSubmitted()) {
        $guard = $plugin->option('guard');

        switch ($guard) {
            case 'captcha':
                $form->guard(CalcGuard::class, [
                    'field' => $plugin->option('captcha.field') ?? CalcGuard::FIELD_NAME,
                ]);
                break;
            case 'honeypot':
                $form->guard(HoneypotGuard::class, [
                    'field' => $plugin->option('honeypot.field') ?? HoneypotGuard::FIELD_NAME,
                ]);
                break;
            case 'honeytime':
                $form->guard(HoneytimeGuard::class, [
                    'key' => $plugin->option('honeytime.key'),
                    'field' => $plugin->option('honeytime.field') ?? HoneytimeGuard::FIELD_NAME,
                    'seconds' => $plugin->option('honeytime.time') ?? 60,
                ]);
                break;
        }

        $driver = $plugin->option('driver');

        switch ($driver) {
            case 'dump':
                $form->action(DumpAction::class);
                die();
                break;
            case 'log':
                $form->action(LogAction::class, [
                    'file' => $kirby->root('logs') . '/webform.log',
                ]);
                break;
            case 'email':
                $form->action(EmailAction::class, [
                    'preset' => $page->config()->emailPreset(),
                    'template' => $page->config()->emailTemplate() ?? 'webform/submission',
                    'subject' => $page->subject()->value(),
                    'from' => $page->recipient()->value(),
                    'to' => $page->sender()->value(),
                    'data' => $kirby->apply('webform.emailSubmission:before', [
                        'page' => $page,
                        'form' => $form,
                        'data' => ['submission' => $form->data()],
                    ], 'data'),
                ]);
                break;
            case 'webhook':
                $form->action(WebhookAction::class, [
                    'url' => $page->config()->webhookUrl(),
                    'json' => $page->config()->webhookType() === 'json',
                    'params' => $kirby->apply('webform.webhookSubmission:before', [
                        'page' => $page,
                        'form' => $form,
                        'params' => [],
                    ], 'params'),
                ]);
                break;
        }

        $form->done();
    }

    return [
        'form' => $form,
    ];
};
