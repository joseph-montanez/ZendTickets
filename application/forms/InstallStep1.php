<?php

class Default_Form_InstallStep1 extends Zend_Dojo_Form {

    public $adaptors = array();

    public function __construct($options = null) {
        if (isset($options['adaptors'])) {
            $this->adaptors = (array) $options['adaptors'];
        }
        parent::__construct($options);
    }

    public function init() {
        // custom validation
        Zend_Dojo::enableForm($this);
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('FilteringSelect', 'adaptorType', array(
            'label' => 'Type of Database',
            'required' => true,
            'autocomplete' => false,
            'multiOptions' => $this->adaptors
        ));

        // Add an email element
        $this->addElement('Checkbox', 'writable', array(
            'label' => '"/data" Folder Writable:',
            'required' => true,
            'disabled' => true
        ));
        $element = $this->getElement('writable');
        $element->addPrefixPath('ZendTickets_Validate', 'ZendTickets/Validate/', 'validate');
        $element->addValidator('WritableFolder', null, 'data');


        // Add the submit button
        $this->addElement('SubmitButton', 'saveChanges', array(
            'ignore' => true,
            'label' => 'Save Changes',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }

}

