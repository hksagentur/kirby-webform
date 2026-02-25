<?php

namespace Webform\Cms\Concerns;

use Kirby\Cms\Block;
use Kirby\Cms\ModelWithContent;
use Kirby\Toolkit\Str;
use UnexpectedValueException;
use Webform\Cms\Contracts\HasActions;
use Webform\Form\Form;
use Webform\Form\FormFactory;

trait HasForm
{
    protected ?Form $form = null;

    public function formId(): string
    {
        return $this->content()->form()->value();
    }

    public function form(): Form
    {
        return $this->form ??= $this->createForm($this->formId());
    }

    public function createForm(string $id): Form
    {
        $method = 'create'.Str::camel($id).'Form';

        if (method_exists($this, $method)) {
            $form = $this->{$method}();
        } else {
            $form = FormFactory::instance()->createFromConfig($id);
        }

        if (! ($form instanceof Form)) {
            throw new UnexpectedValueException(sprintf(
                'Unexpected form type: %s',
                $form::class,
            ));
        }

        if ($this instanceof ModelWithContent) {
            $form->model($this);
        }

        if ($this instanceof Block) {
            $form->block($this);
            $form->model($this->parent());
        }

        if ($this instanceof HasActions) {
            $form->actions($this->actions());
        }

        return $form;
    }
}
