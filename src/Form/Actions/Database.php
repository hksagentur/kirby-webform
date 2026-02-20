<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Database\Db;
use Webform\Form\FormSubmission;
use Webform\Form\ValidatedInput;

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

    public function getTable(): ?string
    {
        return $this->evaluate($this->table);
    }

    public function table(string|Closure $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function execute(FormSubmission $submission): void
    {
        $row = $this->applyFilters('save:before', [
            'row' => $submission->all(),
        ], 'row');

        DB::table($this->getTable())->insert($row);

        $this->fireEvent('save:after', [
            'row' => $row,
        ]);
    }
}
