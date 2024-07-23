<?php $id ??= 'radio-'.Str::uuid() ?>
<?php $checked ??= $value === $form->old($name, new stdClass()) ?>

<label <?= Html::attr([
    'class' => A::merge(A::wrap($class ?? []), ['radio']),
]) ?>>
    <input <?= Html::attr([
        'type' => 'radio',
        'class' => 'radio__input',
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'required' => $required ?? null,
        'disabled' => $disabled ?? null,
        'tabindex' => $tabindex ?? null,
        'checked' => $checked ? 'checked' : null,
        'aria-required' => !empty($required) ? 'true' : null,
        'aria-disabled' => !empty($disabled) ? 'true' : null,
    ]) ?>>

    <span class="checkbox__label">
        <?= $slot ?>
    </span>
</label>
