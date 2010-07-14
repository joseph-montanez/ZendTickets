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

        // Really? I really have to do?
        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        $adaptorType = $request->get('adaptorType');
        $isRequired = $adaptorType !== 'pdo_sqlite';

        $this->addElement('FilteringSelect', 'adaptorType', array(
            'label' => 'Type of Database',
            'required' => true,
            'autocomplete' => false,
            'multiOptions' => $this->adaptors
        ));

        $this->addElement('TextBox', 'server', array(
            'label' => 'Location of Database',
            'required' => $isRequired,
            'value' => 'localhost'
        ));

        $this->addElement('TextBox', 'username', array(
            'label' => 'Username of Database',
            'required' => $isRequired
        ));

        $this->addElement('PasswordTextBox', 'password', array(
            'label' => 'Password of Username',
            'required' => $isRequired
        ));

        $this->addElement('TextBox', 'dbname', array(
            'label' => 'Name of Database',
            'required' => $isRequired
        ));

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

