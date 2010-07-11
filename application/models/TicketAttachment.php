<?php
class Default_Model_TicketAttachment
{
    protected $_ticketId;
    protected $_replyId;
    protected $_filename;
    protected $_contentType;
    protected $_content;
    protected $_id;
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
            throw new Exception('Invalid ticket attachment property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ticket attachment property');
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

    public function setFilename($text)
    {
        $this->_filename = (string) $text;
        return $this;
    }
    
    public function getFilename()
    {
        return $this->_filename;
    }

    public function setContentType($text)
    {
        $this->_contentType = (string) $text;
        return $this;
    }
    
    public function getContentType()
    {
        return $this->_contentType;
    }
    
    public function setContent($text)
    {
        $this->_content = (string) $text;
        return $this;
    }
    
    public function getContent()
    {
        return $this->_content;
    }

    public function setTicketId($ticketId)
    {
        $this->_ticketId = (int) $ticketId;
        return $this;
    }

    public function getTicketId()
    {
        return $this->_ticketId;
    }

    public function setReplyId($replyId)
    {
        $this->_replyId = (int) $replyId;
        return $this;
    }

    public function getReplyId()
    {
        return $this->_replyId;
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

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_TicketAttachmentMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        $this->getMapper()->save($this);
    }
    
    public function delete($id)
    {
        $this->getMapper()->delete($id);
    }

    public function find($id)
    {
        $this->getMapper()->find($id, $this);
        return $this;
    }
    
    public function fetchByTicket($ticketId)
    {
        return $this->getMapper()->fetchByTicket($ticketId);
    }
    
    public function fetchByReply($replyId)
    {
        return $this->getMapper()->fetchByReply($replyId);
    }

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }
    
    public function toArray()
    {
        $array = array(
            'id' => $this->getId(),
            'ticketId' => $this->getTicketId(),
            'replyId'  => $this->getReplyId(),
            'filename' => $this->getFilename(),
            'contentType' => $this->getContentType(),
            'content' => $this->getContent()
        );
        return $array;
    }
}
?>
