<?php
class Default_Model_Ticket
{
    protected $_fromEmail;
    protected $_subject;
    protected $_message;
    protected $_receiveDate;
    protected $_id;
    protected $_attachments;
    protected $_replies;
    protected $_mapper;
    
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ticket property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ticket property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setfromEmail($text)
    {
        $this->_fromEmail = (string) $text;
        return $this;
    }
    
    public function getfromEmail()
    {
        return $this->_fromEmail;
    }

    public function setSubject($text)
    {
        $this->_subject = (string) $text;
        return $this;
    }
    
    public function getSubject()
    {
        return $this->_subject;
    }
    
    public function setMessage($text)
    {
        $this->_message = (string) $text;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param int $ts UTC timestamp 
     */
    public function setReceiveDate($ts)
    {
        $this->_receiveDate = $ts;
        return $this;
    }
    
    public function getReceiveDate()
    {
        return $this->_receiveDate;
    } 

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }
    
    public function getAttachments()
    {
        if (!isset($this->_attachments)) {
            $attachmentModel = new Default_Model_TicketAttachment();
            $this->_attachments = $attachmentModel->fetchByTicket(
                $this->getId()
            );
        }
        return $this->_attachments;
    }
    
    public function getReplies()
    {
        if (!isset($this->_replies)) {
            $replyModel     = new Default_Model_TicketReply();
            $this->_replies = $replyModel->fetchByTicket($this->getId());
        }
        return $this->_replies;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_TicketMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        $this->getMapper()->save($this);
    }
    
    public function delete($id)
    {
        $this->find($id);
        foreach ($this->attachments as $attachment) {
            $attachment->delete($attachment->id);
        }
        foreach ($this->replies as $reply) {
            $reply->delete($reply->id);
        }
        $this->getMapper()->delete($id);
    }

    public function find($id)
    {
        $this->getMapper()->find($id, $this);
        return $this;
    }

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }
    
    public function toArray()
    {
        $array = array(
            'id' => $this->getId(),
            'fromEmail' => $this->getFromEmail(),
            'subject' => $this->getSubject(),
            'message' => $this->getMessage(),
            'receiveDate' => $this->getReceiveDate(),
        );
        return $array;
    }
}
?>
