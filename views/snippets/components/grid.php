<?php /** @var \Webform\Form\Components\Grid $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'grid',
    'data-gap' => $component->getGap(),
])) ?>>
    <?= $children ?? $slot ?>
</div>
