<?php /** @var \Webform\Form\Components\ButtonGroup $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'button-group',
        'cluster',
    ],
])) ?>>
    <?= $children ?? $slot ?>
</div>
