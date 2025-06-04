<?php

namespace app\controllers;

use core\library\Controller;
use core\library\Response;
use core\library\Twig;

class NotFoundController extends Controller
{
  public function __construct(Twig $twig)
  {
    parent::__construct($twig);
  }

  public function index():Response
  {
    return $this->render('404.twig', [], 404);
  }
}