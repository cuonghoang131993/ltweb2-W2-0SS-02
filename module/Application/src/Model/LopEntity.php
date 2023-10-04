<?php

namespace Application\Model;

// Add the following import statements:
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Validator\StringLength;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class LopEntity extends AbstractModel implements InputFilterAwareInterface
{
    public $maMH;
    public $tenMon;
    public $maLop;
    public $hocky;
    public $nam;
    private $inputFilter;

    public function getId()
    {
        return $this->maLop ?? "";
    }

    public function getMaMH()
    {
        return $this->maMH;
    }

    public function setMaMH($value)
    {
        $this->maMH = $value;
        return $this;
    }

    public function getTenMon()
    {
        return $this->tenMon;
    }

    public function setTenMon($value)
    {
        $this->tenMon = $value;
        return $this;
    }

    public function getMaLop()
    {
        return $this->maLop;
    }

    public function setMaLop($value)
    {
        $this->maLop = $value;
        return $this;
    }

    public function getHocKy()
    {
        return $this->hocky;
    }

    public function setHocKy($value)
    {
        $this->hocky = $value;
        return $this;
    }

    public function getNam()
    {
        return $this->nam;
    }

    public function setNam($value)
    {
        $this->nam = $value;
        return $this;
    }

    public function exchangeArray(array $data)
    {
        $this->maMH = !empty($data['MaMonHoc']) ? $data['MaMonHoc'] : (
            !empty($data['MaMH']) ? $data['MaMH'] : null);
        $this->tenMon = !empty($data['TenMon']) ? $data['TenMon'] : null;
        $this->maLop = !empty($data['MaLop']) ? $data['MaLop'] : null;
        $this->hocky = !empty($data['HocKy']) ? $data['HocKy'] : null;
        $this->nam = !empty($data['Nam']) ? $data['Nam'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'MaMH' => $this->getMaMH(),
            'MaLop' => $this->getMaLop(),
            'HocKy' => $this->getHocKy(),
            'Nam' => $this->getNam(),
        ];
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'MaMH',
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
            'name' => 'MaLop',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'min' => 1,
                    'max' => 7
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'HocKy',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ]
        ]);

        $inputFilter->add([
            'name' => 'Nam',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ]
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
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