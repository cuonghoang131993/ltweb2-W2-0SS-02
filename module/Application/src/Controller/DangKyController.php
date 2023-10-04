<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model;
use Application\Form;

class DangKyController extends AbstractActionController
{
    private $dangKyTable;

    public function __construct(Model\DangKyTable $_dangKyTable)
    {
        $this->dangKyTable = $_dangKyTable;
    }

    public function indexAction()
    {
        $res = $this->dangKyTable->getBy();

        return new ViewModel([
            'dangKyRows' => $res
        ]);
    }

    public function addAction()
    {
        $messages = '';
        $form = new Form\DangKyForm();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['dangKyForm' => $form, 'messages' => $messages];
        }

        $dangky = new Model\DangKyEntity();
        $form->setInputFilter($dangky->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $messages = $form->getMessages();
            return ['dangKyForm' => $form, 'messages' => $messages];
        }

        $dangky->exchangeArray($form->getData());
        $this->dangKyTable->save($dangky);
        return $this->redirect()->toRoute('dangKy');
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', '');
        $viewData['messages'] = '';

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('dangKy', ['action' => 'add']);
        }

        try {
            $dangky = $this->dangKyTable->getById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('dangKy', ['action' => 'index']);
        }

        $form = new Form\DangKyForm();
        $form->bind($dangky);
        $form->get('submit')->setAttribute('value', 'Sửa');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'dangKyForm' => $form, 'dangky' => $dangky];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($dangky->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $viewData['messages'] = $form->getMessages();
            return $viewData;
        }

        try {
            $u_dangky = array(
                'Diem' => $form->getData()->Diem,
            );
 
            $this->dangKyTable->patch($id, $u_dangky);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('dangKy', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', '');

        if (preg_replace('/\s+/', '', $id) == '') {
            return $this->redirect()->toRoute('dangKy');
        }

        try {
            $dangky = $this->dangKyTable->getById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('dangKy', ['action' => 'index']);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $this->dangKyTable->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('dangKy');
        }

        return [
            'id' => $id,
            'dangky' => $dangky
        ];
    }
}
