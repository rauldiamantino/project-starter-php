<?php

namespace App\Controllers;

use Core\Library\Twig;
use Core\Library\Logger;
use Core\Library\Response;
use Core\Library\Controller;
use Core\Dbal\Exceptions\EntityNotFoundException;
use Throwable;

use App\Services\UserService;
use App\Request\UserCreateFormRequest;
use App\Database\Repositories\UserRepository;
use App\Exceptions\EmailAlreadyExistsException;

class UserController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private UserRepository $userRepository,
        private UserService $userService,
    ) {
        parent::__construct($twig, folderView: 'User');
    }

    public function index(): Response
    {
        try {
            $users = $this->userRepository->findAll();

            return $this->render('index.twig', ['users' => $users]);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::index: ' . $e->getMessage());

            return $this->redirect('/', 'error', 'An unexpected error occurred while loading users.');
        }
    }

    public function show(int $id): Response
    {
        try {
            $user = $this->userRepository->getUserById($id);

            return $this->render('show.twig', ['user' => $user]);
        } catch (EntityNotFoundException $e) {
            return $this->redirect('/users', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::show: ' . $e->getMessage());

            return $this->redirect('/users', 'error', 'An unexpected error occurred');
        }
    }

    public function create(): Response
    {
        return $this->render('create.twig');
    }

    public function store(): Response
    {
        if (!UserCreateFormRequest::validate($this->request)) {
            return $this->redirect('/users/create');
        }

        try {
            $request = $this->request->getRequest('post')->all();

            $this->userService->createUser($request);

            return $this->redirect('/users', 'success', 'Created successfully!');
        } catch (EmailAlreadyExistsException $e) {
            return $this->redirect('/users/create', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::store: ' . $e->getMessage());

            return $this->redirect('/users', 'error', 'An unexpected error occurred during user creation.');
        }
    }

    public function delete(int $id): Response
    {
        try {
            $user = $this->userRepository->getUserById($id);
            $this->userRepository->delete($user);

            return $this->redirect('/users', 'success', 'Deleted successfully');
        } catch (EntityNotFoundException $e) {
            return $this->redirect('/users', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::delete: ' . $e->getMessage());

            return $this->redirect('/users', 'error', 'An unexpected error occurred during user deletion.');
        }
    }
}
