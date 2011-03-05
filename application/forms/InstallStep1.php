<?php

class Default_Form_InstallStep1 extends Zend_Dojo_Form {

    public $adaptors = array();
    public $request;

    public function __construct($options = null) {
        if (isset($options['adaptors'])) {
            $this->adaptors = (array) $options['adaptors'];
        }
        if (isset($options['request'])) {
            // Should be on the same line but too long to fit
            $httpClassName = 'Zend_Controller_Request_Http';
            if (get_class($options['request']) === $httpClassName) {
                $this->request = $options['request'];
            }
        }
        parent::__construct($options);
    }

    public function init() {
        // Custom validation
        Zend_Dojo::enableForm($this);
        // Set the method for the display form to POST
        $this->setMethod('post');

        $adaptorType = $this->request->get('adaptorType');
        $isRequired = $adaptorType !== 'pdo_sqlite';

        $this->addElement('FilteringSelect', 'adaptorType', array(
            'label' => 'Type of Database',
            'required' => true,
            'autocomplete' => false,
            'multiOptions' => $this->adaptors
        ));

        $this->addElement('TextBox', 'dbServer', array(
            'label' => 'Location of Database',
            'required' => $isRequired,
            'value' => 'localhost'
        ));

        $this->addElement('TextBox', 'dbUsername', array(
            'label' => 'Username of Database',
            'required' => $isRequired
        ));

        $this->addElement('PasswordTextBox', 'dbPassword', array(
            'label' => 'Password of Username',
            'required' => $isRequired
        ));

        $this->addElement('TextBox', 'dbName', array(
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
        $element->setChecked($element->isValid('data'));


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

