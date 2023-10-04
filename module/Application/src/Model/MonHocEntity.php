<?php

namespace Application\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\StringLength;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class MonHocEntity extends AbstractModel implements InputFilterAwareInterface
{

    public $inputFilter = null;

    public $MaMonHoc = null;

    public $TenMon = null;

    public function getId()
    {
        return $this->MaMonHoc ?? "";
    }

    public function getMaMonHoc()
    {
        return $this->MaMonHoc;
    }

    public function setMaMonHoc($value)
    {
        $this->MaMonHoc = $value;
    }

    public function getTenMon()
    {
        return $this->TenMon;
    }

    public function setTenMon($value)
    {
        $this->TenMon = $value;
        return $this;
    }

    public function exchangeArray(array $row)
    {
        $this->MaMonHoc = (!empty($row['MaMonHoc'])) ? $row['MaMonHoc'] : null;
        $this->TenMon = (!empty($row['TenMon'])) ? $row['TenMon'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'MaMonHoc' => $this->getMaMonHoc(),
            'TenMon' => $this->getTenMon(),
        ];
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'MaMonHoc',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'min' => 1,
                    'max' => 9
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'TenMon',
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