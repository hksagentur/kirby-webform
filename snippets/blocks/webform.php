<?php /** @var \Webform\Cms\FormBlock $block */ ?>

<div <?= attr([
    'id' => $block->form()->getId(),
    'class' => [
        'webform',
        'webform--'.$block->form()->getId(),
        'stack',
    ],
]) ?>>
    <?php if ($block->title()->isNotEmpty()) : ?>
        <h2 class="webform__title">
            <?= $block->title()->esc() ?>
        </h2>
    <?php endif ?>

    <?php if ($block->text()->isNotEmpty()) : ?>
        <div class="webform__text">
            <?= $block->text()->kirbytext() ?>
        </div>
    <?php endif ?>

    <?= $block->form() ?>
</div>
