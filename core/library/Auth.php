<?php

namespace Core\Library;

use Core\Library\Session;
use Core\Dbal\Exceptions\EntityNotFound;
use App\Database\Repositories\UserRepository;

class Auth
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function attempt(array $data)
    {
        /** @var UserEntity $user */
        $user = $this->userRepository->auth($data['email']);

        if ($user instanceof EntityNotFound) {
            return false;
        }

        if (!password_verify($data['password'], $user->password)) {
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
