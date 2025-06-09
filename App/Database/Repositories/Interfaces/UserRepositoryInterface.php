<?php 

namespace App\Database\Repositories\Interfaces;

use App\Database\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function createUser(UserEntity $entity): UserEntity;
    public function deleteUser(UserEntity $userEntity): void;
    public function updateUser(UserEntity $entity): void;
    public function findAllUsers(): array;
    public function getUserById(int $id): UserEntity;
    public function findUserById(int $id): ?UserEntity;
    public function emailExists(string $email, ?int $id = null): bool;
    public function auth(string $email): UserEntity;
}