<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use UnexpectedValueException;
use Webform\Cms\Contracts\HasAction as HasActionContract;
use Webform\Form\FormSubmission;

trait HasAction
{
    protected ?Closure $action = null;

    public function hasAction(): bool
    {
        return $this->action !== null;
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }

    public function action(?Closure $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function actionFromModel(string $key): static
    {
        return $this->action(static function (FormSubmission $submission) use ($key) {
            $model = $submission->getForm()?->getContext()->get($key);

            if (! ($model instanceof HasActionContract)) {
                throw new UnexpectedValueException(sprintf(
                    'The action provider "%s" must implement %s.',
                    $key,
                    HasActionContract::class,
                ));
            }

            return $model->action()->execute($submission);
        });
    }
}
