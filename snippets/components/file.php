<?php /** @var \Webform\Form\Components\FileUpload $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php $types ??= $component->getAcceptedFileTypes() ?>

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
        'type' => 'file',
        'class' => [
            'file-upload',
            ...$invalid ? ['file-upload--invalid'] : ['file-upload--valid'],
        ],
        'id' => $id,
        'name' => $component->isMultiple() ? $name.'[]' : $name,
        'required' => $component->isRequired(),
        'disabled' => $component->isDisabled(),
        'multiple' => $component->isMultiple(),
        'accept' => $types ? implode(',', $types) : null,
        'aria-invalid' => $invalid ? 'true' : null,
        'aria-describedby' => [
            ...$invalid ? ["{$id}-error"] : [],
            ...$hint ? ["{$id}-hint"] : [],
        ],
    ])) ?>>

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
