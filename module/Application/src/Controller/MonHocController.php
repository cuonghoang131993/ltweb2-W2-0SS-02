<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Model;
use Application\Form;

class MonHocController extends AbstractController
{
    private $monHocTable;

    public function __construct(Model\MonHocTable $_monHocTable)
    {
        $this->monHocTable = $_monHocTable;
    }

    public function indexAction()
    {
        $res = $this->monHocTable->getBy();

        return [
            'monHocRows' => $res,
        ];
    }

    public function addAction()
    {
        $form = new Form\MonHocForm();

        $request = $this->getRequest();

        $view = [
            'monHocForm' => $form,
        ];

        if (!$request->isPost()) {
            return $view;
        }

        $monhoc = new Model\MonHocEntity();
        $form->setInputFilter($monhoc->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $view['messages'] = $form->getMessages();
            return $view;
        }

        $monhoc->exchangeArray($form->getData());
        $this->monHocTable->save($monhoc);
        return $this->redirect()->toRoute('monHoc');
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('monHoc', ['action' => 'add']);
        }
        
        try {
            $monhoc = $this->monHocTable->getById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('monHoc', ['action' => 'index']);
        }

        $form = new Form\MonHocForm();
        $form->bind($monhoc);
        $form->get('submit')->setAttribute('value', 'Sửa');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'monHocForm' => $form, 'current_route' => $this->currentRoute ];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($monhoc->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $viewData['messages'] = $form->getMessages();
            return $viewData;
        }

        try {
            $u_monhoc = array(
                'TenMon' => $form->getData()->TenMon,
            );
            
            $this->monHocTable->patch($id, $u_monhoc);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('monHoc', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('monHoc');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = $request->getPost('id');
                $this->monHocTable->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('monHoc');
        }

        return [
            'id'    => $id,
        ];
    }
}