<?php /** @var \Webform\Form\Components\Button $component */ ?>

<button <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'button',
    'type' => $component->getType(),
    'id' => $component->getId(),
    'disabled' => $component->isDisabled(),
    ...$component->hasAction() ? [
        'name' => '_webform_operation',
        'value' => $component->getName(),
    ] : [
        'name' => $component->getName(),
        'value' => $component->getValue(),
    ],
])) ?>>
    <?php if ($label = $component->getLabel()) : ?>
        <?= $component->isHtmlAllowed() ? kti($label) : esc($label) ?>
    <?php endif ?>
</button>
