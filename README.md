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

## License

ISC License. Please see [License File](LICENSE.txt) for more information.
