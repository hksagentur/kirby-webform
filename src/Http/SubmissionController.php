<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Cms\Block;
use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Toolkit\I18n;
use Throwable;
use Webform\Cms\FormBlock;
use Webform\Exception\FileUploadException;
use Webform\Exception\ValidationException;
use Webform\Form\Form;
use Webform\Form\Manager;
use Webform\Form\MessageBag;

class SubmissionController
{
    public function __invoke(Form $form): RedirectResponse
    {
        $page = $this->getReferrerPage() ?? $this->getPreviousPage();

        if ($page instanceof Page) {
            $form->model($page);
        }

        $block = $this->getParentBlock($page);

        if ($block instanceof Block) {
            $form->block($block);
        }

        if ($block instanceof FormBlock) {
            $form->action($block->action());
        }

        try {
            $form->validate();
        } catch (ValidationException $exception) {
            return $this->failedValidation($exception);
        }

        try {
            $form->submit();
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

    protected function getParentBlock(?Page $page): ?Block
    {
        $key = App::instance()->request()->get('_webform_block');

        if (! $key || ! $page) {
            return null;
        }

        return Manager::instance()->block($page, $key);
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
