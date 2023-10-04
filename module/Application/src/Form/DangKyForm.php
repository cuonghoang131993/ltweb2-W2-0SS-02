<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form\Element;

class DangKyForm extends Form implements InputFilterProviderInterface
{

    const ELEMENT_MASV = 'MaSV';

     const ELEMENT_LOP = 'Lop';

     const ELEMENT_HOCLANTHU = 'LanHocThu';

     const ELEMENT_DIEM = 'Diem';

    public function __construct($name = 'HocVien_form', array $params = [])
    {
        parent::__construct($name, $params);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'name' => self::ELEMENT_MASV,
            'type' => 'text',
            'options' => [
                'label' => 'MSSV'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_LOP,
            'type' => 'text',
            'options' => [
                'label' => 'Lớp'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_HOCLANTHU,
            'type' => 'number',
            'options' => [
                'label' => 'Lần học thứ'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_DIEM,
            'type' => 'number',
            'options' => [
                'label' => 'Điểm'
            ],
            'attributes' => [
                'step' => 0.25
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