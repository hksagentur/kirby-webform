<?php /** @var \Webform\Form\Components\Honeypot $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<?php snippet('webform/field', [
    'class' => 'trojan-horse',
], slots: true) ?>
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
        'type' => 'text',
        'class' => 'input',
        'id' => $id,
        'name' => $name,
        'value' => '',
        'placeholder' => $component->getPlaceholder(),
        'autocomplete' => $component->getAutocomplete() ?? 'nope',
    ])) ?>>

    <?php if ($help = $component->getHelp()) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $help : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>
<?php endsnippet() ?>
