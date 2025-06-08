<?php

namespace App\Controllers;

use Core\Library\Twig;
use Core\Library\Logger;
use Core\Library\Response;
use Core\Library\Controller;
use App\Services\CompanyService;
use App\Request\CompanyEditFormRequest;
use App\Request\CompanyCreateFormRequest;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\NameAlreadyExistsException;
use App\Exceptions\CompanyHasDependentsException;

class CompanyController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private CompanyService $companyService,
    ) {
        parent::__construct($twig, folderView: 'Company');
    }

    public function index(): Response
    {
        $companies = $this->companyService->findAllCompanies();
        return $this->render('index.twig', ['companies' => $companies]);
    }

    public function create(): Response
    {
        return $this->render('create.twig');
    }

    public function edit(int $id): Response
    {
        $company = $this->companyService->getCompanyById($id);
        return $this->render('edit.twig', ['company' => $company]);
    }

    public function update(int $id): Response
    {
        $formRequest = CompanyEditFormRequest::validate($this->request);

        if ($formRequest === null) {
            return $this->redirect('/companies/' . $id);
        }

        try {
            $userData = $formRequest->validated()->all();
            $this->companyService->editCompany($id, $userData);
            return $this->redirect('/companies/' . $id, 'success', 'Updated successfully!');
        } catch (NameAlreadyExistsException | CnpjAlreadyExistsException $e) {
            return $this->redirect('/companies/' . $id, 'error', $e->getMessage());
        }
    }

    public function store(): Response
    {
        $formRequest = CompanyCreateFormRequest::validate($this->request);

        if ($formRequest === null) {
            return $this->redirect('/companies/create');
        }

        try {
            $userData = $formRequest->validated()->all();
            $this->companyService->createCompany($userData);
            return $this->redirect('/companies', 'success', 'Created successfully!');
        } catch (NameAlreadyExistsException | CnpjAlreadyExistsException $e) {
            return $this->redirect('/companies/create', 'error', $e->getMessage());
        }
    }

    public function delete(int $id): Response
    {
        try {
            $this->companyService->deleteCompanyById($id);
            return $this->redirect('/companies', 'success', 'Deleted successfully!');
        } catch (CompanyHasDependentsException $e) {
            return $this->redirect('/companies', 'error', $e->getMessage());
        }
    }
}
