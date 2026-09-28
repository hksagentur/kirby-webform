<?php /** @var \Webform\Form\Form $form */ ?>
<?php /** @var ?\Webform\Form\FormStatus $status */ ?>

<form <?= attr([
    'class' => 'form',
    'id' => $form->getId(),
    'name' => $form->getName(),
    'action' => $form->getActionUrl(),
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'novalidate' => true,
    'data-webform-status' => $status?->getType()->slug(),
]) ?>>
    <?= $status?->toHtml($form) ?>

    <?= $children ?? $slot ?>

    <input type="hidden" name="_webform_id" value="<?= $form->getId() ?>">
    <input type="hidden" name="_webform_token" value="<?= $form->generateCsrfToken() ?>">

    <?php if ($page = $form->getContext()->page()) : ?>
        <input type="hidden" name="_webform_page" value="<?= $page->id() ?>">
    <?php endif ?>

    <?php if ($block = $form->getContext()->block()) : ?>
        <input type="hidden" name="_webform_block" value="<?= $block->id() ?>">
    <?php endif ?>
</form>
