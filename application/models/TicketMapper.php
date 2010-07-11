<?php
class Default_Model_TicketMapper
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
            $this->setDbTable('Default_Model_DbTable_Ticket');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Ticket $ticket)
    {
        $data = array(
            'subject'     => $ticket->getSubject(),
            'message'     => $ticket->getMessage(),
            'fromEmail'   => $ticket->getFromEmail(),
            'receiveDate' => $ticket->getReceiveDate()
        );

        if (null === ($id = $ticket->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    
    public function delete($id)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $id);
        $table->delete($where);
    }

    public function find($id, Default_Model_Ticket $ticket)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ticket->setId($row->id)
               ->setSubject($row->subject)
               ->setMessage($row->message)
               ->setFromEmail($row->fromEmail)
               ->setReceiveDate($row->receiveDate);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_Ticket();
            $entry->setId($row->id)
                  ->setSubject($row->subject)
                  ->setMessage($row->message)
                  ->setFromEmail($row->fromEmail)
                  ->setReceiveDate($row->receiveDate);
            $entries[] = $entry;
        }
        return $entries;
    }
}
?>
