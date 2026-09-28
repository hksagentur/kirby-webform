<label <?= attr([
    'for' => $for ?? null,
    'class' => [
        'label',
        ...A::wrap($class ?? []),
    ],
    ...$attrs ?? [],
]) ?>>
    <?= $slot ?>

    <?php snippet('webform/label-marker', [
        'marker' => $marker ?? null,
    ]) ?>
</label>
