<label <?= attr([
    'for' => $for ?? null,
    'class' => [
        'label',
        ...A::wrap($class ?? []),
    ],
    ...$attrs ?? [],
]) ?>>
    <?= $slot ?>
</label>
