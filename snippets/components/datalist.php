<?php if (! empty($options)) : ?>
    <datalist <?= attr(['id' => $id ?? null]) ?>>
        <?php foreach ($options as $key => $value) : ?>
            <option <?= attr([
                'value' => !array_is_list($options) ? $key : null,
            ]) ?>>
                <?= $value ?>
            </option>
        <?php endforeach ?>
    </datalist>
<?php endif ?>
