<label <?= attr([
    'for' => $for ?? null,
    'class' => 'label',
]) ?>>
    <?= $slot ?>
</label>
