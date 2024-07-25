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

## Configuration

Add a configuration file for a new form in `site/forms`. You can also use a custom CLI command to generate the required files (`kirby make:webform`).

Each form should at least provide a display name:

```php
<?php // site/forms/contact.php

return [
  'name' => 'Contact Form',
];
```

### Form Validation

Once the configuration file is saved, users can select the form in any page that uses the custom form blueprint.

Depending on your use case you may want to specify custom validation rules for the form. These rules should follow the following structure:

```php
<?php // site/forms/contact.php

return [
  'name' => 'Contact Form',
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

### Form Actions

In addition you can tell the plugin which actions you want to perform once a form submission passes your validation criteria. Multiple actions are available. You could for example save the submission in a database and send an email notification to a predefined address.

> [!NOTE]
> Make sure to add your database credentials to the site configuration `site/config/config.php`, otherwise Kirby does not know, which connection to use.

```php
<?php // site/forms/contact.php

use Uniform\Guards\HoneypotGuard;
use Uniform\Actions\EmailAction;
use Webform\Actions\DatabaseAction;

return [
  'name' => 'Contact Form',
  'guards' => [
    HoneypotGuard::class,
  ],
  'actions' => [
    EmailAction::class,
    DatabaseAction::class,
  ],
  'email' => [
    'template' => 'webform/submission',
    'from' => 'no-reply@example.org',
    'to' => 'info@example.org',
  ],
  'database' => [
    'table' => 'submissions',
  ],
  'fields' => [
    // ...
  ],
];
```

The following actions are supported by default:

- Send an email ([`Uniform\Actions\EmailAction`](https://kirby-uniform.readthedocs.io/en/latest/actions/email/))
- Write submission details to a log file ([`Uniform\Actions\LogAction`](https://kirby-uniform.readthedocs.io/en/latest/actions/log/))
- Notify a webhook about a form submission ([`Uniform\Actions\WebhookAction`](https://kirby-uniform.readthedocs.io/en/latest/actions/webhook/))
- Upload files to a predefined directory ([`Uniform\Actions\UploadAction`](https://kirby-uniform.readthedocs.io/en/latest/actions/upload/))
- Store submission details in a database table ([`Webform\Actions\DatabaseAction`](./src/Form/Actions/DatabaseAction.php))

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
    snippet("forms/{$page->form()}", [
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

  <?= csrf_field() ?>
  <?= honeypot_field() ?>

  <button type="submit" class="button">
    <?= t('form.action.submit', 'Submit Form') ?>
  </button>
</form>
```

If you prefer you can use the snippets provided by the plugin for the most common form controls:

```php
<?php // site/snippets/forms/contact.php ?>

<?php snippet('webform/form', slots: true) ?>
  <?php snippet('webform/field', slots: true) ?>
    <?php snippet('webform/input', [
      'type' => 'text',
      'name' => 'name',
      'autocomplete' => 'name',
      'required' => true,
    ], slots: true) ?>
      <?= t('form.field.name', 'Name') ?>
    <?php endsnippet() ?>
  <?php endsnippet() ?>

  <?php snippet('webform/field', slots: true) ?>
    <?php snippet('webform/input', [
      'type' => 'email',
      'name' => 'email',
      'autocomplete' => 'email',
      'required' => true,
    ], slots: true) ?>
      <?= t('form.field.email', 'E-Mail') ?>
    <?php endsnippet() ?>
  <?php endsnippet() ?>

  <?php snippet('webform/field', slots: true) ?>
    <?php snippet('webform/textarea', [
      'name' => 'message',
      'rows' => 8,
      'required' => true,
    ], slots: true) ?>
      <?= t('form.field.message', 'Message') ?>
    <?php endsnippet() ?>
  <?php endsnippet() ?>

  <?php snippet('webform/button', ['type' => 'submit'], slots: true) ?>
    <?= t('form.action.submit', 'Submit Form') ?>
  <?php endsnippet() ?>
<?php endsnippet() ?>
```

The following form controls area supported: `button`, `input`, `textarea`, `select`, `radio`, `radio-group`, `checkbox`, 'checkbox-group`.
</details>

<details>
<summary>How can I add additional fields to a form page?</summary>
You can overwrite the standard blueprint provided by the plugin by creating your custom version in `site/blueprints/pages/form.yml`.

```yaml
# site/blueprints/pages/form.yml

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
      form: fields/form # or '@hksagentur/webform/fields/form'

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
<summary>How to use the provided email template for form submissions?</summary>

The plugin provides a custom email template for form submissions. This template generates a table that summarizes the submission details. To use the template adjust your email options for the corresponding form:

```php
<?php // site/forms/contact.php

use Uniform\Actions\EmailAction;

return [
  'name' => 'Contact Form',
  'actions' => [
    EmailAction::class,
  ],
  'email' => [
    'template' => 'webform/submission',
  ],
  'fields' => [
    // ...
  ],
];

```

This template used provides various placeholders that allow you to customize the email content:

```php
<?php // site/forms/contact.php

use Uniform\Actions\EmailAction;

return [
  'name' => 'Contact Form',
  'actions' => [
    EmailAction::class,
  ],
  'email' => [
    'template' => 'webform/submission',
    'data' => [
      'logo' => site()->logo()->toFile()?->url(),
      'greeting' => 'Hi!',
      'introLines' => ['A new form submission is available.'],
      'actionText' => 'View Page',
      'actionUrl' => url('contact') ,
      'outroLines' => ['This is an automatically generated email.'],
      'salutation' => sprintf("Greetings,\n%s", site()->title()),
    ],
  ],
  'fields' => [
    // ...
  ],
];

```

Of course you can also simply use your own template.

```php
<?php // site/forms/contact.php

use Uniform\Actions\EmailAction;

return [
  'name' => 'Contact Form',
  'actions' => [
    EmailAction::class,
  ],
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
