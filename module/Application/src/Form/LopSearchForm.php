<?php
namespace Application\Form;

use Laminas\Form\Form;

class LopSearchForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('lop');

        $this->add([
            'name' => 'keyword',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Nhập tên môn hoặc mã lớp để tìm kiếm',
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'attributes' => [
                'class' => 'btn btn-primary',
                'value' => 'Tìm',
            ],
        ]);

        $this->setAttribute('method', 'GET');
    }
}
?>