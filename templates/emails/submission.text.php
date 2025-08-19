<?php if (! empty($greeting)) : ?>
<?= esc($greeting)."\n" ?>
<?php endif ?>

<?php if (! empty($introLines)) : ?>
<?php foreach ($introLines as $line) : ?>
<?= esc($line)."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php foreach ($data as $key => $value) : ?>
<?= esc($form->getField($key)?->getLabel() ?? Str::ucfirst($key)) ?>:
<?= esc($value ?: t('hksagentur.webform.template.submission.notAvailable'))."\n\n" ?>
<?php endforeach ?>

<?php if (! empty($actionText)) : ?>
[<?= esc($actionText) ?>](<?= esc($actionUrl ?? $site->url()) ?>)
<?php endif ?>

<?php if (! empty($outroLines)) : ?>
<?php foreach ($outroLines as $line) : ?>
<?= esc($line)."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($salutation)) : ?>
<?= esc($salutation)."\n" ?>
<?php endif ?>
