<?php /** @var \Webform\Form\Components\Cluster $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'cluster',
    'data-gap' => $component->getGap(),
])) ?>>
    <?= $children ?? $slot ?>
</div>
