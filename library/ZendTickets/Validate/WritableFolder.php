<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WriteableFolder
 *
 * @author joseph
 */
class ZendTickets_Validate_WritableFolder extends Zend_Validate_Abstract
{
    const NOT_WRITABLE = 'notWritable';

    protected $_messageTemplates = array(
        self::NOT_WRITABLE => 'Folder is not writable, please change the folder
            permissions to 0777, or read, write.'
    );
    protected $_folder = false;

    public function  __construct($folder) {
        $this->_folder = $folder;
    }

    public function isValid($value, $context = null)
    {
        $path = realpath(getcwd() . '/../' . $this->_folder);
        $writable = is_writable($path);

        if(!$writable) {
            $this->_setValue("0");
            $this->_error(self::NOT_WRITABLE);
            return false;
        }
        
        $this->_setValue("1");

        return true;
    }
}
?>
