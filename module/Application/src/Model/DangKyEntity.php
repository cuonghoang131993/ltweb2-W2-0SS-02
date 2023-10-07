<?php

namespace Application\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\ToFloat;
use Laminas\Validator\Between;
use Laminas\Validator\GreaterThan;
use Laminas\Validator\StringLength;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use \Application\Helper;

class DangKyEntity extends AbstractModel implements InputFilterAwareInterface
{
    public $inputFilter = null;

    public $id = null;
    public $MaSV = null;

    public $Lop = null;

    public $LanHocThu = null;

    public $Diem = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getMaSV()
    {
        return $this->MaSV;
    }

    public function setMaSV($value)
    {
        $this->MaSV = $value;
        return $this;
    }

    public function getLop()
    {
        return $this->Lop;
    }

    public function setLop($value)
    {
        $this->Lop = $value;
        return $this;
    }

    public function getLanHocThu()
    {
        return $this->LanHocThu;
    }

    public function setLanHocThu($value)
    {
        $this->LanHocThu = $value;
        return $this;
    }

    public function getDiem()
    {
        return $this->Diem;
    }

    public function setDiem($value)
    {
        $this->Diem = $value;
        return $this;
    }

    public function exchangeArray(array $row)
    {
        $this->id = (!empty($row['MaSV']) && !empty($row['Lop']) && !empty($row['LanHocThu'])) ? Helper\Crypt::encrypt([
            0 => $row['MaSV'],
            1 => $row['Lop'],
            2 => $row['LanHocThu']
        ]) : null;
        $this->MaSV = (!empty($row['MaSV'])) ? $row['MaSV'] : null;
        $this->Lop = (!empty($row['Lop'])) ? $row['Lop'] : null;
        $this->LanHocThu = (!empty($row['LanHocThu'])) ? $row['LanHocThu'] : null;
        $this->Diem = (!empty($row['Diem'])) ? $row['Diem'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'MaSV' => $this->getMaSV(),
            'Lop' => $this->getLop(),
            'LanHocThu' => $this->getLanHocThu(),
            'Diem' => $this->getDiem(),
        ];
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'MaSV',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'min' => 1,
                    'max' => 12
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'Lop',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 7
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'LanHocThu',
            'options' => [
                'label' => 'Lần học',
            ],
            'filters' => [
                ['name' => ToInt::class],
            ],
            'validators' => [
                [
                    'name' => GreaterThan::class,
                    'options' => [
                        'min' => 0
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'Diem',
            'filters' => [
                ['name' => ToFloat::class],
            ],
            'validators' => [
                [
                    'name' => Between::class,
                    'options' => [
                        'min' => 0,
                        'max' => 10
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(
            sprintf(
                '%s does not allow injection of an alternate input filter',
                __CLASS__
            )
        );
    }
}

?>