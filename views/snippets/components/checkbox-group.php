<?php /** @var \Webform\Form\Components\CheckboxGroup $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>
<?php $value ??= $component->getValue() ?>

<?php $label ??= $component->getLabel() ?>
<?php $hint ??= $component->getHint() ?>
<?php $help ??= $component->getHelp() ?>

<?php $required ??= $component->isRequired() ?>
<?php $disabled ??= $component->isDisabled() ?>

<?php $invalid ??= $component->isInvalid() ?>
<?php $messages ??= $component->getErrors() ?>

<fieldset <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'checkbox-group',
        ...$invalid ? ['checkbox-group--invalid'] : [],
    ],
    'aria-labelledby' => [
        ...$label ? ["{$id}-label"] : [],
    ],
    'aria-describedby' => [
        ...$invalid ? ["{$id}-error"] : [],
        ...$hint ? ["{$id}-hint"] : [],
    ],
    'disabled' => $disabled,
    'role' => 'group',
])) ?>>
    <?php if ($label) : ?>
        <legend <?= attr([
            'id' => "{$id}-label",
            'class' => 'checkbox-group__label',
        ]) ?>>
            <?= $component->isHtmlAllowed() ? kti($label) : esc($label) ?>
        </legend>
    <?php endif ?>

    <?php if ($hint) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($hint) : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($help) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($help) : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <div class="checkbox-group__options">
        <?php foreach ($component->getOptions()->select($value) as $option) : ?>
            <label class="checkbox-group__option">
                <input <?= attr([
                    'class' => 'checkbox-group__option-input',
                    'type' => 'checkbox',
                    'name' => $name . '[]',
                    'value' => $option->value(),
                    'checked' => $option->isSelected(),
                    'required' => $required,
                    'aria-invalid' => $invalid ? 'true' : null,
                    'aria-labelledby' => $label ? ["{$id}-label"] : [],
                ]) ?>>
                <span class="checkbox-group__option-label">
                    <?= $component->isHtmlAllowed() ? kti($option->label()) : esc($option->label())  ?>
                </span>
            </label>
        <?php endforeach ?>
    </div>

    <?php snippet('webform/inline-error', [
        'id' => "{$id}-error",
        'messages' => $messages,
    ]) ?>
</fieldset>
