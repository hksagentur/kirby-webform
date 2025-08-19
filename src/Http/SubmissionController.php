<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Toolkit\I18n;
use Throwable;
use Webform\Exception\FileUploadException;
use Webform\Exception\ValidationException;
use Webform\Form\Form;
use Webform\Form\MessageBag;

class SubmissionController
{
    public function __invoke(Form $form): RedirectResponse
    {
        $referrer = $this->getReferrerPage() ?? $this->getPreviousPage();

        try {
            $form->validate();
        } catch (ValidationException $exception) {
            return $this->failedValidation($exception);
        }

        try {
            $form->submit($referrer);
        } catch (Throwable $exception) {
            return $this->failedSubmission($exception);
        }

        return $this->processedSubmission();
    }

    protected function getReferrerPage(): ?Page
    {
        return App::instance()->site()->find(
            App::instance()->request()->get('_webform_referrer')
        );
    }

    protected function getPreviousPage(): ?Page
    {
        return App::instance()->site()->find(
            App::instance()->session()->get('webform.page.previous')
        );
    }

    protected function getRedirectUrl(): string
    {
        $id = App::instance()->request()->get('_webform_id');

        if ($referrerPage = $this->getReferrerPage()) {
            return $referrerPage->url().($id ? "#{$id}" : '');
        }

        if ($previousPage = $this->getPreviousPage()) {
            return $previousPage->url().($id ? "#{$id}" : '');
        }

        return Url::home();
    }

    protected function failedValidation(Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withInput()
            ->withErrors($this->asMessageBag($exception));
    }

    protected function failedSubmission(Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withInput()
            ->withErrors($this->asMessageBag($exception));
    }

    protected function processedSubmission(): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withStatus(I18n::translate('hksagentur.webform.status.message.success'));
    }

    protected function asMessageBag(Throwable $exception): MessageBag
    {
        return match (true) {
            $exception instanceof ValidationException => $exception->getErrors(),
            $exception instanceof FileUploadException => $exception->getErrors(),
            default => MessageBag::fromString($exception->getMessage()),
        };
    }
}
