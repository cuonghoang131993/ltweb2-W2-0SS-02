<?php

declare(strict_types=1);

namespace Application\Controller;

use \Application\Model;
use \Application\Form;

class IndexController extends AbstractController
{
    private $lopTable;
    private $monHocTable;
    private $monHocRows;
    private $x = array('monHocRows' => array());

    public function __construct(Model\LopTable $_lopTable, Model\MonHocTable $_monHocTable)
    {
        $this->lopTable = $_lopTable;
        $this->monHocTable = $_monHocTable;
        $this->monHocRows = $this->monHocTable->getBy();
    }

    public function indexAction()
    {
        // $form = new Form\LopSearchForm();

        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            if (preg_replace('/\s+/', '', $keyword) == "") {
                // $form->get('keyword')->setValue("");
                $lopList = $this->lopTable->danhSachLop();
            } else {
                // $form->get('keyword')->setValue($keyword);
                $lopList = $this->lopTable->danhSachLopTimKiem($keyword);
            }
        } else {
            $keyword = "";
            $lopList = $this->lopTable->danhSachLop();
        }

        return [
            // 'form' => $form,
            'lopList' => $lopList,
            'keyword' => $keyword,
        ];
    }

    public function addAction()
    {
        foreach ($this->monHocRows as $monhoc) {
            $mon = $monhoc->MaMonHoc . " (" . $monhoc->TenMon . ")";
            $this->x['monHocRows'][$monhoc->MaMonHoc] = $mon;
        }

        $form = new Form\LopHocForm('LopHoc_form', $this->x);

        $view = [
            'lopHocForm' => $form,
            'messages' => '',
        ];

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $view;
        }

        $lophoc = new Model\LopEntity();
        $form->setInputFilter($lophoc->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $view['messages'] = $form->getMessages();
            return $view;
        }

        $lophoc->exchangeArray($form->getData());
        $this->lopTable->save($lophoc);
        return $this->redirect()->toRoute('lopHoc');
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('lopHoc', ['action' => 'add']);
        }
        
        try {
            $lophoc = $this->lopTable->getById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('lopHoc', ['action' => 'index']);
        }

        foreach ($this->monHocRows as $monhoc) {
            $mon = $monhoc->MaMonHoc . " (" . $monhoc->TenMon . ")";
            $this->x['monHocRows'][$monhoc->MaMonHoc] = $mon;
        }
        $form = new Form\LopHocForm('LopHoc_form', $this->x);
        $form->bind($lophoc);
        $form->get('submit')->setAttribute('value', 'Sửa');

        $request = $this->getRequest();

        $viewData = [
            'messages' => '',
            'id' => $id,
            'lopHocForm' => $form,
        ];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($lophoc->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $viewData['messages'] = $form->getMessages();
            return $viewData;
        }

        try {
            $u_lophoc = array(
                'MaMH' => $form->getData()->maMH,
                'HocKy' => $form->getData()->hocky,
                'Nam' => $form->getData()->nam,
            );
            
            $this->lopTable->patch($id, $u_lophoc);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('lopHoc', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('lopHoc');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = $request->getPost('id');
                $this->lopTable->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('lopHoc');
        }

        return [
            'id'    => $id,
        ];
    }
}