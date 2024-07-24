<?php $id ??= 'status-message-'.Str::uuid() ?>

<?php $type ??= 'info' ?>

<?php $role ??= match ($type) {
    'success' => 'status',
    'error' => 'alert',
    default => null,
} ?>

<div <?= Html::attr([
    'id' => $id,
    'class' => A::merge(A::wrap($class ?? []), [
        'status-message',
        'status-message--'.$type,
    ]),
    'role' => $role,
    'aria-labelledby' => array_filter([
        $slots->title() ? $id.'-label' : null,
    ]),
]) ?>>
    <?php if ($title = $slots->title()) : ?>
        <h2 id="<?= $id.'-label' ?>" class="status-message__title">
            <?= $title ?>
        </h2>
    <?php endif ?>

    <?= $slot ?>
</div>
