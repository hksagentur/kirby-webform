<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Webform\Form\MessageBag;
use Webform\Form\StatusMessage;
use Webform\Session\TransientData;

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

    public function withInput(?array $input = null): static
    {
        $input = is_null($input)
            ? App::instance()->request()->data()
            : $input;

        TransientData::instance()->put('webform.form.input', $input);

        return $this;
    }

    public function withStatus(string|StatusMessage $message, string $messageBag = 'default'): static
    {
        TransientData::instance()->put("webform.form.status.{$messageBag}", $message);

        return $this;
    }

    public function withErrors(array|MessageBag $messages, string $errorBag = 'default'): static
    {
        $messages = $messages instanceof MessageBag
            ? $messages->all()
            : $messages;

        TransientData::instance()->put("webform.form.errors.{$errorBag}", $messages);

        return $this;
    }
}
