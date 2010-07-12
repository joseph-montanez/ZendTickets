<?php

class InstallController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
                $this->_helper->_layout->setLayout('install');
    }

    public function indexAction()
    {
        $request = $this->getRequest();
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
                    //TODO: I am sure there is a better way to do this...
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
                if($request->isPost()) {
                    //$form->populate($request->getParams());
                }
                //$form->populate(array(
                //    'writable' => $writable
                //));
        
                if ($request->isPost() && $form->isValid($request->getPost())) {
                    $schemaFile = false;
                    $adaptorType = $request->getParam('adaptorType');
                    if(stristr($adaptorType, 'sqlite')) {
                        $schemaFile = 'schema.sqlite.sql';
                    }
                    if($schemaFile !== false) {
                        $dbAdapter = Zend_Db::factory($adaptorType, array(
                            'dbname' => realpath(getcwd() . '/../data/db/tickets.db'),
                        ));
                        $dbAdapter->getConnection()->exec($schemaSql);
                    }
                    return $this->_helper->redirector('step-two');
                }
        
                $this->view->form = $form;
    }

    public function stepTwoAction()
    {
        // action body
    }


}

