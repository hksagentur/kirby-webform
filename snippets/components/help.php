<div <?= attr([
    'id' => $id ?? null,
    'class' => [
        'help',
        ...A::wrap($class ?? []),
    ],
    ...$attrs ?? [],
]) ?>>
    <?= $slot ?>
</div>
