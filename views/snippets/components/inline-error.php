<?php if (! empty($messages)) : ?>
    <div <?= attr([
        'id' => $id ?? null,
        'class' => [
            'inline-error',
            ...A::wrap($class ?? []),
        ],
        ...$attrs ?? [],
    ]) ?>>
        <?php snippet('webform/icon', [
            'name' => 'alert',
            'class' => 'inline-error__icon',
        ]) ?>

        <span class="inline-error__label visually-hidden">
            <?= t('hksagentur.webform.inlineError.label') ?>
        </span>

        <ul class="inline-error__list" role="list">
            <?php foreach ($messages as $message) : ?>
                <li class="inline-error__list-item">
                    <?= Sane::sanitize($message, 'html') ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
