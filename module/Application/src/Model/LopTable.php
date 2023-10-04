<?php

namespace Application\Model;

use Laminas\Db\Sql;

class LopTable extends AbstractTable
{
    public function getById($id)
    {
        return $this->getBy(['MaLop' => $id]);
    }

    public function getBy(array $params = [])
    {
        $select = $this->tableGateway->getSql()->select();

        if (isset($params['MaLop'])) {
            $select->where(['MaLop' => $params['MaLop']]);
            $params['limit'] = 1;
        }

        if (isset($params['MaMH'])) {
            $select->where(['MaMH' => $params['MaMH']]);
        }

        if (isset($params['limit'])) {
            $select->limit($params['limit']);
        }

        $result = (isset($params['limit']) && $params['limit'] == 1)
            ? $this->fetchRow($select)
            : $this->fetchAll($select);

        return $result;
    }

    public function danhSachLop()
    {
        $result = $this->tableGateway->select(function (Sql\Select $select) {
            $select
                ->columns(array(
                    'MaLop',
                    'HocKy',
                    'Nam'
                ))
                ->join('monhoc', 'monhoc.MaMonHoc = lophoc.MaMH', array(
                    'TenMon'
                ));
            });
            
        return $result;
    }

    public function danhSachLopTimKiem($keyword = "")
    {
        $result = $this->tableGateway->select(function (Sql\Select $select) use($keyword) {
            $select
                ->columns(array(
                    'MaLop',
                    'HocKy',
                    'Nam'
                ))
                ->join('monhoc', 'monhoc.MaMonHoc = lophoc.MaMH', array(
                    'TenMon'
                ))
                ->where->like('lophoc.MaLop', "%$keyword%")
                ->or
                ->where->like('monhoc.TenMon', "%$keyword%");
            });
            
        return $result;
    }

    public function save(LopEntity $rowset, $data = null)
    {
        return parent::saveRow($rowset, $data);
    }

    public function patch(mixed $id, array $data)
    {
        if (empty($data)) {
            throw new \Exception('missing data to update');
        }
        $passedData = [];

        if (!empty($data['MaMH'])) {
            $passedData['MaMH'] = $data['MaMH'];
        }

        if (!empty($data['MaLop'])) {
            $passedData['MaLop'] = $data['MaLop'];
        }

        if (!empty($data['HocKy'])) {
            $passedData['HocKy'] = $data['HocKy'];
        }

        if (!empty($data['Nam'])) {
            $passedData['Nam'] = $data['Nam'];
        }

        $this->tableGateway->update($passedData, ['MaLop' => $id]);
    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception('missing LopTable id to delete');
        }
        parent::deleteRow(['MaLop' => $id]);
    }
}

?>