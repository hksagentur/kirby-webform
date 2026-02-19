<?php

namespace Webform\Exception;

use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Component;

class ChallengeFailedException extends Exception
{
    protected ?Component $component = null;

    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }


        $arguments += [
             'key' => 'hksagentur.webform.challengeFailed',
             'fallback' => 'Challenge failed.',
             'httpCode' => 422,
         ];

        $this->withComponent($arguments['component'] ?? null);

        parent::__construct($arguments);
    }

    /** @return Component&ProvidesChallenge|null */
    public function getComponent(): ?Component
    {
        return $this->component;
    }

    /** @param Component&ProvidesChallenge|null $component */
    public function withComponent(?Component $component): static
    {
        $this->component = $component;

        return $this;
    }
}
