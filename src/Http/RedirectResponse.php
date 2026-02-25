<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Webform\Toolkit\Flash;
use Webform\Validation\Message;
use Webform\Validation\Messages;

class RedirectResponse extends Response
{
    public function __construct(
        string $location = '/',
        ?int $code = 302,
        array $headers = [],
    ) {
        parent::__construct([
            'code' => $code,
            'headers' => [
                'Location' => Url::unIdn($location),
                ...$headers,
            ],
        ]);
    }

    public function withInput(?array $input = null, string $inputBag = 'default'): static
    {
        $input = is_null($input)
            ? App::instance()->request()->data()
            : $input;

        Flash::put("webform.form.{$inputBag}.input", $input);

        return $this;
    }

    public function withStatus(string|Message $message, string $messageBag = 'default'): static
    {
        Flash::put("webform.form.{$messageBag}.status", $message);

        return $this;
    }

    public function withErrors(array|Messages $messages, string $errorBag = 'default'): static
    {
        $messages = $messages instanceof Messages
            ? $messages->all()
            : $messages;

        Flash::put("webform.form.{$errorBag}.errors", $messages);

        return $this;
    }
}
