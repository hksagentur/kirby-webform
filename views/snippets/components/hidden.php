<?php /** @var \Webform\Form\Components\Hidden $component */ ?>

<input <?= attr(A::merge($component->getExtraAttributes(), [
    'type' => 'hidden',
    'id' => $component->getId(),
    'name' => $component->getName(),
    'value' => $component->getValue(),
])) ?>>
