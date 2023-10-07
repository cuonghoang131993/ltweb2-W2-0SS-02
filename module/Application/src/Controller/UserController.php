<?php

namespace Application\Controller;

use Laminas\Session;

class UserController extends AbstractController
{
    public function indexAction()
    {
        try {
            //code...
            $userSession = new Session\Container('user');

            return [
                'user' => $userSession->details
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'user' => null
            ];
        }
    }
}
