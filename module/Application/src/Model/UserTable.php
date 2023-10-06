<?php

namespace Application\Model;

class UserTable extends AbstractTable
{
    public function getById($id)
    {
        return $this->getBy(['id' => $id]);
    }

    public function getBy(array $params = [])
    {
        $select = $this->tableGateway->getSql()->select();

        if (isset($params['id'])) {
            $select->where(['id' => $params['id']]);
            $params['limit'] = 1;
        }

        if (isset($params['Email'])) {
            $select->where(['Email' => $params['Email']]);
        }

        if (isset($params['Username'])) {
            $select->where(['Username' => $params['Username']]);
        }

        if (isset($params['limit'])) {
            $select->limit($params['limit']);
        }

        $result = (isset($params['limit']) && $params['limit'] == 1)
            ? $this->fetchRow($select)
            : $this->fetchAll();

        return $result;
    }

    public function patch(int $id, array $data)
    {
        if (empty($data)) {
            throw new \Exception('missing data to update');
        }
        $passedData = [];

        if (!empty($data['Email'])) {
            $passedData['Email'] = $data['Email'];
        }

        if (!empty($data['Password'])) {
            $passedData['Password'] = $data['Password'];
        }

        if (!empty($data['Username'])) {
            $passedData['Username'] = $data['Username'];
        }


        $this->tableGateway->update($passedData, ['id' => $id]);
    }

    public function save(UserEntity $rowset)
    {
        return parent::saveRow($rowset);
    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception('missing UsersTable id to delete');
        }
        parent::deleteRow($id);
    }
}

?>