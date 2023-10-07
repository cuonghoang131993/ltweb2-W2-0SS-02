<?php

namespace Application\Util;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use \Application\Helper;

class Authentication extends AuthenticationService {
    
    protected $adapter;
    protected $dbAdapter;
    // protected $authAdapter;


    public function __construct($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
        $this->adapter = new AuthAdapter(
            $this->dbAdapter,
            'user',
            'Email',
            'Password',
            'SHA2(CONCAT(Password_salt, "'.Helper\Password::SECRET_KEY.'", ?), 512)'
        );
        // $this->authAdapter = $authAdapter;
    }

    public function auth($email, $password) {
        if (empty($email) || empty($password)) {
            return false;
        }
        $this->adapter->setIdentity($email);
        $this->adapter->setCredential($password);
        $result = $this->adapter->authenticate();
        $this->authenticate($this->adapter);
        
        return $result;
    }

    public function getIdentity() {
        return $this->getAdapter()->getResultRowObject();
    }

    public function getIdentityArray()
    {
        return json_decode(json_encode($this->adapter->getResultRowObject()), true);
    }

    public function getAdapter() {
        return $this->adapter;
    }
}

?>