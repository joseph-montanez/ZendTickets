<?php
class Default_Model_ProductMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_Product');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Product $product)
    {
        $data = array(
            'code'   => $product->getCode(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'listPrice' => $product->getListPrice(),
            'created' => date('Y-m-d H:i:s'),
        );

        if (null === ($id = $product->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Default_Model_Product $product)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $product->setId($row->id)
                ->setCode($row->code)
                ->setName($row->name)
                ->setDescription($row->description)
                ->setListPrice($row->listPrice)
                ->setCreated($row->created);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_Product();
            $entry->setId($row->id)
                  ->setCode($row->code)
                  ->setName($row->name)
                  ->setDescription($row->description)
                  ->setListPrice($row->listPrice)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }
}
?>
