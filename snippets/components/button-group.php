<?php /** @var \Webform\Form\Components\ButtonGroup $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'button-group',
        'cluster',
    ],
])) ?>>
    <?php foreach ($childComponents as $childComponent) : ?>
        <?= $childComponent ?>
    <?php endforeach ?>
</div>
