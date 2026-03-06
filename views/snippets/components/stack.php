<?php /** @var \Webform\Form\Components\Stack $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'stack',
    'data-gap' => $component->getGap(),
])) ?>>
    <?= $children ?? $slot ?>
</div>
