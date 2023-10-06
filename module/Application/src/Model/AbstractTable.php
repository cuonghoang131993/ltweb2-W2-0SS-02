<?php

namespace Application\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;
use Application\Model\AbstractModel;
use RuntimeException;

class AbstractTable
{
    protected $tableGateway;
        
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function disableCache()
    {
    }
    
    protected function fetchAll()
    {
        return $this->tableGateway->select();
    }
    
    protected function fetchRow($passedSelect)
    {
        $row = $this->tableGateway->selectWith($passedSelect);
        return $row->current();
    }
    
    public function saveRow(AbstractModel $userModel, $data = null, $fieldId = 'id')
    {
        $id = $userModel->getId();
        
        if (empty($id) || !$this->getById($id)) {
            $data = $userModel->getArrayCopy();
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        }
 
        $this->tableGateway->update($data, [$fieldId => $id]);
        return $id;
    }
    
    public function deleteRow($id)
    {
        $this->tableGateway->delete($id);
    }
    
    public function getTableGateway()
    {
        return $this->tableGateway;
    }
}

?>