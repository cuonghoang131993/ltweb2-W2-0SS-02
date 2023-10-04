<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use \Laminas\Form\Element;

class MonHocForm extends Form implements InputFilterProviderInterface
{

     const ELEMENT_MAMONHOC = 'MaMonHoc';

     const ELEMENT_TENMON = 'TenMon';

    public function __construct($name = 'MonHoc_form', array $params = [])
    {
        parent::__construct($name, $params);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'name' => self::ELEMENT_MAMONHOC,
            'type' => 'text',
            'options' => [
                'label' => 'Mã môn học'
            ],
            'attributes' => [
                'required' => true
            ],
        ]);

        $this->add([
            'name' => self::ELEMENT_TENMON,
            'type' => 'text',
            'options' => [
                'label' => 'Tên môn học'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);


        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Thêm',
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [];
    }
}

?>