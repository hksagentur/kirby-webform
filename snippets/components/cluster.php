<?php /** @var \Webform\Form\Components\Cluster $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'cluster',
        $component->hasGap() ? "cluster--{$component->getGap()}" : null,
    ],
])) ?>>
    <?php foreach ($children as $child) : ?>
        <?= $child ?>
    <?php endforeach ?>
</div>
