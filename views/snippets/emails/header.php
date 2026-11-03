<?php /** @var \Kirby\Cms\Site $site */ ?>

<?php $headingColor ??= '#111827' ?>

<a <?= attr([
    'class' => 'email-logo',
    'href' => $site->url(),
    'target' => '_blank',
    'style' => sprintf(
        'color:%s;font-size:20px;font-weight:700;line-height:28px;text-decoration:none;',
        $headingColor,
    ),
]) ?>><?= $site->title()->esc() ?></a>
