<?php /** @var \Webform\Form\Components\Select $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>
<?php $value ??= $component->getValue() ?>

<?php $label ??= $component->getLabel() ?>
<?php $hint ??= $component->getHint() ?>
<?php $help ??= $component->getHelp() ?>

<?php $invalid ??= $component->isInvalid() ?>
<?php $messages ??= $component->getErrors() ?>

<?php snippet('webform/field', slots: true) ?>
    <?php if ($label) : ?>
        <?php snippet('webform/label', [
            'for' => $id,
            'marker' => $component->getLabelMarker(),
        ], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($label) : esc($label) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($hint) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($hint) : esc($hint) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <div <?= attr([
        'class' => [
            'select',
            ...$invalid ? ['select--invalid'] : [],
        ],
    ]) ?>>
        <select <?= attr(A::merge($component->getExtraAttributes(), [
            'class' => 'select__input',
            'id' => $id,
            'name' => $name,
            'required' => $component->isRequired(),
            'disabled' => $component->isDisabled(),
            'autocomplete' => $component->getAutocomplete(),
            'aria-invalid' => $invalid ? 'true' : null,
            'aria-describedby' => [
                ...$invalid ? ["{$id}-error"] : [],
                ...$hint ? ["{$id}-hint"] : [],
                ...$help ? ["{$id}-help"] : [],
            ],
        ])) ?>>
            <option <?= attr([
                'value' => '',
                'selected' => $value == $component->getDefaultValue(),
            ]) ?>>
                <?php if ($component->isRequired()) : ?>
                    <?= t('hksagentur.webform.snippet.select.empty') ?>
                <?php else : ?>
                    <?= t('hksagentur.webform.snippet.select.none') ?>
                <?php endif ?>
            </option>

            <?php foreach ($component->getOptions()->select($value) as $option) : ?>
                <option <?= attr([
                    'value' => $option->value(),
                    'selected' => $option->isSelected(),
                ]) ?>>
                    <?= $component->isHtmlAllowed() ? kti($option->label()) : esc($option->label())  ?>
                </option>
            <?php endforeach ?>
        </select>
        <?php snippet('webform/icon', [
            'name' => 'caret',
            'class' => 'select__caret',
        ]) ?>
    </div>

    <?php snippet('webform/inline-error', [
        'id' => "{$id}-error",
        'messages' => $messages,
    ]) ?>

    <?php if ($help) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? kti($help) : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>
<?php endsnippet() ?>
