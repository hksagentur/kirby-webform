<?php /** @var \Webform\Form\Components\ButtonGroup $component */ ?>

<div <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => [
        'button-group',
        'cluster',
    ],
])) ?>>
    <?php foreach ($children as $child) : ?>
        <?= $child ?>
    <?php endforeach ?>
</div>
