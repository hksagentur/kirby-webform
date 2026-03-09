<?php /** @var \Webform\Form\Components\ButtonGroup $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'id' => $component->getId(),
    'class' => [
        'button-group',
        'cluster',
    ],
])) ?>>
    <?= $children ?? $slot ?>
</div>
