<?php /** @var ?\Webform\Form\Form $form */ ?>
<?php /** @var ?\Webform\Form\FormStatus $status */ ?>

<?php $id ??= $form?->getId() ?? $status?->getChannel() ?>

<?php $fields ??= $form?->getFields() ?>

<?php $message ??= $status?->getMessage() ?>
<?php $errors ??= $status?->getErrors() ?>

<?php if ($errors->hasAny()) : ?>
    <?php snippet('webform/error-summary', [
        'id' => $id . '-summary',
        'errors' => $errors,
        'fields' => $fields,
    ]) ?>
<?php elseif ($message) : ?>
    <?php snippet('webform/message', [
        'id' => $id . '-status',
        'type' => $status?->getType()->slug(),
        'role' => $status?->getType()->role(),
        'title' => $status?->getType()->title(),
        'autofocus' => ! $status?->isWarning(),
    ], slots: true) ?>
        <?= Sane::sanitize($message, 'html') ?>
    <?php endsnippet() ?>
<?php endif ?>
