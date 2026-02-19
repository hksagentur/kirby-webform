<?php

namespace Webform\Http;

use Kirby\Cms\App;
use Kirby\Cms\Block;
use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Http\Request;
use Kirby\Toolkit\I18n;
use Throwable;
use Webform\Cms\FormBlock;
use Webform\Exception\FileUploadException;
use Webform\Exception\ValidationException;
use Webform\Form\Form;
use Webform\Form\MessageBag;

class SubmissionController
{
    public function __invoke(Request $request, Form $form): RedirectResponse
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
            $input = $form->validate();
        } catch (ValidationException $exception) {
            return $this->failedValidation($form, $exception);
        }

        try {
            $form->submit($input);
        } catch (Throwable $exception) {
            return $this->failedSubmission($form, $exception);
        }

        return $this->processedSubmission($form);
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
        $id = App::instance()->request()->get('_webform_block');

        if (! $id || ! $page) {
            return null;
        }

        return $page->block($id);
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

    protected function failedValidation(Form $form, Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withInput($form->getInput(), $form->getKey())
            ->withErrors($this->asMessageBag($exception), $form->getKey());
    }

    protected function failedSubmission(Form $form, Throwable $exception): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withInput($form->getInput(), $form->getKey())
            ->withErrors($this->asMessageBag($exception), $form->getKey());
    }

    protected function processedSubmission(Form $form): RedirectResponse
    {
        return (new RedirectResponse($this->getRedirectUrl()))
            ->withStatus(I18n::translate('hksagentur.webform.status.message.success'), $form->getKey());
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
