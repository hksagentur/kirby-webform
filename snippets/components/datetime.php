<?php /** @var \Webform\Form\Components\DateTimePicker $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php $value ??= $component->getOldValue() ?? $component->getValue() ?? $component->getDefaultValue() ?>
<?php $options ??= $component->getDatalistOptions() ?>

<?php $invalid ??= $errors->hasAny($name) ?>
<?php $messages ??= $errors->get($name) ?>

<?php snippet('webform/field', slots: true) ?>
    <?php if ($label = $component->getLabel()) : ?>
        <?php snippet('webform/label', ['for' => $id], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($hint = $component->getHint()) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $hint : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <input <?= attr(A::merge($component->getExtraAttributes(), [
        'type' => $component->getType(),
        'class' => [
            'input',
            ...$invalid ? ['input--invalid'] : ['input--valid'],
        ],
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'required' => $component->isRequired(),
        'disabled' => $component->isDisabled(),
        'readonly' => $component->isReadonly(),
        'min' => $component->getMinDate(),
        'max' => $component->getMaxDate(),
        'step' => $component->getStep(),
        'placeholder' => $component->getPlaceholder(),
        'autocomplete' => $component->getAutocomplete(),
        'list' => $options ? "{$id}-datalist" : null,
        'aria-invalid' => $invalid ? 'true' : null,
        'aria-describedby' => [
            ...$invalid ? ["{$id}-error"] : [],
            ...$hint ? ["{$id}-hint"] : [],
        ],
    ])) ?>>

    <?php snippet('webform/datalist', [
        'id' => "{$id}-datalist",
        'options' => $options,
    ]) ?>

    <?php snippet('webform/inline-error', [
        'id' => "{$id}-error",
        'messages' => $messages,
    ]) ?>

    <?php if ($help = $component->getHelp()) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $help : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>
<?php endsnippet() ?>
