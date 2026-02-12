<?php

namespace Webform\Cms\Concerns;

use InvalidArgumentException;
use Kirby\Toolkit\Str;
use Webform\Form\Actions\Action;
use Webform\Form\Actions\Database;
use Webform\Form\Actions\Email;
use Webform\Form\Actions\Webhook;

trait HasAction
{
    protected ?Action $action = null;

    public function actionType(): string
    {
        return $this->content()->action()->value();
    }

    public function action(): Action
    {
        return $this->action ??= $this->createAction($this->actionType());
    }

    public function createAction(string $type): Action
    {
        $method = 'create'.Str::camel($type).'Action';

        if (! method_exists($this, $method)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid action type: %s',
                $type
            ));
        }

        $action = $this->{$method}();

        if (! ($action instanceof Action)) {
            throw new InvalidArgumentException(sprintf(
                'Unexpected action type: %s',
                $action::class
            ));
        }

        return $action;
    }

    public function createDatabaseAction(): Database
    {
        return new Database(
            table: $this->content()->databaseTable()->value()
        );
    }

    public function createWebhookAction(): Webhook
    {
        return new Webhook(
            url: $this->content()->webhookUrl()->value()
        );
    }

    public function createEmailAction(): Email
    {
        $action = new Email(preset: $this->formId());

        if ($this->content()->emailSubject()->isNotEmpty()) {
            $action->subject($this->content()->emailSubject()->value());
        }

        if ($this->content()->emailFrom()->isNotEmpty()) {
            $action->from($this->content()->emailFrom()->value());
        }

        if ($this->content()->emailReplyTo()->isNotEmpty()) {
            $action->to($this->content()->emailReplyTo()->value());
        }

        return $action;
    }
}
