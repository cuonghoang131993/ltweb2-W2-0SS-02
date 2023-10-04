<?php

namespace Application\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Validator\StringLength;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class HocVienEntity extends AbstractModel implements InputFilterAwareInterface
{
    public $inputFilter = null;

    public $MSSV = null;

    public $TenSV = null;

    public $GioiTinh = null;

    public $Nsinh = null;

    public $DTB = null;

    public function getMSSV()
    {
        return $this->MSSV;
    }

    public function setMSSV($value)
    {
        $this->MSSV = $value;
        return $this;
    }

    public function getTenSV()
    {
        return $this->TenSV;
    }

    public function setTenSV($value)
    {
        $this->TenSV = $value;
        return $this;
    }

    public function getGioiTinh()
    {
        return $this->GioiTinh;
    }

    public function setGioiTinh($value)
    {
        $this->GioiTinh = $value;
        return $this;
    }

    public function getNsinh()
    {
        return $this->Nsinh;
    }

    public function setNsinh($value)
    {
        $this->Nsinh = $value;
        return $this;
    }

    public function getDTB()
    {
        return $this->DTB;
    }

    public function setDTB($value)
    {
        $this->DTB = $value;
        return $this;
    }

    public function getId()
    {
        return $this->MSSV;
    }

    public function exchangeArray(array $row)
    {
        $this->MSSV = (!empty($row['MSSV'])) ? $row['MSSV'] : null;
        $this->TenSV = (!empty($row['TenSV'])) ? $row['TenSV'] : null;
        $this->GioiTinh = (!empty($row['GioiTinh'])) ? $row['GioiTinh'] : null;
        $this->Nsinh = (!empty($row['Nsinh'])) ? $row['Nsinh'] : null;
        $this->DTB = (!empty($row['DTB'])) ? $row['DTB'] : null;
    }

    public function getArrayCopy()
    {
        return[
            'MSSV' => $this->getMSSV(),
            'TenSV' => $this->getTenSV(),
            'GioiTinh' => $this->getGioiTinh(),
            'Nsinh' => $this->getNsinh(),
            'DTB' => $this->getDTB()
        ];
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'MSSV',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 12
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'TenSV',
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
                        'max' => 255
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'GioiTinh',
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
                        'max' => 30
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'Nsinh',
            'required' => true,
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
