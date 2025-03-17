<?php

namespace Webform\Form;

use JsonSerializable;
use Kirby\Cms\App;
use Kirby\Data\Json;
use Kirby\Data\PHP;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;

class Config implements JsonSerializable
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

    public function name(): string
    {
        return $this->get('name');
    }

    public function actions(): array
    {
        return $this->get('actions', []);
    }

    public function guards(): array
    {
        return $this->get('guards', []);
    }

    public function fields(): array
    {
        return $this->get('fields', []);
    }

	public function read(): array
	{
		if (F::exists($this->file, $this->root)) {
            return $this->unpack($this->file);
		}

		return $this->unpack($this->kirby()->extension('thirdParty', 'webform'));
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
