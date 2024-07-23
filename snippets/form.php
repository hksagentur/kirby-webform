<?php $id ??= 'form-'.Str::uuid() ?>

<form <?= Html::attr([
    'id' => $id,
    'name' => $name ?? null,
    'class' => A::merge(A::wrap($class ?? []), ['form']),
    'action' => $action ?? $page->url(),
    'method' => $method ?? 'POST',
    'novalidate' => true,
]) ?>>
    <?= $slot ?>

    <?= csrf_field() ?>

    <?php if (option('hksagentur.webform.guard') === 'honeypot') : ?>
        <?= honeypot_field(
            name: option('hksagentur.webform.honeypot.field'),
        ) ?>
    <?php elseif (option('hksagentur.webform.guard') === 'honeytime') : ?>
        <?= honeytime_field(
            key: option('hksagentur.webform.honeytime.key'),
            name: option('hksagentur.webform.honeytime.field'),
        ) ?>
    <?php elseif (option('hksagentur.webform.guard') === 'captcha') : ?>
        <?= captcha_field(
            name: option('hksagentur.webform.captcha.field'),
        ) ?>
    <?php endif ?>
</form>
