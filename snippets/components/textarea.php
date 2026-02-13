<?php /** @var \Webform\Form\Components\Textarea $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php $value ??= $component->getOldValue() ?? $component->getValue() ?? $component->getDefaultValue() ?>

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

    <textarea <?= attr(A::merge($component->getExtraAttributes(), [
        'class' => [
            'input',
            ...$invalid ? ['input--invalid'] : [],
        ],
        'id' => $id,
        'name' => $name,
        'rows' => $component->getRows(),
        'cols' => $component->getCols(),
        'placeholder' => $component->getPlaceholder(),
        'required' => $component->isRequired(),
        'disabled' => $component->isDisabled(),
        'readonly' => $component->isReadonly(),
        'aria-invalid' => $invalid ? 'true' : null,
        'aria-describedby' => [
            ...$invalid ? ["{$id}-error"] : [],
            ...$hint ? ["{$id}-hint"] : [],
        ],
    ])) ?>><?= $component->isHtmlAllowed() ? $value : esc($value ?: '') ?></textarea>

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
