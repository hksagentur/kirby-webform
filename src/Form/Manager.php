<?php

namespace Webform\Form;

use Kirby\Cms\App;
use Kirby\Http\Request;
use Uniform\Form;
use Webform\Toolkit\Str;

class Manager
{
    protected static ?self $instance = null;
    protected static ?App $kirby = null;

    protected array $cache = [];

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ?? new static();
    }

    public function kirby(): App
    {
        return static::$kirby ??= App::instance();
    }

    public function request(): Request
    {
        return $this->kirby()->request();
    }

    public function root(): string
    {
        return $this->kirby()->root('webforms') ?? $this->kirby()->root('site') . '/forms';
    }

    public function config(string $path): Config
    {
        return $this->cache[$path] ??= new Config($path, $this->root());
    }

    public function form(string $path): Form
    {
        $config = $this->config($path);

        $form = new Form(
            rules: $config->fields(),
            sessionKey: $config->id()
        );

        if ($this->request()->is('POST')) {
            $this
                ->registerGuards($form, $config)
                ->registerActions($form, $config);

            $form->done();
        }

        return $form;
    }

    protected function registerActions(Form $form, Config $config): self
    {
        foreach ($config->actions() as $alias => $className) {
            if (is_numeric($alias)) {
                $alias = $this->guessActionAlias($className);
            }

            $form->action(
                action: $className,
                options: $this->kirby()->apply("webform.{$alias}:before", [
                    'form' => $form,
                    'options' => $config->get($alias, []),
                ], 'options'),
            );
        }

        return $this;
    }

    protected function registerGuards(Form $form, Config $config): self
    {
        foreach ($config->guards() as $alias => $className) {
            if (is_numeric($alias)) {
                $alias = $this->guessGuardAlias($className);
            }

            $form->guard(
                guard: $className,
                options: $this->kirby()->apply("webform.{$alias}:before", [
                    'form' => $form,
                    'options' => $config->get($alias, []),
                ], 'options'),
            );
        }

        return $this;
    }

    protected function guessActionAlias(string $className): string
    {
        return Str::snake(Str::beforeEnd(Str::classBasename($className), 'Action'));
    }

    protected function guessGuardAlias(string $className): string
    {
        return Str::snake(Str::beforeEnd(Str::classBasename($className), 'Guard'));
    }
}
