<div <?= attr([
    'id' => $id ?? null,
    'class' => [
        'field',
        ...A::wrap($class ?? []),
    ],
    ...$attrs ?? [],
]) ?>>
    <?= $children ?? $slot ?>
</div>
