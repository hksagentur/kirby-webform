<?php

namespace Webform\Http\Controller;

use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Toolkit\I18n;
use Throwable;
use Webform\Form\Form;
use Webform\Http\Exception\FileUploadException;
use Webform\Http\RedirectResponse;
use Webform\Validation\Messages;
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

    protected function failedValidation(Form $form, Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl($form)))
            ->withInput(channel: $form->getKey())
            ->withErrors(messages: $this->asMessageCollection($exception), channel: $form->getKey());
    }

    protected function failedSubmission(Form $form, Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl($form)))
            ->withInput(channel: $form->getKey())
            ->withErrors(messages: $this->asMessageCollection($exception), channel: $form->getKey());
    }

    protected function processedSubmission(Form $form): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl($form)))->withStatus(
            message: I18n::translate('hksagentur.webform.status.message.success'),
            channel: $form->getKey(),
        );
    }

    protected function asMessageCollection(Throwable $exception): Messages
    {
        return match (true) {
            $exception instanceof ValidationException => $exception->getErrors(),
            $exception instanceof FileUploadException => $exception->getUploadErrors(),
            default => Messages::from(['error' => $exception->getMessage()]),
        };
    }
}
