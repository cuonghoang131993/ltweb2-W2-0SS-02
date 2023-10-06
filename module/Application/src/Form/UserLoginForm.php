<?php
namespace Application\Form;

use Laminas\Form\Form;

class UserLoginForm extends Form
{
    const FIELDSET_LOGIN = 'login_fieldset';

    public function __construct($name = 'login_user')
    {
        parent::__construct($name);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'type' => UserLoginFieldset::class,
            'name' => self::FIELDSET_LOGIN
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Login',
                'class' => 'btn btn-primary'
            ]
        ]);
        $this->setAttribute('method', 'POST');
    }
}

?>