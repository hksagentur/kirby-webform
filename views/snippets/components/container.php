<?php /** @var \Webform\Form\Components\Container $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'id' => $component->getId(),
    'class' => 'container',
])) ?>>
    <?= $children ?? $slot ?>
</div>
