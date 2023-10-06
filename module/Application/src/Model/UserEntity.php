<?php

namespace Application\Model;

use DomainException;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class UserEntity extends AbstractModel implements InputFilterAwareInterface
{
    public $inputFilter = null;

    public $Email = null;

    public $Password = null;
    public $Password_salt = null;

    public $Username = null;

    public $id = null;

    public function getEmail()
    {
        return $this->Email;
    }

    public function setEmail($value)
    {
        $this->Email = $value;
        return $this;
    }

    public function getPassword()
    {
        return $this->Password;
    }

    public function setPassword($value)
    {
        $this->Password = $value;
        return $this;
    }

    public function getPasswordSalt()
    {
        return $this->Password_salt;
    }

    public function setPasswordSalt($value)
    {
        $this->Password_salt = $value;
        return $this;
    }

    public function getUsername()
    {
        return $this->Username;
    }

    public function setUsername($value)
    {
        $this->Username = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function exchangeArray(array $row)
    {
        $this->id = (!empty($row['id'])) ? $row['id'] : null;
        $this->Email = (!empty($row['Email'])) ? $row['Email'] : null;
        $this->Password = (!empty($row['Password'])) ? $row['Password'] : null;
        $this->Password_salt = (!empty($row['Password_salt'])) ? $row['Password_salt'] : null;
        $this->Username = (!empty($row['Username'])) ? $row['Username'] : null;
    }

    public function getArrayCopy()
    {
        return[
            'id' => $this->getId(),
            'Email' => $this->getEmail(),
            'Password' => $this->getPassword(),
            'Password_Salt' => $this->getPasswordSalt(),
            'Username' => $this->getUsername(),
        ];
    }

    public function getInputFilter(bool $includeIdField = true)
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        if ($includeIdField) {
            $inputFilter->add([
                'name' => 'id',
                'required' => true,
                'filters' => [
                    ['name' => ToInt::class],
                ],
            ]);
        }
        $inputFilter->add([
            'name' => 'Email',
        ]);

        $inputFilter->add([
            'name' => 'Password',
        ]);

        $inputFilter->add([
            'name' => 'Username',
        ]);


        $this->inputFilter = $inputFilter;
        return $inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException('This class does not support adding of extra input filters');
    }
}

?>