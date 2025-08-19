<?php

namespace Webform\Cms\Concerns;

use InvalidArgumentException;
use Kirby\Toolkit\Str;
use Webform\Form\Actions\Action;
use Webform\Form\Actions\Database;
use Webform\Form\Actions\Email;
use Webform\Form\Actions\Webhook;
use Webform\Form\Form;
use Webform\Form\Manager;

trait HasForm
{
    protected ?Form $form = null;
    protected ?Action $action = null;

    public function formId(): ?string
    {
        return $this->content()->form()->value();
    }

    public function actionType(): ?string
    {
        return $this->content()->action()->value();
    }

    public function form(): Form
    {
        return $this->form ??= $this->createForm($this->formId());
    }

    public function action(): Action
    {
        return $this->action ??= $this->createAction($this->actionType());
    }

    protected function createForm(string $id): Form
    {
        $method = 'create'.Str::camel($id).'Form';

        if (! method_exists($this, $method)) {
            return Manager::instance()
                ->form($id)
                ->actions([$this->action()]);
        }

        $form = $this->{$method}();

        if (! ($form instanceof Form)) {
            throw new InvalidArgumentException('Unexpected form type: '.$form::class);
        }

        return $form;
    }

    protected function createAction(string $type): Action
    {
        $method = 'create'.Str::camel($type).'Action';

        if (! method_exists($this, $method)) {
            throw new InvalidArgumentException("Invalid action type [$type].");
        }

        $action = $this->{$method}();

        if (! ($action instanceof Action)) {
            throw new InvalidArgumentException('Unexpected action type: '.$action::class);
        }

        return $action;
    }

    protected function createDatabaseAction(): Database
    {
        $action = new Database(table: $this->content()->databaseTable()->value());

        return $action;
    }

    protected function createWebhookAction(): Webhook
    {
        $action = new Webhook(url: $this->content()->webhookUrl()->value());

        return $action;
    }

    protected function createEmailAction(): Email
    {
        $action = new Email(preset: $this->formId());

        if ($this->content()->emailSubject()->isNotEmpty()) {
            $action->subject($this->content()->emailSubject()->value());
        }

        if ($this->content()->emailFrom()->isNotEmpty()) {
            $action->to($this->content()->emailFrom()->value());
        }

        if ($this->content()->emailReplyTo()->isNotEmpty()) {
            $action->to($this->content()->emailReplyTo()->value());
        }

        return $action;
    }
}
