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
</form>
