<?php /** @var \Webform\Form\Components\RadioGroup $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php $value ??= $component->getValue() ?>
<?php $options ??= $component->getOptions() ?>

<?php $invalid ??= $errors->hasAny($name) ?>
<?php $messages ??= $errors->get($name) ?>

<fieldset <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'radio-group',
        ...$invalid ? ['radio-group--invalid'] : [],
    ],
])) ?>>
    <?php if ($label = $component->getLabel()) : ?>
        <legend class="radio-group__label">
            <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
        </legend>
    <?php endif ?>

    <?php if ($hint = $component->getHint()) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $hint : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($help = $component->getHelp()) : ?>
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
                    'required' => $component->isRequired(),
                    'disabled' => $component->isDisabled(),
                    'checked' => $optionValue == $value,
                    'aria-invalid' => $invalid ? 'true' : null,
                    'aria-describedby' => [
                        ...$invalid ? ["{$id}-error"] : [],
                        ...$hint ? ["{$id}-hint"] : [],
                    ],
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
