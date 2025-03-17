<?php $id ??= 'checkbox-'.Str::uuid() ?>

<?php $multiple ??= in_array($context ?? null, ['checkbox-group']) ?>
<?php $checked ??= $value === $form->old($name, new stdClass()) ?>

<label <?= Html::attr([
    'class' => A::merge(A::wrap($class ?? []), ['checkbox']),
]) ?>>
    <input <?= Html::attr([
        'type' => 'checkbox',
        'class' => 'checkbox__input',
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'required' => $required ?? null,
        'readonly' => $readonly ?? null,
        'disabled' => $disabled ?? null,
        'tabindex' => $tabindex ?? null,
        'checked' => $checked ? 'checked' : null,
        'aria-invalid' => !$multiple && $form->error($name) ? 'true' : null,
        'aria-required' => !empty($required) ? 'true' : null,
        'aria-disabled' => !empty($disabled) ? 'true' : null,
        'aria-describedby' => array_filter([
            !$multiple && $form->error($name) ? $id.'-error' : null,
            $slots->help() ? $id.'-help' : null,
        ]),
    ]) ?>>

    <span class="checkbox__label">
        <?= $slots->label() ?: $slot ?>
    </span>
</label>

<?php if ($help = $slots->help()) : ?>
    <?php snippet('webform/help', ['id' => $id.'-help', 'for' => $id], slots: true) ?>
        <?= $help ?>
    <?php endsnippet() ?>
<?php endif ?>

<?php if (!$multiple) : ?>
    <?php snippet('webform/inline-error', [
        'id' => $id.'-error',
        'for' => $name,
        'form' => $form,
    ]) ?>
<?php endif ?>
