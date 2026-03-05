<?php /** @var ?\Webform\Toolkit\Options $options */ ?>

<?php if ($options?->isNotEmpty()) : ?>
    <datalist <?= attr(['id' => $id ?? null, ...$attrs ?? []]) ?>>
        <?php foreach ($options as $option) : ?>
            <option <?= attr(['value' => $option->value()]) ?>>
                <?= esc($option->label()) ?>
            </option>
        <?php endforeach ?>
    </datalist>
<?php endif ?>
