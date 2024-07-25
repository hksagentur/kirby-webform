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

    <?php if (in_array(Uniform\Guards\HoneypotGuard::class, $page->formConfig()->guards())) : ?>
        <?= honeypot_field(
            name: $page->formConfig()->get('honeypot.field'),
        ) ?>
    <?php elseif (in_array(Uniform\Guards\HoneytimeGuard::class, $page->formConfig()->guards())) : ?>
        <?= honeytime_field(
            key: $page->formConfig()->get('honeytime.key'),
            name: $page->formConfig()->get('honeytime.field'),
        ) ?>
    <?php elseif (in_array(Uniform\Guards\CalcGuard::class, $page->formConfig()->guards())) : ?>
        <?= captcha_field(
            name: $page->formConfig()->get('calc.field'),
        ) ?>
    <?php endif ?>
</form>
