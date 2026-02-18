<?php

namespace Webform\Form;

use InvalidArgumentException;
use Kirby\Cms\App;
use Kirby\Cms\Block;
use Kirby\Cms\Page;
use Webform\Session\TransientData;

class Manager
{
    protected static ?Manager $instance = null;
    protected static ?TransientData $transient = null;

    /** @var array<string, array<string, Block>> */
    protected array $blocks = [];

    /** @var array<string, Form> */
    protected array $forms = [];

    /** @var array<string, StatusMessage> */
    protected array $messages = [];

    /** @var array<string, MessageBag> */
    protected array $errors = [];

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ?? new static();
    }

    public function transient(): TransientData
    {
        return static::$transient ??= TransientData::instance();
    }

    public function block(Page $page, string $key): ?Block
    {
        return $this->blocks[$page->id()][$key] ??= $this->findBlock($page, $key);
    }

    public function form(string $path): Form
    {
        return $this->forms[$path] ??= Form::from($path);
    }

    public function status(string $messageBag = 'default'): ?StatusMessage
    {
        return $this->messages[$messageBag] ??= StatusMessage::tryFrom(
            $this->transient()->get("webform.form.status.{$messageBag}")
        );
    }

    public function errors(string $errorBag = 'default'): MessageBag
    {
        return $this->errors[$errorBag] ??= MessageBag::fromArray(
            $this->transient()->get("webform.form.errors.{$errorBag}", [])
        );
    }

    protected function findBlock(Page $page, string $key): ?Block
    {
        try {
            return $page->content()->get(
                App::instance()->option('hksagentur.webform.referrer.blocks', 'blocks')
            )->toBlocks()->findByKey($key);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
