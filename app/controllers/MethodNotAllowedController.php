<?php

namespace App\Controllers;

use Core\Library\Controller;
use Core\Library\Response;
use Core\Library\Twig;

class MethodNotAllowedController extends Controller
{
    public function __construct(Twig $twig)
    {
        parent::__construct($twig);
    }

    public function index(): Response
    {
        return $this->render('405.twig', [], 405);
    }
}
