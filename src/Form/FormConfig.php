<?php

namespace Webform\Form;

use JsonSerializable;
use Kirby\Cms\App;
use Kirby\Data\Json;
use Kirby\Data\PHP;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;

class FormConfig implements JsonSerializable
{
    public static App $kirby;

    protected string $path;
    protected string $id;
    protected string $root;
    protected string $file;
    protected ?array $data;

    public function __construct(string $path, string $root)
    {
        $this->id = basename($path);

        $this->path = $path;
        $this->root = $root;

        $this->file = $this->root . '/' . $path . '.php';
    }

    public function kirby(): App
    {
        return static::$kirby ??= App::instance();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function root(): string
    {
        return $this->root;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function data(): array
    {
        return $this->data ??= $this->read();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return A::get($this->data(), $key, $default);
    }

    public function label(): string
    {
        return $this->get('label', $this->id);
    }

    public function fields(): array
    {
        return $this->get('fields', []);
    }

    public function emailOption(string $key, mixed $default = null): mixed
    {
        $value = $this->get("email.{$key}");

        if ($value) {
            return $value;
        }

        $preset = $this->get('email.preset');

        if ($preset) {
            return $this->kirby()->option("email.presets.{$preset}.{$key}", $default);
        }

        return $default;
    }

    public function emailPreset(): ?string
    {
        return $this->emailOption('preset');
    }

    public function emailTemplate(): ?string
    {
        return $this->emailOption('template');
    }

    public function emailSender(): ?string
    {
        return $this->emailOption('from');
    }

    public function emailRecipient(): ?string
    {
        return $this->emailOption('to');
    }

    public function emailSubject(): ?string
    {
        return $this->emailOption('subject', $this->label());
    }

    public function webhookOption(string $key, mixed $default = null): mixed
    {
        return $this->get("webhook.{$key}", $default);
    }

    public function webhookType(): ?string
    {
        return $this->webhookOption('type');
    }

    public function webhookUrl(): ?string
    {
        return $this->webhookOption('url');
    }

	public function read(): array
	{
        $kirby = App::instance();

		if (F::exists($this->file, $this->root) === true) {
			return $this->unpack($this->file);
		}

		return $this->unpack($kirby->extension('thirdParty', 'webform'));
	}

    public function unpack(string|callable|null $extension): array
	{
		return match (true) {
            is_callable($extension) => $extension($this->kirby(), $this->path),
            is_string($extension) => PHP::read($extension),
			default => throw new NotFoundException('"' . $this->path . '" could not be found'),
		};
	}

    public function toArray(): array
    {
        return $this->data();
    }

    public function toJson(): string
    {
        return Json::encode($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
