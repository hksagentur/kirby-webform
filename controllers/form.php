<?php

use Kirby\Cms\App;
use Uniform\Form;
use Webform\Cms\FormPage;
use Webform\Toolkit\Str;

return function (FormPage $page, App $kirby): array {
    $form = new Form(
        rules: $page->formConfig()->fields(),
        sessionKey: $page->formConfig()->id(),
    );

    if ($page->isSubmitted()) {
        foreach ($page->formConfig()->guards() as $alias => $className) {
            if (is_numeric($alias)) {
                $alias = Str::snake(Str::beforeEnd(Str::classBasename($className), 'Guard'));
            }

            $form->guard(
                guard: $className,
                options: $kirby->apply("webform.{$alias}:before", [
                    'page' => $page,
                    'form' => $form,
                    'options' => $page->formConfig()->get($alias, []),
                ], 'options'),
            );
        }

        foreach ($page->formConfig()->actions() as $alias => $className) {
            if (is_numeric($alias)) {
                $alias = Str::snake(Str::beforeEnd(Str::classBasename($className), 'Action'));
            }

            $form->action(
                action: $className,
                options: $kirby->apply("webform.{$alias}:before", [
                    'page' => $page,
                    'form' => $form,
                    'options' => $page->formConfig()->get($alias, []),
                ], 'options'),
            );
        }

        $form->done();
    }

    return [
        'form' => $form,
    ];
};
