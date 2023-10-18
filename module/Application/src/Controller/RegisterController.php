<?php

namespace Application\Controller;

use \Application\Form;
use \Application\Model;
use \Application\Hydrator;
use Laminas\Session;

class RegisterController extends AbstractController
{
    protected $userModel;
    protected $securityAuth;
    protected $securityHelper;

    public function __construct($userModel, $securityAuth, $securityHelper)
    {
        $this->userModel = $userModel;
        $this->securityAuth = $securityAuth;
        $this->securityHelper = $securityHelper;
    }

    public function indexAction()
    {
        if ($this->sessionUser->details)
            return $this->redirect()->toRoute('home', ['action' => 'index']);

        $form = new Form\UserRegisterForm(
            'user_register',
            [
                'dbAdapter' => $this->userModel->getTableGateway()->getAdapter(),
                'baseUrl' => $this->baseUrl
            ]
        );
        $viewParams = [
            'userForm' => $form
        ];

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $rowset = new Model\UserEntity();
                $hydrator = new Hydrator\UserFormHydrator($this->securityHelper);
                $formData = $form->getData();
                $rowset->exchangeArray($hydrator->hydrate($form));

                //store to database
                $userId = $this->userModel->save($rowset);
                $rowset->setId($userId);
                // $rowset->setRole('user');
                //user logging
                $this->securityAuth->auth(
                    $rowset->getEmail(),
                    $formData[Form\UserLoginFieldset::ELEMENT_PASSWORD]
                );
                $identity = $this->securityAuth->getIdentityArray();

                if ($identity) {
                        //session creation
                        $sessionUser = new Session\Container('user');
                        $sessionUser->details = $rowset;

                    return $this->redirect()->toUrl('login');
                } else {
                    throw new \Exception('Something went wrong.. Check if the user has been added to the database correctly');
                }
            } else {
                $viewParams['messages'] = $form->getMessages();
            }
        }
        return $viewParams;
    }
}
