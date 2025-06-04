<?php

namespace app\controllers;

use core\library\Auth;
use core\library\Twig;
use core\library\Response;
use core\library\Controller;
use app\request\LoginFormRequest;

class LoginController extends Controller
{
  public function __construct(Twig $twig, private Auth $auth)
  {
    parent::__construct($twig);
  }

  public function index(): Response
  {
    return $this->render('login/index.twig');
  }

  public function store(): Response
  {
    $validated = LoginFormRequest::validate($this->request);

    if (! $validated) {
      return redirect('/login');
    }

    $request = $this->request->getRequest('post');

    $auth = $this->auth->attempt($request->all());

    if (! $auth) {
      return redirect('/login')->withMessage('error', 'Email or password incorrect');
    }

    return redirect('/');
  }

  public function destroy():Response
  {
    $this->auth->logout();

    return redirect('/');
  }
}
