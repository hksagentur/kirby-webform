<?php $id ??= 'error-summary-'.Str::uuid() ?>
<?php $errors ??= $form->errors() ?>

<?php if (! empty($errors)) : ?>
    <div <?= Html::attr([
        'class' => A::merge(A::wrap($class ?? []), ['error-summary']),
        'role' => 'alert',
        'aria-labelledby' => array_filter([
            $slots->title() ? $id.'-label' : null,
        ]),
        'aria-describedby' => array_filter([
            $slots->help() ? $id.'-help' : null,
        ]),
    ]) ?>>
        <?php if ($title = $slots->title()) : ?>
            <h2 id="<?= $id.'-label' ?>" class="error-summary__title">
                <?= $title ?>
            </h2>
        <?php endif ?>

        <?php if ($help = $slots->help()) : ?>
            <p id="<?= $id.'-help' ?>" class="error-summary__help">
                <?= $help ?>
            </p>
        <?php endif ?>

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
