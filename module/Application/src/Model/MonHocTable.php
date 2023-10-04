<?php

namespace Application\Model;

class MonHocTable extends AbstractTable
{

    public function getById($id)
    {
        return $this->getBy(['MaMonHoc' => $id]);
    }

    public function getBy(array $params = [])
    {
        $select = $this->tableGateway->getSql()->select();

        if (isset($params['MaMonHoc'])) {
            $select->where(['MaMonHoc' => $params['MaMonHoc']]);
            $params['limit'] = 1;
        }

        if (isset($params['TenMon'])) {
            $select->where(['TenMon' => $params['TenMon']]);
        }

        if (isset($params['limit'])) {
            $select->limit($params['limit']);
        }

        $result = (isset($params['limit']) && $params['limit'] == 1)
            ? $this->fetchRow($select)
            : $this->fetchAll($select);

        return $result;
    }

    public function patch(mixed $id, array $data)
    {
        if (empty($data)) {
            throw new \Exception('missing data to update');
        }
        $passedData = [];

        if (!empty($data['MaMonHoc'])) {
            $passedData['MaMonHoc'] = $data['MaMonHoc'];
        }

        if (!empty($data['TenMon'])) {
            $passedData['TenMon'] = $data['TenMon'];
        }

        $this->tableGateway->update($passedData, ['MaMonHoc' => $id]);
    }

    public function save(MonHocEntity $rowset, $data = null)
    {
        return parent::saveRow($rowset, $data);
    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception('missing MonHocTable id to delete');
        }
        parent::deleteRow(['MaMonHoc' => $id]);
    }
}

?>
