<?php

namespace Application\Model;

use Application\Helper;

class DangKyTable extends AbstractTable
{
    public function getById($id)
    {
        $data = self::decryptId($id);

        return $this->getBy($data);
    }

    public function getBy(array $params = [])
    {
        $select = $this->tableGateway->getSql()->select();

        if (isset($params['MaSV'])) {
            $select->where(['MaSV' => $params['MaSV']]);
        }

        if (isset($params['Lop'])) {
            $select->where(['Lop' => $params['Lop']]);
        }

        if (isset($params['LanHocThu'])) {
            $select->where(['LanHocThu' => $params['LanHocThu']]);
        }

        if (isset($params['Diem'])) {
            $select->where(['Diem' => $params['Diem']]);
        }

        if (isset($params['MaSV']) && isset($params['Lop']) && isset($params['LanHocThu'])) {
            $params['limit'] = 1;
        }

        $result = $result = (isset($params['limit']) && $params['limit'] == 1)
        ? $this->fetchRow($select)
        : $this->fetchAll($select);

        return $result;
    }

    public function patch(string $id, array $data)
    {
        if (empty($data)) {
            throw new \Exception('missing data to update');
        }

        $rowset = self::decryptId($id);

        $passedData['Diem'] = $data['Diem'];

        $this->tableGateway->update($passedData, $rowset);
    }

    public function save(DangKyEntity $rowset, $data = null)
    {
        return parent::saveRow($rowset, $data);
    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception('missing DangKyTable id group to delete');
        }

        $rowset = self::decryptId($id);

        parent::deleteRow($rowset);
    }

    private function decryptId($id)
    {
        $data = Helper\Crypt::decrypt($id);

        return [
            'MaSV' => $data[0],
            'Lop' => $data[1],
            'LanHocThu' => $data[2]
        ];
    }
}
