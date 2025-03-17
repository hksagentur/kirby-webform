<?php $id ??= 'input-'.Str::uuid() ?>

<?php if ($label = $slots->label() ?: $slot) : ?>
    <?php snippet('webform/label', ['for' => $id], slots: true) ?>
        <?= $label ?>
    <?php endsnippet() ?>
<?php endif ?>

<?php if ($help = $slots->help()) : ?>
    <?php snippet('webform/help', ['id' => $id.'-help'], slots: true) ?>
        <?= $help ?>
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
    'aria-required' => !empty($required) ? 'true' : null,
    'aria-disabled' => !empty($disabled) ? 'true' : null,
    'aria-describedby' => array_filter([
        $form->error($name) ? $id.'-error' : null,
        $slots->help() ? $id.'-help' : null,
    ]),
]) ?>><?= $value ?? $form->old($name) ?></textarea>

<?php snippet('webform/inline-error', [
    'id' => $id.'-error',
    'for' => $name,
    'form' => $form,
]) ?>
