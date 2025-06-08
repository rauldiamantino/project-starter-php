<?php

namespace App\Controllers;

use Core\Library\Controller;
use Core\Library\Response;
use Core\Library\Twig;

class NotFoundController extends Controller
{
    public function __construct(Twig $twig)
    {
        parent::__construct($twig, folderView: 'Error');
    }

    public function index(): Response
    {
        return $this->render('404.twig', [], 404);
    }
}
