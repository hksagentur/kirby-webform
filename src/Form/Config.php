<?php

namespace Webform\Form;

use Kirby\Cms\App;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\F;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;

class Config
{
    protected string $root;
    protected string $path;
    protected string $file;

    public function __construct(string $path)
    {
        $kirby = App::instance();

        $this->root = $this->normalizeRoot($kirby->root('webforms') ?? $kirby->root('site') . '/forms');
        $this->path = $this->normalizePath($path);
        $this->file = $this->root . '/' . $this->path . '.php';
    }

    public static function create(string $path): static
    {
        return new static($path);
    }

    public function getLabel(): string
    {
        return I18n::translate(
            key: sprintf('hksagentur.webform.form.%s.label', $this->getName()),
            fallback: Str::ucfirst($this->getName())
        );
    }

    public function getName(): string
    {
        return F::name($this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function exists(): bool
    {
        return F::exists($this->file, $this->root);
    }

    public function read(): ?Form
    {
        $form = F::load($this->file, allowOutput: false);

        if (! ($form instanceof Form)) {
            return null;
        }

        return $form->config($this);
    }

    public function readOrFail(): Form
    {
        $form = $this->read();

        if (is_null($form)) {
            throw new NotFoundException([
                'key' => 'hksagentur.webform.configNotFound',
                'data' => ['path' => $this->path],
            ]);
        }

        return $form;
    }

    protected function normalizeRoot(string $path): string
    {
        return Str::rtrim($path, '/');
    }

    protected function normalizePath(string $path): string
    {
        return Str::ltrim(F::dirname($path) . '/' . F::name($path), './');
    }
}
