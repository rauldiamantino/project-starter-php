<?php

namespace app\controllers;

use Throwable;
use core\library\Twig;
use core\library\Response;
use core\library\Controller;
use app\services\UserService;
use app\request\UserCreateFormRequest;
use core\dbal\exceptions\EntityNotFound;
use app\database\repositories\UserRepository;
use core\library\Logger;

class UserController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private UserRepository $userRepository,
        private UserService $userService,
    ) {
        parent::__construct($twig);
    }

    public function index(): Response
    {
        try {
            $users = $this->userRepository->getAll();

            return $this->render('users/index.twig', ['users' => $users]);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::index: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);

            return $this->redirect('/', 'error', 'An unexpected error occurred while loading users.');
        }
    }

    public function show(int $id): Response
    {
        try {
            $user = $this->userRepository->getUserById($id);

            return $this->render('users/show.twig', ['user' => $user]);
        } catch (EntityNotFound $e) {
            return $this->redirect('/users', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::show: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);

            return $this->redirect('/users', 'error', 'An unexpected error occurred');
        }
    }

    public function create(): Response
    {
        return $this->render('users/create.twig');
    }

    public function store(): Response
    {
        if (!UserCreateFormRequest::validate($this->request)) {
            return $this->redirect('/user/create');
        }

        try {
            $request = $this->request->getRequest('post')->all();

            $this->userService->createUser($request);

            return $this->redirect('/users', 'success', 'Created successfully!');
        } catch (EmailAlreadyExistsException $e) {
            return $this->redirect('/user/create', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::store: ' . $e->getMessage(), [
              'exception' => $e->getTraceAsString(), 'file' => $e->getFile(), 'line' => $e->getLine(),
            ]);

            return $this->redirect('/users', 'error', 'An unexpected error occurred during user creation.');
        }
    }

    public function delete(int $id): Response
    {
        try {
            $user = $this->userRepository->getUserById($id);
            $this->userRepository->delete($user);

            return $this->redirect('/users', 'success', 'Deleted successfully');
        } catch (EntityNotFound $e) {
            return $this->redirect('/users', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in UserController::delete: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);

            return $this->redirect('/users', 'error', 'An unexpected error occurred during user deletion.');
        }
    }
}
