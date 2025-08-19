<?php /** @var \Webform\Form\Components\Grid $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'grid',
        $component->hasGap() ? "grid--{$component->getGap()}" : null,
    ],
])) ?>>
    <?php foreach ($childComponents as $childComponent) : ?>
        <?= $childComponent ?>
    <?php endforeach ?>
</div>
