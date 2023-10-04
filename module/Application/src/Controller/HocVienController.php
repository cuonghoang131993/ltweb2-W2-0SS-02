<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model;
use Application\Form;

class HocVienController extends AbstractActionController
{
    protected $hocVienTable;

    public function __construct(Model\HocVienTable $_hocVienTable)
    {
        $this->hocVienTable = $_hocVienTable;
    }

    public function indexAction()
    {
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            if (preg_replace('/\s+/', '', $keyword) == "") {
                // $form->get('keyword')->setValue("");
                $rows = $this->hocVienTable->getBy([]);
            } else {
                // $form->get('keyword')->setValue($keyword);
                $rows = $this->hocVienTable->danhSachHocVienTimKiem($keyword);
            }
        } else {
            $keyword = "";
            $rows = $this->hocVienTable->getBy([]);
        }

        $view = new ViewModel();

        $view->setVariable('hocVienRows', $rows);
        $view->setVariable('keyword', $keyword);

        return $view;
    }

    public function addAction()
    {
        $messages = "";
        $request = $this->getRequest();
        $hocVienForm = new Form\HocVienForm();

        if (!$request->isPost()) {
            return ['hocVienForm' => $hocVienForm, 'messages' => $messages];
        }
        $hocVien = new Model\HocVienEntity();
        $hocVienForm->setInputFilter($hocVien->getInputFilter());
        $hocVienForm->setData($request->getPost());

        if (!$hocVienForm->isValid()) {
            $messages = $hocVienForm->getMessages();
            return ['hocVienForm' => $hocVienForm, 'messages' => $messages];
        }
        $hocVien->exchangeArray($hocVienForm->getData());
        $this->hocVienTable->save($hocVien);

        $this->redirect()->toRoute('hocVien');
    }

    public function editAction()
    {
        $view = new ViewModel();
        $hocVienId = $this->params()->fromRoute('id', '');
        $view->setVariable('hocVienId', $hocVienId);
        $view->setVariable('messages', '');

        if (preg_replace('/\s+/', '', $hocVienId) == '') {
            return $this->redirect()->toRoute('hocVien', ['action' => 'add']);
        }
        // get user data; if it doesn’t exists, then redirect back to the index
        try {
            $hocVienRow = $this->hocVienTable->getById($hocVienId);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('hocVien', ['action' => 'index']);
        }
        $hocVienForm = new Form\HocVienForm();
        $hocVienForm->bind($hocVienRow);

        $hocVienForm->get('submit')->setAttribute('value', 'Sửa');
        
        $request = $this->getRequest();
        $view->setVariable('hocVienForm', $hocVienForm);

        if (!$request->isPost()) {
            return $view;
        }
        $hocVienForm->setInputFilter($hocVienRow->getInputFilter());
        $hocVienForm->setData($request->getPost());

        if (!$hocVienForm->isValid()) {
            $view->setVariable('messages', $hocVienForm->getMessages());
            return $view;
        }
        
        $u_hocvien = array(
            'TenSV' => $hocVienForm->getData()->TenSV,
            'GioiTinh' => $hocVienForm->getData()->GioiTinh,
            'Nsinh' => $hocVienForm->getData()->Nsinh,
        );
        $this->hocVienTable->patch($hocVienId, $u_hocvien);

        // data saved, redirect to the users list page
        return $this->redirect()->toRoute('hocVien', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $hocVienId = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $hocVienId) == '') {
            return $this->redirect()->toRoute('hocVien');
        }
        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $hocVienId = $request->getPost('id');
                $this->hocVienTable->delete($hocVienId);
            }
            // redirect to the users list
            return $this->redirect()->toRoute('hocVien');
        }
        return [
            'id' => $hocVienId,
            'hocVien' => $this->hocVienTable->getById($hocVienId),
        ];
    }
}

?>
