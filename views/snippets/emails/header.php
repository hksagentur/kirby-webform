<?php /** @var \Webform\Template\EmailTheme $theme */ ?>

<a <?= attr([
    'class' => 'email-logo',
    'href' => site()->url(),
    'target' => '_blank',
    'style' => sprintf(
        'color:%s;font-size:20px;font-weight:700;line-height:28px;text-decoration:none;',
        $theme->headingColor,
    ),
]) ?>><?= site()->title()->esc() ?></a>
