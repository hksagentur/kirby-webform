<?php /** @var \Webform\Form\Components\Fieldset $component */ ?>

<fieldset <?= attr(A::merge($component->getExtraAttributes(), [
    'class' => 'fieldset',
    'disabled' => $component->isDisabled(),
    'role' => 'group',
])) ?>>
    <?php if ($label = $component->getLabel()) : ?>
        <legend class="fieldset__legend">
            <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
        </legend>
    <?php endif ?>

    <div class="fieldset__content">
        <?= $component->getChildren() ?>
    </div>
</fieldset>
