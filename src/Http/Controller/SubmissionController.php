<?php

namespace Webform\Http\Controller;

use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Toolkit\I18n;
use Throwable;
use Webform\Form\Form;
use Webform\Http\RedirectResponse;
use Webform\Validation\ValidationException;

class SubmissionController
{
    public function __invoke(Form $form, ?string $operation = null): RedirectResponse
    {
        try {
            $validated = $form->validate();
        } catch (ValidationException $exception) {
            return $this->failedValidation($form, $exception);
        }

        try {
            $form->submit($validated, $operation);
        } catch (Throwable $exception) {
            return $this->failedSubmission($form, $exception);
        }

        return $this->processedSubmission($form);
    }

    protected function getRedirectUrl(Form $form): string
    {
        /** @var ?Page $referrer */
        $referrer = $form->getContext()->page();

        if (! $referrer || ! $referrer->isAccessible()) {
            return Url::home();
        }

        return sprintf('%s#%s', $referrer->url(), $form->getId());
    }

    protected function getSuccessMessage(Form $form): ?string
    {
        return $form->getContext()?->block()?->successMessage()->value();
    }

    protected function getErrorMessage(Form $form): ?string
    {
        return $form->getContext()?->block()?->errorMessage()->value();
    }

    protected function failedValidation(Form $form, ValidationException $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl($form)))
            ->withInput(channel: $form->getKey())
            ->withErrors(
                messages: $exception->getErrors(),
                channel: $form->getKey(),
            );
    }

    protected function failedSubmission(Form $form, Throwable $exception): RedirectResponse
    {
        $url = $this->getRedirectUrl($form);
        $message = $this->getErrorMessage($form);

        return (new RedirectResponse($url))
            ->withInput(channel: $form->getKey())
            ->withMessage(
                text: $message ?: I18n::translate('hksagentur.webform.status.message.error'),
                type: 'error',
                channel: $form->getKey()
            );
    }

    protected function processedSubmission(Form $form): RedirectResponse
    {
        $url = $this->getRedirectUrl($form);
        $message = $this->getSuccessMessage($form);

        return (new RedirectResponse($url))
            ->withMessage(
                text: $message ?: I18n::translate('hksagentur.webform.status.message.success'),
                type: 'success',
                channel: $form->getKey(),
            );
    }
}
