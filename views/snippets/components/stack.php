<?php /** @var \Webform\Form\Components\Stack $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'stack',
        $component->hasGap() ? "stack--{$component->getGap()}" : null,
    ],
])) ?>>
    <?= $component->getChildren() ?>
</div>
