<datalist <?= Html::attr([
    'id' => $id ?? 'datalist-'.Str::uuid(),
]) ?>>
    <?php if (empty($options)) : ?>
        <?= $slot ?>
    <?php else : ?>
        <?php foreach ($options as $value => $label) : ?>
            <option <?= Html::attr([
                'value' => !array_is_list($options) ? $value : null,
            ]) ?>>
                <?= $label ?>
            </option>
        <?php endforeach ?>
    <?php endif ?>
</datalist>
