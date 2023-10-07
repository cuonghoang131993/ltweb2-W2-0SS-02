<?php

namespace Application\Controller;

use Laminas\Session;

class LogoutController extends AbstractController
{

    public function __construct()
    { }

    public function indexAction()
    {
        try {
            //code...
            $session = new Session\Container('user');
            $session->getManager()->destroy();    
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        $this->redirect()->toRoute('login');
    }
}
