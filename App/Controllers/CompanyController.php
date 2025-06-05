<?php

namespace App\Controllers;

use App\Database\Repositories\CompanyRepository;

use Core\Library\Controller;
use Core\Library\Logger;
use Core\Library\Response;
use Core\Library\Twig;

use Throwable;

class CompanyController extends Controller
{
    public function __construct(
        Twig $twig,
        private Logger $logger,
        private CompanyRepository $companyRespository,
    ) {
        parent::__construct($twig);
    }

    public function index(): Response
    {
        try {
            $companies = $this->companyRespository->getAll();

            return $this->render('companies/index.twig', ['companies' => $companies]);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyController::index: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);

            return $this->redirect('/', 'error', 'An unexpected error occurred while loading companies.');
        }
    }

    public function show()
    {

    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function delete()
    {

    }
}
