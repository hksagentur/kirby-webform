<?php $id ??= 'checkbox-group-'.Str::uuid() ?>
<?php $checked ??= A::wrap($form->old($name, A::wrap($default ?? []))) ?>

<fieldset <?= Html::attr([
    'id' => $id,
    'class' => A::merge(A::wrap($class ?? []), [
        'checkbox-group',
        $form->error($name) ? 'checkbox-group--invalid' : null,
    ]),
    'role' => 'group',
    'disabled' => $disabled ?? null,
    'aria-disabled' => !empty($disabled) ? 'true' : null,
    'aria-labelledby' => array_filter([
        $slots->label() ? $id.'-label' : null,
    ]),
    'aria-describedby' => array_filter([
        $form->error($name) ? $id.'-error' : null,
        $slots->help() ? $id.'-help' : null,
    ]),
]) ?>>
    <?php if ($label = $slots->label() ?: $slot) : ?>
        <legend id="<?= $id.'-label' ?>" class="checkbox-group__label">
            <span><?= $label ?></span>
        </legend>
    <?php endif ?>

    <?php if ($help = $slots->help()) : ?>
        <div id="<?= $id.'-help' ?>" class="checkbox-group__help">
            <?= $help ?>
        </div>
    <?php endif ?>

    <?php if (empty($options)) : ?>
        <?= $slot ?>
    <?php else : ?>
        <?php $index = 0 ?>
        <?php foreach ($options as $value => $label) : ?>
            <?php snippet('webform/checkbox', [
                'id' => $id.'-'.(++$index),
                'name' => $name.'[]',
                'value' => $value,
                'disabled' => $disabled ?? null,
                'checked' => A::has($checked, $value),
            ], slots: true) ?>
                <?= $label ?>
            <?php endsnippet() ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php snippet('webform/inline-error', [
        'for' => $name,
        'id' => $id.'-error',
        'class' => 'checkbox-group__error',
    ]) ?>
</fieldset>
