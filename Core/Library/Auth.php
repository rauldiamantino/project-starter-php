<?php

namespace Core\Library;

use Core\Library\Session;
use Core\Dbal\Exceptions\EntityNotFoundException;
use App\Database\Repositories\Interfaces\UserRepositoryInterface;

class Auth
{
    public function __construct(private UserRepositoryInterface $userRepositoryInterface)
    {
    }

    public function attempt(array $data): bool
    {
        /** @var UserEntity $user */
        $user = $this->userRepositoryInterface->auth($data['email']);

        if ($user instanceof EntityNotFoundException) {
            return false;
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            return false;
        }

        Session::set('auth', $user);

        return true;
    }

    public function logout()
    {
        Session::remove_session('auth');
    }
}
