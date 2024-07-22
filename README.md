# Kirby Webforms

Easily create and manage webforms in Kirby

## Requirements

Kirby CMS (`>=3.9`)  
PHP (`>= 8.1`)

## Installation

### Composer

```sh
composer require hksagentur/kirby-webform
```

### Download

Download the project archive and copy the files to the plugin directory of your kirby installation. By default this directory is located at `/site/plugins`.

## Usage

This plugin provides a new blueprint for form pages. Users can use this blueprint to create new form pages and select one of multiple predefined forms. These forms are controlled by configuration files in a custom directory (`site/forms` by default). The configuration files can be used to specify the e-mail address to which submissions should be sent, as well as the validation criteria for individual fields.

> [!NOTE]
> The plugin does not yet provide any snippets to help generate the actual HTML code for the form. This has so far been left to the respective themes.

## Configuration

Add a configuration file for a new form in `site/forms`. You can also use the custom CLI command to generate a file to start from (`kirby webform:make`).

Each form should at least provide a display name:

```php
<?php // site/forms/contact.php

return [
  'label' => 'Contact Form',
];
```

Once the configuration file is saved, users can select the form in any page that uses the form blueprint.

Depending on your use case you may want to specify custom validation rules for the form. These rules should follow the following structure:

```php
<?php // site/forms/contact.php

return [
  'label' => 'Contact Form',
  'fields' => [
        'name' => [
            'rules' => ['required'],
            'message' => ['Your name is required'],
        ],
        'email' => [
            'rules' => ['required', 'email'],
            'message' => ['Your e-mail address is required', 'Please enter a valid e-mail address'],
        ],
        'message' => [
            'rules' => ['required'],
            'message' => ['A message is required'],
        ],
    ],
];
```

Kirby provides a predefined set of validators that can be further extended (see <https://getkirby.com/docs/reference/system/validators>).

You can further customize the settings of the email that will be generated for each form submission:

```php
<?php // site/forms/contact.php

return [
  'label' => 'Contact Form',
  'email' => [
    'from' => 'no-reply@example.org',
    'to' => 'info@example.org',
  ],
  'fields' => [
    // ...
  ],
];
```

> [!NOTE]
> The email settings will only be used when no recipient is selected on the dedicated form page.

In addition you can provide an email preset to use for each form submission. See the [Kirby configuration](https://getkirby.com/docs/guide/emails#presets) for further information about this feature.

```php
<?php // site/forms/contact.php

return [
  'label' => 'Contact Form',
  'email' => [
    'preset' => 'contact-form',
  ],
  'fields' => [
    // ...
  ],
];
```

## FAQ

<details>
<summary>How do I render the form in the frontend?</summary>
You can add a custom template for form pages and embed different snippets dependent on the selected form:

```php
<?php // site/templates/form.php ?>

<?php snippet('header'); ?>

<main>
  <h1>
    <?= $page->title()->esc() ?>
  </h1>

  <?php
    snippet("forms/{$page->form()->value()}", [
      'method' => 'POST'
      'action' => $page->url(),
      'form' => $form,
    ])
  ?>
</main>

<?php snippet('footer'); ?>

```
This allows you to add a separate snippet for each form utilized by your site. You could then go on and add your form logic:

```php
<?php // site/snippets/forms/contact.php ?>

<form id="contact-form" method="<?= $method ?? 'POST' ?>" action="<?= $url ?? $page->url() ?>" novalidate>
  <div class="field">
    <label for="name">
      <?= t('form.field.name', 'Name') ?>
    </label>

    <input type="text" id="name" name="name" autocomplete="name" value="<?= $form->old('name') ?>" required <?= $form->error('name') ? attr([
      'aria-invalid' => 'true',
      'aria-describedby' => 'error-name',
    ]) : '' ?>>

    <?php if ($form->error('name')) : ?>
      <div id="error-name" class="hint">
        <?= implode(', ', $form->error('name')) ?>
      </div>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="email">
      <?= t('form.field.email', 'E-Mail') ?>
    </label>

    <input type="email" id="email" name="email" autocomplete="email" required <?= $form->error('email') ? attr([
      'aria-invalid' => 'true',
      'aria-describedby' => 'error-email',
    ]) : '' ?>>

    <?php if ($form->error('email')) : ?>
      <div id="error-email" class="hint">
        <?= implode(', ', $form->error('email')) ?>
      </div>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="message">
      <?= t('form.field.message', 'Message') ?>
    </label>

    <textarea id="message" name="message" rows="8" <?= $form->error('message') ? attr([
      'aria-invalid' => 'true',
      'aria-describedby' => 'error-message',
    ]) : '' ?>><?= $form->old('message') ?></textarea>

    <?php if ($form->error('message')) : ?>
      <div id="error-message" class="hint">
        <?= implode(', ', $form->error('message')) ?>
      </div>
    <?php endif ?>
  </div>

  <button type="submit" class="button">
    <?= t('form.action.submit', 'Submit Form') ?>
  </button>
</form>
```
</details>

<details>
<summary>How can I add additional fields to a form page?</summary>
You can overwrite the standard blueprint provided by the plugin by creating your custom version in `site/blueprints/form.yml`. If you want to reuse the fields provided by the plugin you can utilize the corresponding field group:

```yaml
# site/blueprints/form.yml

extends: pages/default

title: Form
icon: chat

columns:
  main:
    width: 2/3
    fields:
      blocks:
        type: blocks
        label: Content
      form: fields/form # or @hksagentur/webform/fields/form

  sidebar:
    width: 1/3
    fields:
      cover:
        type: files
        label: Cover
        multiple: false
```
</details>

<details>
<summary>How can add text to the email generated for each form submission?</summary>

The default template used for each form submission provides some variables that can be used to customize the text of each email. You can use a [kirby hook](https://getkirby.com/docs/reference/plugins/extensions/hooks) to inject data for these variables:

```php
<?php // site/plugins/example-hook/index.php

use Uniform\Form;
use Webform\Cms\FormPage;

Kirby::plugin('webform/example-hook', [
  'hooks' => [
    'webform.emailSubmission:before' => fn (FormPage $page, Form $form) => [
      'logo' => $page->site()->logo()->toFile()?->url(),
      'greeting' => sprintf('Hi, %s!', $form->data('name')),
      'introLines' => ['A new form submission is available.'],
      'actionText' => 'View Page',
      'actionUrl' => $page->url(),
      'outroLines' => ['This is an automatically generated email.'],
      'salutation' => sprintf('Greetings,\n%s', $page->site()->title()),
      'submission' => $form->data(),
    ];
  ],
])
```

If you want to fully customize the email template you can overwrite the template used, either in your email preset or in your form configuration:

```php
<?php // site/forms/contact.php

return [
  'label' => 'Contact Form',
  'email' => [
    'template' => 'contact-form',
  ],
  'fields' => [
    // ...
  ],
];
```

Place your new template in the dedicated directory for [email templates](https://getkirby.com/docs/guide/emails#formats).
</details>

<details>
<summary>How can I customize the directory from which the form configuration is read?</summary>
You can provide a custom directory via Kirby’s `roots` structure. Open the `index.php` and adjust the initialization of the kirby core:

```php
<?php // index.php

require __DIR__ . '/kirby/bootstrap.php';

$kirby = new Kirby([
  'roots' => [
    'webforms' => dirname(__DIR__) . '/site/webforms',
  ],
]);

echo $kirby->render();
```
</details>

## License

ISC License. Please see [License File](LICENSE.txt) for more information.
