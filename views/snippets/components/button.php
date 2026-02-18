<?php /** @var \Webform\Form\Components\Button $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>

<button <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'button',
    'type' => $component->getType(),
    'id' => $id,
    'name' => $name,
    'value' => $component->getValue() ?? $component->getDefaultValue(),
    'disabled' => $component->isDisabled(),
])) ?>>
    <?php if ($label = $component->getLabel()) : ?>
        <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
    <?php endif ?>
</button>
