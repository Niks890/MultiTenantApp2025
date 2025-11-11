<?php

namespace App\Repositories\Contracts;

interface GroupRepositoryInterface
{
    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);

    public function search(string $keyword, $perPage = null);

    public function getAll();
}
