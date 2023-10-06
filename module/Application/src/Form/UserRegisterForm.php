<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Filter\StringTrim;

class UserRegisterForm extends Form implements InputFilterProviderInterface
{
    const TIMEOUT = 300;
    const ELEMENT_PASSWORD_CONFIRM = 'confirm_password';
    const ELEMENT_CSRF = 'user_csrf';
    const ELEMENT_CAPTCHA = 'captcha';

    const ELEMENT_EMAIL = 'Email';
    const ELEMENT_PASSWORD = 'Password';
    const ELEMENT_USERNAME = 'Username';

    const FIELDSET_LOGIN = 'login_fieldset';

    public function __construct($name = 'register_user', $params = [])
    {
        parent::__construct($name, $params);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'name' => self::ELEMENT_EMAIL,
            'type' => 'text',
            'options' => [
                'label' => 'Email'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);
        $this->add([
            'name' => self::ELEMENT_PASSWORD,
            'type' => Element\Password::class,
            'options' => [
                'label' => 'Password'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);
        $this->add([
            'name' => self::ELEMENT_PASSWORD_CONFIRM,
            'type' => Element\Password::class,
            'options' => [
                'label' => 'Repeat password',
            ],
            'attributes' => [
                'required' => true
            ],
        ]);
        $this->add([
            'name' => self::ELEMENT_USERNAME,
            'type' => 'text',
            'options' => [
                'label' => 'Username'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);


        $this->add([
            'name' => self::ELEMENT_CSRF,
            'type' => Element\Csrf::class,
            'options' => [
                'salt' => 'unique',
                'timeout' => self::TIMEOUT
            ],
            'attributes' => [
                'id' => self::ELEMENT_CSRF
            ]
        ]);

        /*$this->add([
            'name' => self::ELEMENT_CAPTCHA,
            'type' => Element\Captcha::class,
            'options' => [
                'label' => 'Rewrite Captcha text:',
                'captcha' => new Captcha\Image([
                    'name' => 'myCaptcha',
                    'messages' => array(
                        'badCaptcha' => 'incorrectly rewritten image text'
                    ),
                    'wordLen' => 5,
                    'timeout' => self::TIMEOUT,
                    'font' => APPLICATION_PATH.'/public/fonts/arbli.ttf',
                    'imgDir' => APPLICATION_PATH.'/public/img/captcha/',
                    'imgUrl' => $this->getOption('baseUrl').'/public/img/captcha/',
                    'lineNoiseLevel' => 4,
                    'width' => 200,
                    'height' => 70
                ]),
            ]
        ]);*/

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Register',
                'class' => 'btn btn-primary'
            ]
        ]);

        $this->setAttribute('method', 'POST');
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name' => self::ELEMENT_PASSWORD_CONFIRM,
                'filters' => [
                    ['name' => StringTrim::class]
                ],
                'validators' => [
                    [
                        'name' => Validator\Identical::class,
                        'options' => [
                            'token' => self::ELEMENT_PASSWORD,
                            'messages' => [
                                Validator\Identical::NOT_SAME => 'Passwords are not the same'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}

?>