<?php $id ??= Str::kebab($page->form()->value()) ?>

<?php if ($form->success()) : ?>
    <?php snippet('webform/status-message', [
        'type' => 'success',
        'id' => $id.'-status',
    ], slots: true) ?>
        <?php slot('title') ?>
            <?= t('form.message.success.title') ?>
        <?php endslot() ?>

        <?php slot('default') ?>
            <?= t('form.message.success.summary') ?>
        <?php endslot() ?>
    <?php endsnippet() ?>
<?php elseif ($form->errors()) : ?>
    <?php snippet('webform/error-summary', [
        'id' => $id.'-summary',
    ], slots: true) ?>
        <?php slot('title') ?>
            <?= t('form.message.error.title') ?>
        <?php endslot() ?>

        <?php slot('help') ?>
            <?= t('form.message.error.summary') ?>
        <?php endslot() ?>
    <?php endsnippet() ?>
<?php endif ?>

<?php snippet('webform/form', ['id' => $id], slots: true) ?>
    <?php snippet('webform/field', slots: true) ?>
        <?php snippet('webform/input', [
            'type' => 'text',
            'name' => 'name',
            'autocomplete' => 'name',
            'required' => true,
        ], slots: true) ?>
            <?= t('form.field.name.label') ?>
        <?php endsnippet() ?>
    <?php endsnippet() ?>

    <?php snippet('webform/field', slots: true) ?>
        <?php snippet('webform/input', [
            'type' => 'email',
            'name' => 'email',
            'autocomplete' => 'email',
            'required' => true,
        ], slots: true) ?>
            <?= t('form.field.email.label') ?>
        <?php endsnippet() ?>
    <?php endsnippet() ?>

    <?php snippet('webform/field', slots: true) ?>
        <?php snippet('webform/textarea', [
            'name' => 'message',
            'rows' => 8,
            'required' => true,
        ], slots: true) ?>
            <?= t('form.field.message.label') ?>
        <?php endsnippet() ?>
    <?php endsnippet() ?>

    <?php snippet('webform/button', ['type' => 'submit'], slots: true) ?>
        <?= t('form.action.submit.label') ?>
    <?php endsnippet() ?>
<?php endsnippet() ?>
