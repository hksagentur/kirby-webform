<?php /** @var \Webform\Form\Components\Cluster $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'cluster',
        $component->hasGap() ? "cluster--{$component->getGap()}" : null,
    ],
])) ?>>
    <?php foreach ($childComponents as $childComponent) : ?>
        <?= $childComponent ?>
    <?php endforeach ?>
</div>
