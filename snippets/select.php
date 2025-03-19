<?php $id ??= 'select-'.Str::uuid() ?>
<?php $selected ??= A::wrap($form->old($name, A::wrap($default ?? ''))) ?>

<?php if ($label = $slots->label() ?? $slot) : ?>
    <?php snippet('webform/label', ['for' => $id], slots: true) ?>
        <?= $label ?>
    <?php endsnippet() ?>
<?php endif ?>

<?php if ($help = $slots->help()) : ?>
    <?php snippet('webform/help', ['id' => $id.'-help'], slots: true) ?>
        <?= $help ?>
    <?php endsnippet() ?>
<?php endif ?>

<div <?= Html::attr([
    'class' => A::merge(A::wrap($class ?? []), [
        'select',
        $form->error($name) ? 'select--invalid' : null,
    ]),
]) ?>>
    <select <?= Html::attr([
        'class' => ['select__input'],
        'id' => $id,
        'name' => $name,
        'required' => $required ?? null,
        'disabled' => $disabled ?? null,
        'multiple' => $multiple ?? null,
        'readonly' => $readonly ?? null,
        'tabindex' => $tabindex ?? null,
        'aria-invalid' => $form->error($name) ? 'true' : null,
        'aria-required' => !empty($required) ? 'true' : null,
        'aria-disabled' => !empty($disabled) ? 'true' : null,
        'aria-describedby' => array_filter([
            $form->error($name) ? $id.'-error' : null,
            $slots->help() ? $id.'-help' : null,
        ]),
    ]) ?>>
        <?php if (empty($required)) : ?>
            <option value="" <?= Html::attr([
                'selected' => A::has($selected, ''),
            ]) ?>>
                <?= $none ?? t('hksagentur.webform.select.none', '- None -') ?>
            </option>
        <?php elseif (empty($multiple)) : ?>
            <option value="" <?= Html::attr([
                'disabled' => true,
                'selected' => A::has($selected, ''),
            ]) ?>>
                <?= $empty ?? t('hksagentur.webform.select.empty', '- Select -') ?>
            </option>
        <?php endif ?>

        <?php if (empty($options)) : ?>
            <?= $slot ?>
        <?php else : ?>
            <?php foreach ($options as $value => $label) : ?>
                <option <?= Html::attr([
                    'value' => !array_is_list($options) ? $value : null,
                    'selected' => A::has($selected, $value) ? 'selected' : null,
                ]) ?>>
                    <?= $label ?>
                </option>
            <?php endforeach ?>
        <?php endif ?>
    </select>

    <?php if (empty($multiple)) : ?>
        <svg class="select__caret" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M23.468,2.984a2,2,0,0,0-1.742-1.018H2.274A2,2,0,0,0,.563,5L10.289,21.07a2,2,0,0,0,3.422,0L23.437,5A2,2,0,0,0,23.468,2.984Z"/>
        </svg>
    <?php endif ?>
</div>

<?php snippet('webform/inline-error', [
    'id' => $id.'-error',
    'for' => $name,
    'form' => $form,
]) ?>
