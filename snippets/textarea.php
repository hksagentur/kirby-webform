<?php $id ??= 'input-'.Str::uuid() ?>

<?php if ($label = $slots->label() ?: $slot) : ?>
    <?php snippet('webform/label', ['for' => $id], slots: true) ?>
        <?= $label ?>
    <?php endsnippet() ?>
<?php endif ?>

<textarea <?= Html::attr([
    'class' => A::merge(A::wrap($class ?? []), [
        'input',
        $form->error($name) ? 'input--invalid' : null,
    ]),
    'id' => $id,
    'name' => $name,
    'rows' => $rows ?? null,
    'cols' => $cols ?? null,
    'placeholder' => $placeholder ?? null,
    'required' => $required ?? null,
    'disabled' => $disabled ?? null,
    'readonly' => $readonly ?? null,
    'tabindex' => $tabindex ?? null,
    'aria-invalid' => $form->error($name) ? 'true' : null,
    'aria-describedby' => $form->error($name) ? $id.'-error' : null,
    'aria-required' => !empty($required) ? 'true' : null,
    'aria-disabled' => !empty($disabled) ? 'true' : null,
]) ?>><?= $value ?? $form->old($name) ?></textarea>

<?php snippet('webform/inline-error', ['id' => $id.'-error', 'for' => $name]) ?>
