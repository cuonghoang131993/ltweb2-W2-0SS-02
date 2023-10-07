<?php

namespace Application\Hydrator;

use \Application\Form;

class UserFormHydrator implements \Laminas\Hydrator\Strategy\StrategyInterface
{
    protected $securityHelper;

    public function __construct($securityHelper)
    {
        $this->securityHelper = $securityHelper;
    }

    public function hydrate($form, $extraData = null)
    {
        if (!$form instanceof \Application\Form\UserRegisterForm) {
            throw new \Exception('invalid form object passed to the'.__CLASS__);
        }
        $data = $form->getData();
        $hashedPassword = $this->securityHelper->sha512($data[Form\UserLoginFieldset::ELEMENT_PASSWORD]);
        
        return [
            'Username' => $data[Form\UsernameFieldset::ELEMENT_USERNAME],
            'Email' => $data[Form\UserLoginFieldset::ELEMENT_EMAIL],
            'Password' => $hashedPassword['hash'],
            'Password_salt' => $hashedPassword['salt']
        ];
    }

    public function extract($array, $object = null)
    {
        return $array;
    }
}

