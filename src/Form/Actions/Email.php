<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Cms\App;
use Kirby\Toolkit\Str;
use Webform\Form\FormSubmission;
use Webform\Support\A;

class Email extends Action
{
    protected string|array|null|Closure $preset = null;
    protected string|array|null|Closure $to = null;

    protected string|null|Closure $template = 'webform/submission';

    protected string|null|Closure $subject = null;
    protected string|null|Closure $from = null;
    protected string|null|Closure $replyTo = null;

    public function __construct(string|array|Closure|null $preset = null)
    {
        $this->preset($preset);
    }

    public static function create(string|array|Closure|null $preset = null): static
    {
        return new static($preset);
    }

    public function getPreset(): array
    {
        $preset = $this->evaluate($this->preset);

        if (is_string($preset)) {
            return App::instance()->option("email.presets.{$preset}", []);
        }

        return A::wrap($preset);
    }

    public function preset(string|Closure|null $preset): static
    {
        $this->preset = $preset;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->evaluate($this->template);
    }

    public function template(string|Closure $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->evaluate($this->subject);
    }

    public function subject(string|Closure $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->evaluate($this->from);
    }

    public function sender(string|Closure $address): static
    {
        $this->from = $address;

        return $this;
    }

    public function from(string|Closure $address): static
    {
        return $this->sender($address);
    }

    public function getRecipients(): array
    {
        $recipients = $this->evaluate($this->to);

        if (is_string($recipients)) {
            return Str::split($recipients, ',');
        }

        return A::wrap($recipients);
    }

    public function getRecipient(): ?string
    {
        return A::first($this->getRecipients());
    }

    public function recipient(string|array|Closure $address): static
    {
        $this->to = $address;

        return $this;
    }

    public function to(string|array|Closure $address): static
    {
        return $this->recipient($address);
    }

    public function getReplyTo(): ?string
    {
        return $this->evaluate($this->replyTo);
    }

    public function replyTo(string|Closure $address): static
    {
        $this->replyTo = $address;

        return $this;
    }

    public function execute(FormSubmission $submission): void
    {
        $kirby = App::instance();
        $site = App::instance()->site();
        $user = App::instance()->user();

        $form = $this->getForm();
        $preset = $this->getPreset();
        $template = $this->getTemplate();

        $data = $submission->all();
        $attachments = A::collapse($submission->filePaths());

        $subject = Str::template($this->getSubject(), $data);
        $sender = Str::template($this->getSender(), $data);
        $replyTo = Str::template($this->getReplyTo(), $data);

        $recipients = A::map($this->getRecipients(), function ($recipient) use ($data) {
            return Str::template($recipient, $data);
        });

        $preset = $this->applyFilters('email:before', [
            'preset' => [
                'data' => [
                    'kirby' => $kirby,
                    'site' => $site,
                    'user' => $user,
                    'form' => $form,
                    'data' => $data,
                ],
                ...$preset,
                ...$template ? ['template' => $template] : [],
                ...$subject ? ['subject' => $subject] : [],
                ...$sender ? ['from' => $sender] : [],
                ...$recipients ? ['to' => $recipients] : [],
                ...$replyTo ? ['replyTo' => $replyTo] : [],
                ...$attachments ? ['attachments' => $attachments] : [],
            ],
        ], 'preset');

        $email = $kirby->email($preset);

        $this->fireEvent('email:after', [
            'preset' => $preset,
            'email' => $email,
        ]);
    }
}
