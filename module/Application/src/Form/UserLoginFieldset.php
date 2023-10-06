<?php
namespace Application\Form;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form\Element;
use Laminas\Validator;
use Laminas\Filter;
use Laminas\Filter\StringTrim;

class UserLoginFieldset extends Fieldset implements InputFilterProviderInterface
{
    const TIMEOUT = 300;
    const ELEMENT_EMAIL = 'Email';
    const ELEMENT_PASSWORD = 'Password';
    const ELEMENT_CSRF = 'user_csrf';

    public function __construct()
    {
        parent::__construct('user_login');

        $this->add([
            'type' => Element\Email::class,
            'name' => self::ELEMENT_EMAIL,
            'attributes' => [
                'required' => true,
            ],
            'options' => [
                'label' => 'Email'
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_PASSWORD,
            'type' => Element\Password::class,
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'required' => true
            ],
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
    }

    public function getInputFilterSpecification()
    {
        $validators = [
            [
                'name' => self::ELEMENT_EMAIL,
                'filters' => [
                    ['name' => Filter\StringTrim::class]
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 5,
                            'messages' => [
                                Validator\StringLength::TOO_SHORT => 'The minimum length is: %min%'
                            ]
                        ]
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                Validator\EmailAddress::INVALID_FORMAT => 'validator.Email.format',
                                Validator\EmailAddress::INVALID => 'validator.Email.general',
                                Validator\EmailAddress::INVALID_HOSTNAME => 'validator.Email.hostname',
                                Validator\EmailAddress::INVALID_LOCAL_PART => 'validator.Email.local',
                                Validator\Hostname::UNKNOWN_TLD => 'validator.Email.unknown_domain',
                                Validator\Hostname::LOCAL_NAME_NOT_ALLOWED => 'validator.Email.name_not_allowed'
                            )
                        )
                    ]
                ]
            ],
            [
                'name' => self::ELEMENT_PASSWORD,
                'required' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class]
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'min' => 5,
                            'messages' => [
                                Validator\StringLength::TOO_SHORT => 'The minimum length is: %min%'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        //let's add extra DB validator to the register form, bypassing login form
        if (!empty($this->getOption('dbAdapter'))) {
            $validators[0]['validators'][] = [
                'name' => Validator\Db\NoRecordExists::class,
                'options' => array(
                    'adapter' => $this->getOption('dbAdapter'),
                    'table' => 'user',
                    'field' => 'Email',
                    'messages' => array(
                        Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Provided Email address already exists in database'
                    )
                )
            ];
        }

        return $validators;
    }
}

?>