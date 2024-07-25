<?php $id ??= 'radio-group-'.Str::uuid() ?>
<?php $checked ??= $form->old($name, $default ?? '') ?>

<fieldset <?= Html::attr([
    'id' => $id,
    'class' => A::merge(A::wrap($class ?? []), [
        'radio-group',
        $form->error($name) ? 'radio-group--invalid' : null,
    ]),
    'role' => 'radiogroup',
    'disabled' => $disabled ?? null,
    'aria-disabled' => !empty($disabled) ? 'true' : null,
    'aria-required' => !empty($required) ? 'true' : null,
    'aria-invalid' => $form->error($name) ? 'true' : null,
    'aria-labelledby' => array_filter([
        $slots->label() ? $id.'-label' : null,
    ]),
    'aria-describedby' => array_filter([
        $form->error($name) ? $id.'-error' : null,
        $slots->help() ? $id.'-help' : null,
    ]),
]) ?>>
    <?php if ($label = $slots->label() ?: $slot) : ?>
        <legend id="<?= $id.'-label' ?>" class="radio-group__label">
            <span><?= $label ?></span>
        </legend>
    <?php endif ?>

    <?php if ($help = $slots->help()) : ?>
        <div id="<?= $id.'-help' ?>" class="radio-group__help">
            <?= $help ?>
        </div>
    <?php endif ?>

    <?php if (empty($options)) : ?>
        <?= $slot ?>
    <?php else : ?>
        <?php $index = 0 ?>
        <?php foreach ($options as $value => $label) : ?>
            <?php snippet('webform/radio', [
                'id' => $id.'-'.(++$index),
                'name' => $name,
                'value' => $value,
                'checked' => $checked === $value,
                'context' => 'radio-group',
            ], slots: true) ?>
                <?= $label ?>
            <?php endsnippet() ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php snippet('webform/inline-error', [
        'for' => $name,
        'id' => $id.'-error',
        'class' => 'radio-group__error',
    ]) ?>
</fieldset>
