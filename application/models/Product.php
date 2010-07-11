<?php
class Default_Model_Product
{
    protected $_name;
    protected $_code;
    protected $_description;
    protected $_listPrice;
    protected $_created;
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
            throw new Exception('Invalid product property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid product property');
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

    public function setName($text)
    {
        $this->_name = (string) $text;
        return $this;
    }
    
    public function getName()
    {
        return $this->_name;
    }

    public function setCode($text)
    {
        $this->_code = (string) $text;
        return $this;
    }
    
    public function getCode()
    {
        return $this->_code;
    }

    public function setListPrice($number)
    {
        $this->_listPrice = (float) $number;
        return $this;
    }
    
    public function getListPrice()
    {
        return $this->_listPrice;
    }
    
    public function setDescription($text)
    {
        $this->_description = (string) $text;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }

    public function setCreated($ts)
    {
        $this->_created = date('Y-m-d', strtotime($ts));
        return $this;
    }
    
    public function getCreated()
    {
        return $this->_created;
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
            
            $this->setMapper(new Default_Model_ProductMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        $this->getMapper()->save($this);
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
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'listPrice' => $this->getListPrice(),
            'created' => $this->getCreated(),
        );
        return $array;
    }
}
?>
