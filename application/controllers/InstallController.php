<?php

class InstallController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->_layout->setLayout('install');
    }

    public function indexAction() {
        // action body
        $adaptors = array(
            'IBM DB2 and Informix Dynamic Server (IDS)' => 'pdo_ibm',
            'MySQL' => 'pdo_mysql',
            'MySQLi' => 'mysqli',
            'Microsoft SQL Server' => 'pdo_mssql',
            'Oracle' => 'pdo_oci',
            'PostgreSQL' => 'pdo_pgsql',
            'Sqlite' => 'pdo_sqlite'

        );
        foreach($adaptors as $key => $adaptor) {
            $db = Zend_Db::factory($adaptor, array(
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


        $form = new Default_Form_InstallStep1();
        $this->view->form = $form;
    }

}