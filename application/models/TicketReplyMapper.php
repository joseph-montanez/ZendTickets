<?php
class Default_Model_TicketReplyMapper
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
            $this->setDbTable('Default_Model_DbTable_TicketReply');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_TicketReply $reply)
    {
        $data = array(
            'ticketId'    => $reply->getTicketId(),
            'subject'     => $reply->getSubject(),
            'message'     => $reply->getMessage(),
            'fromEmail'   => $reply->getFromEmail(),
            'receiveDate' => $reply->getReceiveDate()
        );

        if (null === ($id = $reply->getId())) {
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

    public function find($id, Default_Model_TicketReply $reply)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $reply->setId($row->id)
              ->setTicketId($row->ticketId)
              ->setSubject($row->subject)
              ->setMessage($row->message)
              ->setFromEmail($row->fromEmail)
              ->setReceiveDate($row->receiveDate);
    }

    public function fetchByTicket($ticketId)
    {
        $table = $this->getDbTable();
        $resultSet = $table->fetchAll(
            $table->select()->where('ticketId = ?', $ticketId)
        );
        
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_TicketReply();
            $entry->setId($row->id)
                  ->setTicketId($row->ticketId)
                  ->setSubject($row->subject)
                  ->setMessage($row->message)
                  ->setFromEmail($row->fromEmail)
                  ->setReceiveDate($row->receiveDate);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_TicketReply();
            $entry->setId($row->id)
                  ->setTicketId($row->ticketId)
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
