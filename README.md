# Kirby Webforms

Easily create and manage webforms in Kirby, heavily inspired by [Filament Forms](https://filamentphp.com/docs/3.x/forms/).

## Requirements

Kirby CMS (`>=3.10`)  
PHP (`>= 8.2`)

## Installation

### Composer

```sh
composer require hksagentur/kirby-webform
```

### Download

Download the project archive and copy the files to the plugin directory of your kirby installation. By default this directory is located at `/site/plugins`.

## Configuration

Add a configuration file for a new form in `site/forms`.

```php
<?php // site/forms/contact.php

use Webform\Action\Email;
use Webform\Form\Components\Button;
use Webform\Form\Components\TextInput;
use Webform\Form\Components\Textarea;
use Webform\Form\Form;

return Form::create()->children([
  TextInput::create('name')
    ->required(),
  TextInput::create('email')
    ->required()
    ->email(),
  Textarea::create('message')
    ->required()
    ->maxLength(255)
    ->rows(8),
  Button::create('submit')
    ->action(fn (FormSubmission $submission) => Email::create('contact')
      ->subject('Contact Form')
      ->from('no-reply@example.com')
      ->to('info@example.org')
      ->execute($submission)
    ),
]);
```

## FAQ

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

<details>
<summary>How can I customize the header and footer of the submission email?</summary>
The HTML email renders the snippets `webform/emails/header` and `webform/emails/footer`. The header shows the site title by default, the footer the `footerLinks` and `footerLines` of the email data or a copyright line. Override either with a snippet of the same name in your site, e.g. to show a logo. Prefer PNG over SVG, since Outlook and Gmail don’t display SVG, and keep the `alt` text meaningful for clients that block images:

```php
<?php // site/snippets/webform/emails/header.php ?>
<a href="<?= $site->url() ?>" target="_blank">
  <img src="<?= asset('assets/images/email-logo.png')->url() ?>" alt="<?= $site->title()->esc('attr') ?>" width="240" height="64" style="display:block;border:0;">
</a>
```

Both snippets receive `$site`, `$kirby` and the theme colours of the template (`$headingColor`, `$accentColor`, `$textColor`, …); the footer also receives `$footerLinks` and `$footerLines`.
</details>

## License

ISC License. Please see [License File](LICENSE.txt) for more information.
