<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Cms\App;
use Kirby\Filesystem\File;
use Kirby\Toolkit\Str;
use Webform\Form\FormSubmission;
use Webform\Toolkit\A;
use Webform\Template\SubmissionEmail;

class Email extends Action
{
    protected string|array|null|Closure $preset = null;
    protected string|array|null|Closure $to = null;

    protected string|null|Closure $template = 'webform/submission';

    protected string|null|Closure $subject = null;
    protected string|null|Closure $from = null;
    protected string|null|Closure $replyTo = null;

    protected string|Closure $directory = 'storage/uploads';

    public function __construct(string|array|Closure|null $preset = null)
    {
        $this->preset($preset);
    }

    public static function create(string|array|Closure|null $preset = null): static
    {
        return new static($preset);
    }

    public static function handle(FormSubmission $submission, mixed ...$arguments): mixed
    {
        return static::create(...$arguments)->execute($submission);
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

    public function getAttachmentDirectory(): string
    {
        return $this->evaluate($this->directory);
    }

    public function storeAttachmentsIn(string|Closure $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function execute(FormSubmission $submission): mixed
    {
        $kirby = App::instance();

        $preset = $this->getPreset();
        $template = $this->getTemplate();

        $options = $this->prepareEmailOptions($submission);
        $attachments = $this->prepareEmailAttachments($submission);

        $preset = $this->apply('email:before', [
            'preset' => [
                'data' => [
                    'kirby' => $kirby,
                    'site' => $kirby->site(),
                    'user' => $kirby->user(),
                    ...SubmissionEmail::create($submission),
                ],
                ...$preset,
                ...$options,
                ...$template ? ['template' => $template] : [],
                ...$attachments ? ['attachments' => $attachments] : [],
            ],
        ], 'preset');

        $email = $kirby->email($preset);

        $this->dispatch('email:after', [
            'preset' => $preset,
            'email' => $email,
        ]);

        return $email->isSent();
    }

    protected function prepareEmailOptions(FormSubmission $submission): array
    {
        $format = fn (?string $value): string => Str::template($value, $submission->all());

        $options = A::map([
            'subject' => $this->getSubject(),
            'from' => $this->getSender(),
            'to' => $this->getRecipients(),
            'replyTo' => $this->getReplyTo(),
        ], fn (string|array|null $option) => match (true) {
            is_array($option) => A::map($option, $format),
            is_string($option) => $format($option),
            default => null,
        });

        return $options;
    }

    protected function prepareEmailAttachments(FormSubmission $submission): array
    {
        $files = $submission->storeFilesIn($this->getAttachmentDirectory());

        $files = A::collapse(A::map($files, function (array $files) {
            return A::map($files, fn (File $file) => $file->root());
        }));

        return $files;
    }
}
