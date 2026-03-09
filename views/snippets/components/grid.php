<?php /** @var \Webform\Form\Components\Grid $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'id' => $component->getId(),
    'class' => 'grid',
    'data-gap' => $component->getGap(),
])) ?>>
    <?= $children ?? $slot ?>
</div>
