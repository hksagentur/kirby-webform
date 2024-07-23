<?php $id ??= 'error-summary-'.Str::uuid() ?>

<?php if ($errors = $form->errors()) : ?>
    <div <?= Html::attr([
        'class' => A::merge(A::wrap($class ?? []), ['error-summary']),
        'role' => 'alert',
        'aria-labelledby' => 'label-'.$id,
        'aria-describedby' => 'help-'.$id,
    ]) ?>>
        <h2 id="<?= 'label-'.$id ?>"class="error-summary__title">
            <?php if ($title = $slots->title()) : ?>
                <?= $title ?>
            <?php else : ?>
                <?= t('hksagentur.webform.summary.label') ?>
            <?php endif ?>
        </h2>

        <p id="<?= 'help-'.$id ?>" class="error-summary__help">
            <?php if ($help = $slots->help()) : ?>
                <?= $help ?>
            <?php else : ?>
                <?= t('hksagentur.webform.summary.help') ?>
            <?php endif ?>
        </p>

        <ul class="error-summary__list">
            <?php foreach ($errors as $field => $messages) : ?>
                <?php foreach ($messages as $message) : ?>
                    <li class="error-summary__list-item" data-field-name="<?= $field ?>">
                        <?= $message ?>
                    </li>
                <?php endforeach ?>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
