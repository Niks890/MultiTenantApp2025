<?php

namespace App\Repositories\Contracts;
interface AuthRepositoryInterface
{
    public function findByLogin(string $username);
    public function checkCredentials(string $username, string $password, bool $isActive): bool;
}
