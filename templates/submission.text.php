<?php if (! empty($greeting)) : ?>
<?= $greeting."\n" ?>
<?php endif ?>

<?php if (! empty($introLines)) : ?>
<?php foreach ($introLines as $line) : ?>
<?= $line."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($submission)) : ?>
<?php foreach ($submission as $key => $value) : ?>
<?= Str::ucfirst($key) ?>:
<?= $value."\n\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($actionText)) : ?>
[<?= $actionText ?>](<?= $actionUrl ?? site()->url() ?>)
<?php endif ?>

<?php if (! empty($outroLines)) : ?>
<?php foreach ($outroLines as $line) : ?>
<?= $line."\n" ?>
<?php endforeach ?>
<?php endif ?>

<?php if (! empty($salutation)) : ?>
<?= $salutation."\n" ?>
<?php endif ?>
