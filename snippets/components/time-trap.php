<?php /** @var \Webform\Form\Components\TimeTrap $component */ ?>

<input <?= attr(A::merge($component->getExtraAttributes(), [
    'type' => 'hidden',
    'id' => $component->getId(),
    'name' => $component->getName(),
    'value' => $component->shouldEncrypt() ? $component->getEncryptedValue() : $component->getValue(),
])) ?>>
