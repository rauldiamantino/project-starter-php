<?php

namespace app\controllers;

use core\library\Twig;
use core\library\Response;
use core\library\Controller;
use app\database\repositories\UserRepository;

class HomeController extends Controller
{
  public function __construct(Twig $twig, private UserRepository $userRepository)
  {
    parent::__construct($twig);
  }

  public function index(): Response
  {
    return $this->render('home/index.twig', ['message' => 'Welcome']);
  }
}