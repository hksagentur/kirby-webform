<div <?= attr([
    'id' => $id ?? null,
    'class' => [
        'hint',
        ...A::wrap($class ?? []),
    ],
    ...$attrs ?? [],
]) ?>>
    <?= $slot ?>
</div>
