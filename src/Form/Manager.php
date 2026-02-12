<?php

namespace Webform\Form;

use InvalidArgumentException;
use Kirby\Cms\App;
use Kirby\Cms\Block;
use Kirby\Cms\Page;
use Webform\Form\Form;
use Webform\Session\TransientData;

class Manager
{
    protected static ?self $instance = null;

    /** @var array<string, array<string, Block>> */
    protected array $blocks = [];

    /** @var array<string, Form> */
    protected array $forms = [];

    protected ?MessageBag $messageBag = null;
    protected ?TransientData $transientData = null;

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ?? new static();
    }

    public function form(string $path): Form
    {
        return $this->forms[$path] ??= Form::from($path);
    }

    public function block(Page $page, string $key): ?Block
    {
        return $this->blocks[$page->id()][$key] ??= $this->findBlock($page, $key);
    }

    public function transient(): TransientData
    {
        return $this->transientData ??= TransientData::instance();
    }

    public function status(): ?StatusMessage
    {
        $message = $this->transient()->get('webform.form.status');

        if (! $message) {
            return null;
        }

        return new StatusMessage($message);
    }

    public function errors(): MessageBag
    {
        return $this->messageBag ??= new MessageBag(
            $this->transient()->get('webform.form.errors', [])
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
