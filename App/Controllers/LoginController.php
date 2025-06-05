<?php

namespace App\Controllers;

use Core\Library\Auth;
use Core\Library\Twig;
use Core\Library\Response;
use Core\Library\Controller;
use App\Request\LoginFormRequest;

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

        if (!$validated) {
            return $this->redirect('/login');
        }

        $request = $this->request->getRequest('post');

        $auth = $this->auth->attempt($request->all());

        if (!$auth) {
            return $this->redirect('/login', 'error', 'Email or password incorrect');
        }

        return $this->redirect('/');
    }

    public function destroy(): Response
    {
        $this->auth->logout();

        return $this->redirect('/');
    }
}
