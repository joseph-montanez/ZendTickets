<?php

class Default_Form_InstallStep1 extends Zend_Dojo_Form
{
    public $adaptors = array();
    public function  __construct($options = null) {
        if(isset($options['adaptors'])) {
            $this->adaptors = (array) $options['adaptors'];
        }
        parent::__construct($options);
    }

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        Zend_Dojo::enableForm($this);
        // Set the method for the display form to POST
        $this->setMethod('post');

        $this->setAttribs(array(
            'name'   => 'toggletab',
            'legend' => 'Basics',
        ));

        // Add an email element
        $this->addElement('ComboBox', 'adaptorType', array(
            'label'      => 'Adaptor Type',
            'required'   => true,
            'multiOptions' => $this->adaptors,
        ));

        // Add an email element
        $this->addElement('Checkbox', 'writeable', array(
            'label'      => 'Data Folder Writeable:',
            'required'   => true,
            'disabled'   => true,
            'checked'    => true
        ));


        // Add the submit button
        $this->addElement('SubmitButton', 'saveChanges', array(
            'ignore'   => true,
            'label'    => 'Save Changes',
        ));
        
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

