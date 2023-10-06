<?php

namespace Application\Controller;

use Laminas\Session;

class LogoutController extends AbstractController
{

    public function __construct()
    { }

    public function indexAction()
    {
        $session = new Session\Container('user');
        $session->getManager()->destroy();
        // $session->getManager()->getStorage()->clear('user');
        // unset($_SESSION['user']); 
        // $this->sessionUser = null;
        $this->redirect()->toRoute('login');
    }
}
