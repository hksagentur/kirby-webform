<?php

namespace Webform\Form\Actions;

use Exception;
use Kirby\Database\Db;
use Kirby\Toolkit\A;
use Uniform\Actions\Action;

class DatabaseAction extends Action
{
    public function perform()
    {
        $table = $this->requireOption('table');

        $data = $this->option('data', []);
        $ignore = $this->option('except', []);

        $submission = $this->form->data(escape: false);

        $row = $this->transformRow(
            A::without(A::merge($submission, $data), $ignore)
        );

        try {
            Db::table($table)->insert($row);
        } catch (Exception $exception) {
            $this->fail($exception->getMessage());
        }
    }

    protected function transformRow(array $row): array
    {
        return $row;
    }
}
