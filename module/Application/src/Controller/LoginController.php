<?php

namespace Application\Controller;

use \Application\Model;
use \Application\Form;
use Laminas\Session;

class LoginController extends AbstractController
{
    protected $securityAuth;

    public function __construct($securityAuth)
    {
        $this->securityAuth = $securityAuth;
    }
    public function indexAction()
    {
        $form = new Form\UserLoginForm();
        //print_r($this);
        if (!$this->getRequest()->isPost()) {
            return [
                'form' => $form
            ];
        }
        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            return [
                'form' => $form,
                'messages' => $form->getMessages()
            ];
        }
        $auth = $this->securityAuth->auth(
            $form->get($form::FIELDSET_LOGIN)->get('Email')->getValue(),
            $form->get($form::FIELDSET_LOGIN)->get('Password')->getValue()
        );
        $identity = $this->securityAuth->getIdentityArray();

        if ($identity) {
            $rowset = new Model\UserEntity();
            $rowset->exchangeArray($identity);
            $this->securityAuth->getStorage()->write($rowset);

            $sessionUser = new Session\Container('user');
            $sessionUser->details = $rowset;

            return $this->redirect()->toUrl($this->baseUrl);
        } else {
            $message = '<strong>Error</strong> Given email address or password is incorrect.';
            return [
                'form' => $form,
                'messages' => $message
            ];
        }
    }
}
