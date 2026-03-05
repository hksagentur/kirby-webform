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

    public function withInput(array|null|Arrayable $input = null, string $channel = 'default'): static
    {
        $input ??= App::instance()->request()->data();

        if ($input instanceof Arrayable) {
            $input = $input->toArray();
        }

        Flash::put("webform.form.{$channel}.input", $input);

        return $this;
    }

    public function withMessage(string|Stringable $text, string $type = 'success', string $channel = 'default'): static
    {
        if ($text instanceof Stringable) {
            $text = (string) $text;
        }

        Flash::put("webform.form.{$channel}.message", [
            'message' => $text,
            'type' => $type,
        ]);

        return $this;
    }

    public function withErrors(array|Arrayable $messages, string $channel = 'default'): static
    {
        if ($messages instanceof Arrayable) {
            $messages = $messages->toArray();
        }

        Flash::put("webform.form.{$channel}.errors", $messages);

        return $this;
    }
}
