<?php /** @var \Webform\Form\Components\Stack $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'id' => $component->getId(),
    'class' => 'stack',
    'data-gap' => $component->getGap(),
])) ?>>
    <?= $children ?? $slot ?>
</div>
