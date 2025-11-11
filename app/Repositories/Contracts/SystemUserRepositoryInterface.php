<?php

namespace App\Repositories\Contracts;
interface SystemUserRepositoryInterface
{
    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);

    public function search(string $keyword, string $status = '', $perPage = null);
}
