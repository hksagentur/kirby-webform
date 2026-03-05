<?php

namespace Webform\Cms\Concerns;

use Kirby\Cms\App;
use Kirby\Cms\Block;
use Kirby\Cms\Page;
use Kirby\Content\Content;
use Kirby\Toolkit\Str;
use UnexpectedValueException;
use Webform\Form\Form;
use Webform\Form\FormRepository;

/**
 * @method Content content(string|null $languageCode = null)
 */
trait HasForm
{
    protected ?Form $form = null;

    public function form(): Form
    {
        return $this->form ??= $this->createForm($this->content()->form()->value());
    }

    public function createForm(string $id): Form
    {
        $method = 'create'.Str::studly($id).'Form';

        if (method_exists($this, $method)) {
            $form = $this->{$method}();
        } else {
            $form = FormRepository::instance()->getByPath($id);
        }

        if (! ($form instanceof Form)) {
            throw new UnexpectedValueException(sprintf(
                'Unexpected form type: %s',
                $form::class,
            ));
        }

        $form->getContext()->add(match (true) {
            $this instanceof Block => [
                'kirby' => $this->parent()->kirby(),
                'site' => $this->parent()->site(),
                'page' => $this->parent(),
                'block' => $this,
            ],
            $this instanceof Page => [
                'kirby' => $this->kirby(),
                'site' => $this->site(),
                'page' => $this,
            ],
            default => [
                'kirby' => App::instance(),
                'site' => App::instance()->site(),
            ],
        });

        return $form;
    }
}
