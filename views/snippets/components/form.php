<?php /** @var \Webform\Form\Form $form */ ?>

<form <?= attr([
    'class' => 'form',
    'id' => $form->getId(),
    'name' => $form->getName(),
    'action' => $form->getActionUrl(),
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'novalidate' => true,
]) ?>>
    <?php if ($form->hasErrors()) : ?>
        <?php snippet('webform/message', [
            'id' => $form->getId().'-error',
            'type' => 'error',
            'role' => 'alert',
        ], slots: true) ?>
            <?= tc('hksagentur.webform.status.message.error', $form->getErrors()->count()) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($status = $form->getStatus()) : ?>
        <?php snippet('webform/message', [
            'id' => $form->getId().'-status',
            'type' => 'success',
            'role' => 'status',
        ], slots: true) ?>
            <?= esc($status) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?= $form->getChildren() ?>

    <input type="hidden" name="_webform_id" value="<?= $form->getId() ?>">
    <input type="hidden" name="_webform_token" value="<?= $form->getCsrfToken() ?>">
    <input type="hidden" name="_webform_block" value="<?= $form->getBlockId() ?>">
    <input type="hidden" name="_webform_referrer" value="<?= $form->getModelId() ?>">
</form>
