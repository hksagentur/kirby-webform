<?php /** @var \Webform\Form\Components\Select $component */ ?>

<?php $id ??= $component->getId() ?>
<?php $name ??= $component->getName() ?>
<?php $value ??= $component->getValue() ?>

<?php $invalid ??= $errors->hasAny($name) ?>
<?php $messages ??= $errors->get($name) ?>

<?php snippet('webform/field', slots: true) ?>
    <?php if ($label = $component->getLabel()) : ?>
        <?php snippet('webform/label', ['for' => $id], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $label : esc($label) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($hint = $component->getHint()) : ?>
        <?php snippet('webform/hint', ['id' => "{$id}-hint"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $hint : esc($hint) ?>
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

            <?php foreach ($component->getOptions() as $optionValue => $optionLabel) : ?>
                <?php if (is_array($optionLabel)) : ?>
                    <optgroup label="<?= esc($optionValue, 'attr') ?>">
                        <?php foreach ($optionLabel as $groupedOptionValue => $groupedOptionLabel) : ?>
                            <option <?= attr([
                                'value' => $groupedOptionValue,
                                'selected' => $groupedOptionValue == $value,
                            ]) ?>>
                                <?= $component->isHtmlAllowed() ? $groupedOptionLabel : esc($groupedOptionLabel) ?>
                            </option>
                        <?php endforeach ?>
                    </optgroup>
                <?php else : ?>
                    <option <?= attr([
                        'value' => $optionValue,
                        'selected' => $optionValue == $value,
                    ]) ?>>
                        <?= $component->isHtmlAllowed() ? $optionLabel : esc($optionLabel)  ?>
                    </option>
                <?php endif ?>
            <?php endforeach ?>
        </select>
        <svg class="select__caret" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M23.468,2.984a2,2,0,0,0-1.742-1.018H2.274A2,2,0,0,0,.563,5L10.289,21.07a2,2,0,0,0,3.422,0L23.437,5A2,2,0,0,0,23.468,2.984Z"/>
        </svg>
    </div>

    <?php snippet('webform/inline-error', [
        'id' => "{$id}-error",
        'messages' => $messages,
    ]) ?>

    <?php if ($help = $component->getHelp()) : ?>
        <?php snippet('webform/help', ['id' => "{$id}-help"], slots: true) ?>
            <?= $component->isHtmlAllowed() ? $help : esc($help) ?>
        <?php endsnippet() ?>
    <?php endif ?>
<?php endsnippet() ?>
