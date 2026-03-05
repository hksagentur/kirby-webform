<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Stringable;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Flash;

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

    public function withInput(?array $input = null, string $channel = 'default'): static
    {
        $input = is_null($input)
            ? App::instance()->request()->data()
            : $input;

        Flash::put("webform.form.{$channel}.input", $input);

        return $this;
    }

    public function withStatus(string|Stringable $message, string $channel = 'default'): static
    {
        Flash::put("webform.form.{$channel}.message", $message);

        return $this;
    }

    public function withErrors(array|Arrayable $messages, string $channel = 'default'): static
    {
        $messages = $messages instanceof Arrayable
            ? $messages->toArray()
            : $messages;

        Flash::put("webform.form.{$channel}.errors", $messages);

        return $this;
    }
}
