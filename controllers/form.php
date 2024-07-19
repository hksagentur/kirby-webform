<?php

use Kirby\Cms\App;
use Uniform\Actions\EmailAction;
use Uniform\Actions\LogAction;
use Uniform\Guards\CalcGuard;
use Uniform\Guards\HoneypotGuard;
use Uniform\Guards\HoneytimeGuard;

return function (FormPage $page, App $kirby): array {
    $form = $page->toForm();

    if ($page->isSubmitted()) {
        $guard = $kirby->option('hksagentur.webform.guard.default', 'honeypot');

        switch ($guard) {
            case 'calc':
                $form->guard(CalcGuard::class, [
                    'field' => $kirby->option('hksagentur.webform.guard.calc.field', CalcGuard::FIELD_NAME),
                ]);
                break;
            case 'honeypot':
                $form->guard(HoneypotGuard::class, [
                    'field' => $kirby->option('hksagentur.webform.guard.honeypot.field', HoneypotGuard::FIELD_NAME),
                ]);
                break;
            case 'honeytime':
                $form->guard(HoneytimeGuard::class, [
                    'field' => $kirby->option('hksagentur.webform.guard.honeytime.field', HoneytimeGuard::FIELD_NAME),
                    'key' => $kirby->option('hksagentur.webform.guard.honeytime.key'),
                    'seconds' => $kirby->option('hksagentur.webform.guard.honeytime.time', 10),
                ]);
                break;
        }

        $log = $kirby->option('hksagentur.webform.logging.default', 'null');

        switch ($log) {
            case 'file':
                $form->action(LogAction::class, [
                    'file' => $kirby->roots()->logs() . '/' . $kirby->option('hksagentur.webform.logging.file.path', 'webform.log'),
                ]);
                break;
        }

        $subject = $page->subject()->value();
        $to = $page->recipient()->value();
        $from = $page->sender()->value();

        if ($to || $from) {
            $form->action(EmailAction::class, [
                'preset' => $page->formId(),
                'subject' => $subject,
                'to' => $to,
                'from' => $from,
            ]);
        }

        $form->done();
    }

    return [
        'form' => $form,
    ];
};
