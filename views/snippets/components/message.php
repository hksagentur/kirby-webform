<?php $type ??= 'success' ?>

<?php $id ??= null ?>
<?php $title ??= null ?>

<?php $autofocus ??= false ?>

<<?= $as ?? 'div' ?> <?= attr([
    'id' => $id,
    'class' => [
        'message',
        "message--{$type}",
        ...A::wrap($class ?? []),
    ],
    'role' => $role ?? null,
    'aria-live' => $live ?? null,
    'aria-labelledby' => $id && $title ? "{$id}-title" : null,
    'autofocus' => $autofocus,
    'tabindex' => $autofocus ? '-1' : null,
    ...$attrs ?? [],
]) ?>>
    <?php snippet('webform/icon', [
        'name' => in_array($type, ['success', 'warning', 'error']) ? $type : 'info',
        'class' => 'message__icon',
    ]) ?>

    <?php if ($title) : ?>
        <<?= $level ?? 'h2' ?> <?= attr([
            'id' => $id ? "{$id}-title" : null,
            'class' => 'message__title',
        ]) ?>>
            <?= $title ?>
        </<?= $level ?? 'h2' ?>>
    <?php endif ?>

    <?= $message ?? $slot ?>
</<?= $as ?? 'div' ?>>
