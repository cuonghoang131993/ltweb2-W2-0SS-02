<?php

namespace Application\Model;

use Laminas\Db\Sql;

class HocVienTable extends AbstractTable
{

    public function getById($id)
    {
        return $this->getBy(['MSSV' => $id]);
    }

    public function getBy(array $params = [])
    {
        $select = $this->tableGateway->getSql()->select();

        if (isset($params['MSSV'])) {
            $select->where(['MSSV' => $params['MSSV']]);
            $params['limit'] = 1;
        }

        if (isset($params['TenSV'])) {
            $select->where(['TenSV' => $params['TenSV']]);
        }

        if (isset($params['limit'])) {
            $select->limit($params['limit']);
        }

        $result = (isset($params['limit']) && $params['limit'] == 1)
            ? $this->fetchRow($select)
            : $this->fetchAll();

        return $result;
    }

    public function danhSachHocVienTimKiem($keyword = "")
    {
        $result = $this->tableGateway->select(function (Sql\Select $select) use($keyword) {
            $select
                ->columns(array(
                    'MSSV',
                    'TenSV',
                    'GioiTinh',
                    'Nsinh',
                    'DTB'
                ))
                ->where->like('MSSV', $keyword)
                ->or
                ->where->like('TenSV', "%$keyword%");
            });
            
        return $result;
    }

    public function patch(mixed $id, array $data)
    {
        if (empty($data)) {
            throw new \Exception('missing data to update');
        }
        $passedData = [];

        if (!empty($data['MSSV'])) {
            $passedData['MSSV'] = $data['MSSV'];
        }

        if (!empty($data['TenSV'])) {
            $passedData['TenSV'] = $data['TenSV'];
        }

        if (!empty($data['GioiTinh'])) {
            $passedData['GioiTinh'] = $data['GioiTinh'];
        }

        if (!empty($data['Nsinh'])) {
            $passedData['Nsinh'] = $data['Nsinh'];
        }

        if (!empty($data['DTB'])) {
            $passedData['DTB'] = $data['DTB'];
        } else {
            $passedData['DTB'] = null;
        }

        $this->tableGateway->update($passedData, ['MSSV' => $id]);
    }

    public function save(HocVienEntity $rowset, $data = null)
    {
        return parent::saveRow($rowset, $data);
    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception('missing HocVienTable id to delete');
        }
        parent::deleteRow(['MSSV' => $id]);
    }
}

?>
