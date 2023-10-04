<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form\Element;

class HocVienForm extends Form implements InputFilterProviderInterface
{

    const ELEMENT_MSSV = 'MSSV';

    const ELEMENT_TENSV = 'TenSV';

    const ELEMENT_GIOITINH = 'GioiTinh';

    const ELEMENT_NSINH = 'Nsinh';

    const ELEMENT_DTB = 'DTB';

    public function __construct($name = 'HocVien_form', array $params = [])
    {
        parent::__construct($name, $params);
        $this->setAttribute('class', 'styledForm');

        $this->add([
            'name' => self::ELEMENT_MSSV,
            'type' => 'text',
            'options' => [
                'label' => 'MSSV'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_TENSV,
            'type' => 'text',
            'options' => [
                'label' => 'Tên sinh viên'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_GIOITINH,
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Giới tính',
                'value_options' => [
                    'Nam' => 'Nam',
                    'Nữ' => 'Nữ'
                ],
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_NSINH,
            'type' => 'date',
            'options' => [
                'label' => 'Ngày sinh'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => self::ELEMENT_DTB,
            'type' => 'text',
            'options' => [
                'label' => 'Điểm trung bình'
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