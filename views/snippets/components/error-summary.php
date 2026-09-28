<?php $fields ??= null ?>
<?php $autofocus ??= true ?>

<div <?= attr([
    'id' => $id ?? null,
    'class' => [
        'error-summary',
        ...A::wrap($class ?? []),
    ],
    'autofocus' => $autofocus,
    'tabindex' => $autofocus ? '-1' : null,
]) ?>>
    <div <?= attr([
        'class' => 'error-summary__inner',
        'role' => 'alert',
    ]) ?>>
        <<?= $level ?? 'h2' ?> <?= attr([
            'class' => 'error-summary__title',
        ]) ?>>
            <?= $title ?? \Webform\Form\FormStatusType::Invalid->title() ?>
        </<?= $level ?? 'h2' ?>>

        <ul class="error-summary__list" role="list">
            <?php foreach ($errors->keys() as $name) : ?>
                <li class="error-summary__list-item">
                    <?php if ($field = $fields?->findBy('name', $name)) : ?>
                        <a <?= attr([
                            'href' => '#' . $field->getId(),
                            'class' => [
                                'error-summary__message',
                                'error-summary__message--linked',
                            ],
                        ]) ?>>
                            <?= Sane::sanitize($errors->first($name), 'html') ?>
                        </a>
                    <?php else : ?>
                        <span <?= attr([
                            'class' => [
                                'error-summary__message',
                                'error-summary__message--orphaned',
                            ],
                        ]) ?>>
                            <?= Sane::sanitize($errors->first($name), 'html') ?>
                        </span>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
