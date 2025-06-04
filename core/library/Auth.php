<?php

namespace core\library;

use core\library\Session;
use core\dbal\exceptions\EntityNotFound;
use app\database\repositories\UserRepository;

class Auth
{
  public function __construct(private UserRepository $userRepository) {}

  public function attempt(array $data)
  {
    /** @var UserEntity $user */
    $user = $this->userRepository->auth($data['email']);

    if ($user instanceof EntityNotFound) {
      return false;
    }

    if (! password_verify($data['password'], $user->password)) {
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
