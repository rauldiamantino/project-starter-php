<?php

namespace App\Controllers;

use Throwable;
use Core\Library\Twig;
use Core\Library\Logger;
use Core\Library\Response;
use Core\Library\Controller;

use App\Services\CompanyService;
use App\Request\CompanyCreateFormRequest;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\NameAlreadyExistsException;
use App\Database\Repositories\CompanyRepository;
use App\Exceptions\CompanyHasDependentsException;

class CompanyController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private CompanyRepository $companyRepository,
        private CompanyService $companyService,
    ) {
        parent::__construct($twig, folderView: 'Company');
    }

    public function index(): Response
    {
        try {
            $companies = $this->companyRepository->findAll();

            return $this->render('index.twig', ['companies' => $companies]);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyController::index: ' . $e->getMessage());
            return $this->redirect('/', 'error', 'An unexpected error occurred while loading companies.');
        }
    }

    public function show(int $id)
    {
        try {
            $company = $this->companyRepository->getCompanyById($id);

            return $this->render('show.twig', ['company' => $company]);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyController::show: ' . $e->getMessage());
            return $this->redirect('/', 'error', 'An unexptected error occurred while loading company.');
        }
    }

    public function create()
    {
        return $this->render('create.twig');
    }

    public function store()
    {
        if (!CompanyCreateFormRequest::validate($this->request)) {
            return $this->redirect('/companies/create');
        }

        try {
            $request = $this->request->getRequest('post')->all();

            $this->companyService->createCompany($request);

            return $this->redirect('/companies', 'success', 'Created successfully!');
        } catch (NameAlreadyExistsException | CnpjAlreadyExistsException $e) {
            return $this->redirect('/companies/create', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyController::store: ' . $e->getMessage());

            return $this->redirect('/companies', 'error', 'An unexpected error occurred during company creation.');
        }
    }

    public function delete(int $id)
    {
        try {
            $this->companyService->deleteCompany($id);

            return $this->redirect('/companies', 'success', 'Deleted successfully!');
        } catch (CompanyNotExistsException $e) {
            return $this->redirect('/companies', 'error', $e->getMessage());
        } catch (CompanyHasDependentsException $e) {
            return $this->redirect('/companies', 'error', $e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyController::delete: ' . $e->getMessage());
            return $this->redirect('/companies', 'error', 'An unexpected error occurred while deleting the company.');
        }
    }
}
