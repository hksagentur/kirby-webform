<?php

use Kirby\Cms\App;
use Uniform\Actions\EmailAction;
use Uniform\Actions\WebhookAction;
use Uniform\Form;
use Uniform\Guards\CalcGuard;
use Uniform\Guards\HoneypotGuard;
use Uniform\Guards\HoneytimeGuard;
use Webform\Cms\FormPage;
use Webform\Form\Actions\DatabaseAction;

return function (FormPage $page, App $kirby): array {
    $plugin = $kirby->plugin('hksagentur/webform');

    $config = $page->formConfig();

    $form = new Form(
        rules: $config->fields(),
        sessionKey: $config->id(),
    );

    if ($page->isSubmitted()) {
        switch ($plugin->option('guard')) {
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

        switch ($page->formHandler()) {
            case 'database':
                $form->action(DatabaseAction::class, [
                    'table' => $page->databaseTable()->or($config->databaseTable())->value(),
                ]);
                break;
            case 'email':
                $form->action(EmailAction::class, [
                    'preset' => $config->emailPreset(),
                    'template' => $config->emailTemplate() ?? 'webform/submission',
                    'subject' => $page->emailSubject()->or($config->emailSubject())->value(),
                    'from' => $page->emailRecipient()->or($config->emailRecipient())->value(),
                    'to' => $page->emailSender()->or($config->emailSender())->value(),
                    'data' => $kirby->apply('webform.emailSubmission:before', [
                        'page' => $page,
                        'form' => $form,
                        'config' => $config,
                        'data' => ['submission' => $form->data()],
                    ], 'data'),
                ]);
                break;
            case 'webhook':
                $form->action(WebhookAction::class, [
                    'url' => $page->webhookUrl()->or($config->webhookUrl())->value(),
                    'json' => $page->webhookFormat()->or($config->webhookFormat())->value() === 'json',
                    'params' => $kirby->apply('webform.webhookSubmission:before', [
                        'page' => $page,
                        'form' => $form,
                        'config' => $config,
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
