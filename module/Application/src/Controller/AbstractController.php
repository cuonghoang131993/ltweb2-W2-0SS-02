<?php

namespace Application\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Session;

class AbstractController extends \Laminas\Mvc\Controller\AbstractActionController
{
    protected $sessionUser;
    protected $baseUrl;

    public function __construct()
    {
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->baseUrl = $this->getRequest()->getBasePath();
        $this->sessionUser = new Session\Container('user');
        $e->getViewModel()->setVariable('user', $this->sessionUser->details);

        return parent::onDispatch($e);
    }
}