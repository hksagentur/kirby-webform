<?php /** @var \Webform\Form\Components\Stack $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'stack',
        $component->hasGap() ? "stack--{$component->getGap()}" : null,
    ],
])) ?>>
    <?php foreach ($childComponents as $childComponent) : ?>
        <?= $childComponent ?>
    <?php endforeach ?>
</div>
