<?php if (! empty($greeting)) : ?>
<?= esc($greeting)."\n" ?>
<?php endif ?>

<?php if (! empty($introLines)) : ?>
<?php foreach ($introLines as $introLine) : ?>
<?= esc($introLine)."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php foreach ($data as $key => $value) : ?>
<?= esc($form->find($key)?->getLabel() ?? Str::ucfirst($key)) ?>:
<?= esc($value ?: t('hksagentur.webform.template.submission.notAvailable'))."\n\n" ?>
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
<?php foreach ($footerLinks as $footerLink) : ?>
[<?= esc($footerLink['text']) ?>](<?= esc($footerLink['url']) ?>)<?= "\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($footerLines)) : ?>
<?php foreach ($footerLines as $footerLine) : ?>
<?= esc($footerLine)."\n" ?>
<?php endforeach ?>
<?php endif ?>
