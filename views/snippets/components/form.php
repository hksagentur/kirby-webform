<?php
/**
 * @var \Webform\Form\Form $form
 * @var \Webform\Form\MessageBag|null $errors
 * @var \Webform\Form\StatusMessage|null $status
 */
?>

<form <?= attr([
    'class' => 'form',
    'id' => $form->getId(),
    'name' => $form->getName(),
    'action' => $form->getActionUrl(),
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'novalidate' => true,
]) ?>>
    <?php if (isset($errors) && $errors->isNotEmpty()) : ?>
        <?php snippet('webform/message', [
            'id' => $form->getId().'-error',
            'type' => 'error',
            'role' => 'alert',
        ], slots: true) ?>
            <?= tc('hksagentur.webform.status.message.error', $errors->count()) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if (isset($status)) : ?>
        <?php snippet('webform/message', [
            'id' => $form->getId().'-status',
            'type' => $status->getType(),
            'role' => $status->getRole(),
        ], slots: true) ?>
            <?= esc($status->getMessage()) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php foreach ($children as $child) : ?>
        <?= $child ?>
    <?php endforeach ?>

    <input type="hidden" name="_webform_id" value="<?= $form->getId() ?>">
    <input type="hidden" name="_webform_token" value="<?= $form->getCsrfToken() ?>">
    <input type="hidden" name="_webform_block" value="<?= $form->getBlockId() ?>">
    <input type="hidden" name="_webform_referrer" value="<?= $form->getModelId() ?>">
</form>
