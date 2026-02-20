<?php /** @var \Webform\Form\Components\RadioGroup $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>
<?php $value ??= $component->getValue() ?>

<?php $label ??= $component->getLabel() ?>
<?php $hint ??= $component->getHint() ?>
<?php $help ??= $component->getHelp() ?>

<?php $required ??= $component->isRequired() ?>
<?php $disabled ??= $component->isDisabled() ?>
<?php $readOnly ??= $component->isReadOnly() ?>

<?php $invalid ??= $component->isInvalid() ?>
<?php $messages ??= $component->getErrors() ?>

<fieldset <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'radio-group',
        ...$invalid ? ['radio-group--invalid'] : [],
    ],
    'aria-labelledby' => [
        ...$label ? ["{$id}-label"] : [],
    ],
    'aria-describedby' => [
        ...$invalid ? ["{$id}-error"] : [],
        ...$hint ? ["{$id}-hint"] : [],
    ],
    'aria-required' => $required ? 'true' : null,
    'aria-readonly' => $readOnly ? 'true' : null,
    'aria-invalid' => $invalid ? 'true' : null,
    'disabled' => $disabled,
    'role' => 'radiogroup',
])) ?>>
    <?php if ($label) : ?>
        <legend <?= attr([
            'id' => "{$id}-label",
            'class' => 'radio-group__label',
        ]) ?>>
            <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
        </legend>
    <?php endif ?>

    <?php if ($hint) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $hint : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($help) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $help : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <div class="radio-group__options">
        <?php foreach ($component->getOptions() as $optionValue => $optionLabel) : ?>
            <label class="radio-group__option">
                <input <?= attr([
                    'class' => 'radio-group__option-input',
                    'type' => 'radio',
                    'name' => $name,
                    'value' => $optionValue,
                    'required' => $required,
                    'checked' => $optionValue == $value,
                    'aria-labelledby' => $label ? ["{$id}-label"] : [],
                ]) ?>>
                <span class="radio-group__option-label">
                    <?= $component->isHtmlAllowed() ? $optionLabel : esc($optionLabel)  ?>
                </span>
            </label>
        <?php endforeach ?>
    </div>

    <?php snippet('webform/inline-error', [
        'id' => "{$id}-error",
        'messages' => $messages,
    ]) ?>
</fieldset>
