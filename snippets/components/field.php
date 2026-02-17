<div <?= attr([
    'id' => $id ?? null,
    'class' => 'field',
    ...$attrs ?? [],
]) ?>>
    <?= $slot ?>
</div>
