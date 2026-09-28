<?php /** @var \Webform\Form\Components\Textarea $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>
<?php $value ??= $component->getValue() ?>

<?php $label ??= $component->getLabel() ?>
<?php $hint ??= $component->getHint() ?>
<?php $help ??= $component->getHelp() ?>

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
        'spellcheck' => $component->getSpellcheck(),
        'required' => $component->isRequired(),
        'disabled' => $component->isDisabled(),
        'readonly' => $component->isReadonly(),
        'aria-invalid' => $invalid ? 'true' : null,
        'aria-describedby' => [
            ...$invalid ? ["{$id}-error"] : [],
            ...$hint ? ["{$id}-hint"] : [],
            ...$help ? ["{$id}-help"] : [],
        ],
    ])) ?>><?= esc($value ?: '') ?></textarea>

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
