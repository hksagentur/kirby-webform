<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Stringable;
use Webform\Form\FormInput;
use Webform\Form\FormStatus;
use Webform\Form\FormStatusType;
use Webform\Toolkit\Arrayable;

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
        FormInput::create($channel, $input ?? App::instance()->request()->data())->flash();

        return $this;
    }

    public function withMessage(string|Stringable $text, FormStatusType|string $type = FormStatusType::Success, string $channel = 'default'): static
    {
        FormStatus::create($channel, $type, $text)->flash();

        return $this;
    }

    public function withErrors(array|Arrayable $messages, string $channel = 'default'): static
    {
        FormStatus::create($channel, FormStatusType::Invalid, errors: $messages)->flash();

        return $this;
    }
}
