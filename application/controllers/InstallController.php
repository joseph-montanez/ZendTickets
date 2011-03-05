<?php

class InstallController extends Zend_Controller_Action {

    public function init() {
        // Change the layout to our installer
        $this->_helper->_layout->setLayout('install');

        // Build our installtion navigation
        $navStepOne = new ZendTickets_Navigation_Page_Mvc(array(
                    'label' => 'Step One',
                    'controller' => 'install',
                    'action' => 'step-one'
                ));

        $navStepTwo = new ZendTickets_Navigation_Page_Mvc(array(
                    'label' => 'Step Two',
                    'controller' => 'install',
                    'action' => 'step-two'
                ));
        $this->view->navigation = array($navStepOne, $navStepTwo);
    }

    public function indexAction() {
        $this->_redirect('/install/step-one');
    }

    public function stepTwoAction() {
        // action body
    }

    public function stepOneAction() {
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
        foreach ($adaptors as $key => $adaptor) {
            //TODO: I am sure there is a better way to do this...
            $db = Zend_Db::factory($key, array(
                        'dbname' => 'test',
                        'password' => 'test',
                        'username' => 'test'
                    ));
            try {
                $db->query('SELECT * FROM test');
            } catch (Exception $e) {
                if (stristr($e->getMessage(), 'not currently installed')) {
                    unset($adaptors[$key]);
                }
            }
        }
        $this->view->adaptors = $adaptors;

        // Generate Form
        $form = new Default_Form_InstallStep1(array(
                    'adaptors' => $adaptors,
                    'request' => $request
                ));

        // Fill in form
        if ($request->isPost()) {
            //$form->populate($request->getParams());
        }
        //$form->populate(array(
        //    'writable' => $writable
        //));

        if ($request->isPost() && $form->isValid($request->getPost())) {
            // Init directories
            $path = realpath(getcwd() . '/data');
            $dbPath = $path . '/db';
            $logPath = $path . '/log';

            // Create directories
            if (!is_dir($dbPath)) {
                mkdir($dbPath);
                chmod($dbPath, 0777);
            }
            if (!is_dir($logPath)) {
                mkdir($logPath);
                chmod($logPath, 0777);
            }

            $schemaFile = false;
            $adaptorType = $request->getParam('adaptorType');
            $allocationType = 'server';
            if (stristr($adaptorType, 'sqlite')) {
                $schemaFile = 'schema.sqlite.sql';
                $allocationType = 'file';
            } else if (stristr($adaptorType, 'mysql')) {
                $schemaFile = 'schema.mysql.sql';
            }

            if ($schemaFile !== false) {
                $schemaFile = realpath(getcwd() . '/../scripts/' . $schemaFile);
                $dbName = '';

                // Get database name
                if ($allocationType === 'file') {
                    $dbName = $dbPath . '/tickets.db';
                } else {
                    $dbName = $request->getParam('dbName');
                }

                // Remove the older installtion if present
                if (is_file($dbFile) and is_writable($dbFile)) {
                    unlink($dbFile);
                }

                // Get adapter
                $dbAdapter = Zend_Db::factory($adaptorType, array(
                            'dbname' => $dbName,
                            'dbusername' => $request->getParam('dbUsername'),
                            'dbpassword' => $request->getParam('dbUsername'),
                            'dbserver' => $request->getParam('dbServer')
                        ));

                // Read Schema
                $schemaSql = file_get_contents($schemaFile);

                // Apply Schema to Database
                $dbAdapter->getConnection()->exec($schemaSql);
            }
            return $this->_helper->redirector('step-two');
        }

        $this->view->form = $form;
    }

}

