<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Database\Db;
use Webform\Form\FormSubmission;

class Database extends Action
{
    protected string|Closure $table;

    public function __construct(string|Closure $table)
    {
        $this->table($table);
    }

    public static function create(string|Closure $table): static
    {
        return new static($table);
    }

    public static function handle(FormSubmission $submission, mixed ...$arguments): mixed
    {
        return static::create(...$arguments)->execute($submission);
    }

    public function getTable(): ?string
    {
        return $this->evaluate($this->table);
    }

    public function table(string|Closure $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function execute(FormSubmission $submission): mixed
    {
        $row = $this->apply('save:before', [
            'row' => $submission->all(),
        ], 'row');

        $result = DB::table($this->getTable())->insert($row);

        $this->dispatch('save:after', [
            'row' => $row,
        ]);

        return $result;
    }
}
