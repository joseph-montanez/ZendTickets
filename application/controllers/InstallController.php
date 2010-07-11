<?php

class InstallController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->_layout->setLayout('install');
    }

    public function indexAction() {
        // Check to see if data folder is writeable
        $writeablePath = realpath(getcwd() . '/../data');
        $writeable = is_writable($writeablePath);

        // Figure out what adaptors are available
        $adaptors = array(
            'pdo_ibm' => 'IBM DB2 and Informix Dynamic Server (IDS)',
            'pdo_mysql' => 'PDO MySQL',
            'mysqli' => 'MySQLi',
            'pdo_mssql' => 'Microsoft SQL Server',
            'pdo_oci' => 'Oracle',
            'pdo_pgsql' => 'PostgreSQL',
            'pdo_sqlite' => 'Sqlite'

        );
        foreach($adaptors as $key => $adaptor) {
            $db = Zend_Db::factory($key, array(
                'dbname' => 'test',
                'password' => 'test',
                'username' => 'test'
            ));
            try {
                $db->query('SELECT * FROM test');
            } catch (Exception $e) {
                if(stristr($e->getMessage(), 'not currently installed')) {
                    unset($adaptors[$key]);
                }
            }
        }
        $this->view->adaptors = $adaptors;

        // Generate Form
        $form = new Default_Form_InstallStep1(array(
            'adaptors' => $adaptors
        ));

        // Fill in form
        $form->populate(array(
            'writeable' => $writeable
        ));

        $this->view->form = $form;
    }

}