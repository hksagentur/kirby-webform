<?php
/**
 * E-Mail Template for Webform Submissions
 *
 * @var \Kirby\Cms\App $kirby
 * @var \Kirby\Cms\Site $site
 * @var \Kirby\Cms\User $user
 */
?>

<?php if (! empty($greeting)) : ?>
<?= $greeting."\n" ?>
<?php endif ?>

<?php if (! empty($introLines)) : ?>
<?php foreach ($introLines as $introLine) : ?>
<?= $introLine."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php foreach ($rows as ['label' => $label, 'value' => $value]) : ?>
<?= $label ?>:
<?= $value."\n\n" ?>
<?php endforeach ?>

<?php if (! empty($actionText)) : ?>
[<?= $actionText ?>](<?= $actionUrl ?? $site->url() ?>)
<?php endif ?>

<?php if (! empty($outroLines)) : ?>
<?php foreach ($outroLines as $outroLine) : ?>
<?= $outroLine."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($salutation)) : ?>
<?= $salutation."\n" ?>
<?php endif ?>

<?php if (! empty($footerLinks)) : ?>
<?php foreach ($footerLinks as ['title' => $title, 'url' => $url]) : ?>
[<?= $title ?>](<?= $url ?>)<?= "\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($footerLines)) : ?>
<?php foreach ($footerLines as $footerLine) : ?>
<?= $footerLine."\n" ?>
<?php endforeach ?>
<?php endif ?>
