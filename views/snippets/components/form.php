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
            'role' => 'status',
        ], slots: true) ?>
            <?= tc('hksagentur.webform.status.message.error', $form->getErrors()->count()) ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?php if ($status = $form->getStatus()) : ?>
        <?php snippet('webform/message', [
            'id' => $form->getId().'-status',
            'type' => $status->type(),
            'role' => 'status',
        ], slots: true) ?>
            <?= Sane::sanitize($status->message(), 'html') ?>
        <?php endsnippet() ?>
    <?php endif ?>

    <?= $form->getChildren() ?>

    <input type="hidden" name="_webform_id" value="<?= $form->getId() ?>">
    <input type="hidden" name="_webform_token" value="<?= $form->generateCsrfToken() ?>">

    <?php if ($page = $form->getContext()->page()) : ?>
        <input type="hidden" name="_webform_page" value="<?= $page->id() ?>">
    <?php endif ?>

    <?php if ($block = $form->getContext()->block()) : ?>
        <input type="hidden" name="_webform_block" value="<?= $block->id() ?>">
    <?php endif ?>
</form>
