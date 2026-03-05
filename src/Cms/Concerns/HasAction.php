<?php

namespace Webform\Cms\Concerns;

use Kirby\Content\Content;
use Kirby\Toolkit\Str;
use UnexpectedValueException;
use Webform\Form\Actions\Action;
use Webform\Form\Actions\Database;
use Webform\Form\Actions\Email;
use Webform\Form\Actions\Webhook;

/**
 * @method Content content(string|null $languageCode = null)
 */
trait HasAction
{
    protected ?Action $action = null;

    public function action(): Action
    {
        return $this->action ??= $this->createAction($this->content()->action()->value());
    }

    protected function createAction(string $type): Action
    {
        $method = 'create'.Str::studly($type).'Action';

        if (! method_exists($this, $method)) {
            throw new UnexpectedValueException(sprintf(
                'Invalid action type: %s',
                $type
            ));
        }

        $action = $this->{$method}();

        if (! ($action instanceof Action)) {
            throw new UnexpectedValueException(sprintf(
                'Unexpected action type: %s',
                $action::class
            ));
        }

        return $action;
    }

    protected function createDatabaseAction(): Database
    {
        return new Database(
            table: $this->content()->databaseTable()->value()
        );
    }

    protected function createWebhookAction(): Webhook
    {
        return new Webhook(
            url: $this->content()->webhookUrl()->value()
        );
    }

    protected function createEmailAction(): Email
    {
        $action = new Email(preset: $this->content()->form()->value() ?: null);

        if ($this->content()->emailSubject()->isNotEmpty()) {
            $action->subject($this->content()->emailSubject()->value());
        }

        if ($this->content()->emailFrom()->isNotEmpty()) {
            $action->from($this->content()->emailFrom()->value());
        }

        if ($this->content()->emailTo()->isNotEmpty()) {
            $action->to($this->content()->emailTo()->value());
        }

        if ($this->content()->emailReplyTo()->isNotEmpty()) {
            $action->replyTo($this->content()->emailReplyTo()->value());
        }

        return $action;
    }
}
