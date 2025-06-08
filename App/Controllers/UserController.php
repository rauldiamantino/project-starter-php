<?php

namespace App\Controllers;

use Core\Library\Twig;
use Core\Library\Logger;
use Core\Library\Response;
use Core\Library\Controller;
use App\Services\UserService;
use App\Request\UserEditFormRequest;
use App\Request\UserCreateFormRequest;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\EmailAlreadyExistsException;

class UserController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private UserService $userService,
    ) {
        parent::__construct($twig, folderView: 'User');
    }

    public function index(): Response
    {
        $users = $this->userService->findAllUsers();
        return $this->render('index.twig', ['users' => $users]);
    }

    public function edit(int $id): Response
    {
        $user = $this->userService->getUserById($id);
        return $this->render('edit.twig', ['user' => $user]);
    }

    public function update(int $id): Response
    {
        $formRequest = UserEditFormRequest::validate($this->request);

        if ($formRequest === null) {
            return $this->redirect('/users/' . $id);
        }

        try {
            $userData = $formRequest->validated()->all();
            $this->userService->editUser($id, $userData);
            return $this->redirect('/users/' . $id, 'success', 'Updated successfully!');
        } catch (CompanyNotExistsException | EmailAlreadyExistsException $e) {
            return $this->redirect('/users/' . $id, 'error', $e->getMessage());
        }
    }

    public function create(): Response
    {
        return $this->render('create.twig');
    }

    public function store(): Response
    {
        $formRequest = UserCreateFormRequest::validate($this->request);

        if ($formRequest === null) {
            return $this->redirect('/users/create');
        }

        try {
            $userData = $formRequest->validated()->all();
            $this->userService->createUser($userData);
            return $this->redirect('/users', 'success', 'Created successfully!');
        } catch (EmailAlreadyExistsException $e) {
            return $this->redirect('/users/create', 'error', $e->getMessage());
        }
    }

    public function delete(int $id): Response
    {
        $this->userService->deleteUserById($id);
        return $this->redirect('/users', 'success', 'Deleted successfully');
    }
}
