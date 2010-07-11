<?php
class Default_Model_TicketAttachmentMapper
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
            $this->setDbTable('Default_Model_DbTable_TicketAttachment');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_TicketAttachment $attachment)
    {
        $data = $attachment->toArray();

        if (null === ($id = $attachment->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getMapper()->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function delete($id)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $id);
        $table->delete($where);
    }

    public function find($id, Default_Model_TicketAttachment $attachment)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $attachment->setId($row->id)
                   ->setTicketId($row->ticketId)
                   ->setReplyId($row->replyId)
                   ->setFilename($row->filename)
                   ->setContentType($row->contentType)
                   ->setContent($row->content);
    }

    public function fetchByTicket($ticketId)
    {
        $table = $this->getDbTable();
        $resultSet = $table->fetchAll(
            $table->select(array('filename, id'))
                ->where('ticketId = ?', $ticketId)
                ->where('replyId = ?', 0)
        );
        
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_TicketAttachment();
            $entry->setId($row->id)
                  ->setTicketId($row->ticketId)
                  ->setReplyId($row->replyId)
                  ->setFilename($row->filename)
                  ->setContentType($row->contentType)
                  ->setContent($row->content);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchByReply($replyId)
    {
        $table = $this->getDbTable();
        $resultSet = $table->fetchAll(
            $table->select(array('filename, id'))
                ->where('replyId = ?', $replyId)
        );
        
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_TicketAttachment();
            $entry->setId($row->id)
                  ->setTicketId($row->ticketId)
                  ->setReplyId($row->replyId)
                  ->setFilename($row->filename)
                  ->setContentType($row->contentType)
                  ->setContent($row->content);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_TicketAttachment();
            $entry->setId($row->id)
                  ->setTicketId($row->ticketId)
                  ->setReplyId($row->replyId)
                  ->setFilename($row->filename)
                  ->setContentType($row->contentType)
                  ->setContent($row->content);
            $entries[] = $entry;
        }
        return $entries;
    }
}
?>
