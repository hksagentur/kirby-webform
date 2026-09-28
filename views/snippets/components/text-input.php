<?php /** @var \Webform\Form\Components\TextInput $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php $label ??= $component->getLabel() ?>
<?php $hint ??= $component->getHint() ?>
<?php $help ??= $component->getHelp() ?>

<?php $options ??= $component->getDatalistOptions() ?>

<?php $invalid ??= $component->isInvalid() ?>
<?php $messages ??= $component->getErrors() ?>

<?php snippet('webform/field', slots: true) ?>
    <?php if ($label) : ?>
        <?php snippet('webform/label', [
            'for' => $id,
            'marker' => $component->getLabelMarker(),
        ], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($label) : esc($label) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($hint) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($hint) : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <input <?= attr(A::merge($component->getExtraAttributes(), [
        'type' => $component->getType(),
        'class' => [
            'input',
            ...$invalid ? ['input--invalid'] : [],
        ],
        'id' => $id,
        'name' => $name,
        'value' => $component->getValue(),
        'required' => $component->isRequired(),
        'disabled' => $component->isDisabled(),
        'readonly' => $component->isReadonly(),
        'min' => $component->getMinValue(),
        'max' => $component->getMaxValue(),
        'step' => $component->getStep(),
        'placeholder' => $component->getPlaceholder(),
        'autocomplete' => $component->getAutocomplete(),
        'inputmode' => $component->getInputMode(),
        'spellcheck' => $component->getSpellcheck(),
        'autocapitalize' => $component->getAutocapitalize(),
        'list' => $options->isNotEmpty() ? "{$id}-datalist" : null,
        'aria-invalid' => $invalid ? 'true' : null,
        'aria-describedby' => [
            ...$invalid ? ["{$id}-error"] : [],
            ...$hint ? ["{$id}-hint"] : [],
            ...$help ? ["{$id}-help"] : [],
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

    <?php if ($help) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($help) : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>
<?php endsnippet() ?>
