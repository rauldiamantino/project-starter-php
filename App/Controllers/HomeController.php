<?php

namespace App\Controllers;

use Core\Library\Twig;
use Core\Library\Response;
use Core\Library\Controller;

class HomeController extends Controller
{
    public function __construct(Twig $twig)
    {
        parent::__construct($twig, folderView: 'Home');
    }

    public function index(): Response
    {
        return $this->render('index.twig', ['message' => 'Welcome']);
    }
}
