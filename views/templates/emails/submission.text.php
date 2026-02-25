<?php
/**
 * E-Mail Template for Webform Submissions
 *
 * @var \Kirby\Cms\App $kirby
 * @var \Kirby\Cms\Site $site
 * @var \Kirby\Cms\User $user
 * @var \Webform\Form\Form $form
 */
?>

<?php if (! empty($greeting)) : ?>
<?= esc($greeting)."\n" ?>
<?php endif ?>

<?php if (! empty($introLines)) : ?>
<?php foreach ($introLines as $introLine) : ?>
<?= esc($introLine)."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php foreach ($rows as ['label' => $label, 'value' => $value]) : ?>
<?= esc($label) ?>:
<?= esc($value)."\n\n" ?>
<?php endforeach ?>

<?php if (! empty($actionText)) : ?>
[<?= esc($actionText) ?>](<?= esc($actionUrl ?? $site->url()) ?>)
<?php endif ?>

<?php if (! empty($outroLines)) : ?>
<?php foreach ($outroLines as $outroLine) : ?>
<?= esc($outroLine)."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($salutation)) : ?>
<?= esc($salutation)."\n" ?>
<?php endif ?>

<?php if (! empty($footerLinks)) : ?>
<?php foreach ($footerLinks as ['title' => $title, 'url' => $url]) : ?>
[<?= esc($title) ?>](<?= esc($url) ?>)<?= "\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($footerLines)) : ?>
<?php foreach ($footerLines as $footerLine) : ?>
<?= esc($footerLine)."\n" ?>
<?php endforeach ?>
<?php endif ?>
