<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form\Element;

class LopHocForm extends Form implements InputFilterProviderInterface
{
    const ELEMENT_MAMH = 'MaMH';
    const ELEMENT_MALOP = 'MaLop';
    const ELEMENT_HOCKY = 'HocKy';
    const ELEMENT_NAM = 'Nam';

    public function __construct($name = 'LopHoc_form', array $params = [])
    {
        parent::__construct($name, $params);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'name' => self::ELEMENT_MALOP,
            'type' => 'text',
            'options' => [
                'label' => 'Mã Lớp'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_HOCKY,
            'type' => 'number',
            'options' => [
                'label' => 'Học kỳ'
            ],
            'attributes' => [
                'min' => 1,
                'max' => 2,
                'required' => true
            ],
        ]);

        $this->add([
            'name' => self::ELEMENT_NAM,
            'type' => 'number',
            'options' => [
                'label' => 'Năm học'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'MaMH',
            'options' => [
                'label' => 'Môn học',
                'value_options' => isset($params["monHocRows"]) ? $params["monHocRows"] : [],
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
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [];
    }
}

?>