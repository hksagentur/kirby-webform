<?php /** @var \Webform\Cms\WebformBlock $block */ ?>

<div <?= attr([
    'id' => $block->form()->getId(),
    'class' => [
        'webform',
        'webform--'.$block->form()->getId()
    ],
]) ?>>
    <?php if ($block->title()->isNotEmpty()) : ?>
        <h2 class="webform__title heading heading--md">
            <?= $block->title()->esc() ?>
        </h2>
    <?php endif ?>

    <?= $block->form() ?>
</div>
