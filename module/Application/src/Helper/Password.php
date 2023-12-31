<?php

namespace Application\Helper;

class Password
{
    const SECRET_KEY = 'W2-0SS-02';

    /**
    * Generates password of type: sha512 and with passed salte for hashng.
    *
    * @param string $phrase plain password
    * @param string $salt optional salt
    *
    * @return string
    */
    public function sha512($phrase, $salt = null)
    {
        $result = array();
        
        if ($salt == null) {
            $salt = $this->generatePassword(8);
        }
        $result['salt'] = $salt;
        $result['hash'] = hash('sha512', $salt.self::SECRET_KEY.$phrase);
        
        return $result;
    }

    /**
    * Generates a random password
    *
    * @param int $maximumLength max length of the password
    *
    * @return string
    */
    public function generatePassword($maximumLength = 14)
    {
        $chars = 'qwertyuipasdfghjkzxcvbnm23456789QWERTYUPASDFGHJKCVBNM';
        $shuffle = str_shuffle($chars);
        
        return substr($shuffle, 0, rand(4, $maximumLength));
    }
}

?>