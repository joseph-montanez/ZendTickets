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
                    'adaptors' => $adaptors
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
            mkdir($dbPath);
            mkdir($logPath);
            chmod($dbPath, 0777);
            chmod($logPath, 0777);

            $schemaFile = false;
            $adaptorType = $request->getParam('adaptorType');
            if (stristr($adaptorType, 'sqlite')) {
                $schemaFile = 'schema.sqlite.sql';
            }
            if ($schemaFile !== false) {
                $schemaFile = realpath(getcwd() . '/../scripts/' . $schemaFile);
                $dbFile = $dbPath . '/tickets.db';
                if (is_file($dbFile) and is_writable($dbFile)) {
                    unlink($dbFile);
                }
                $dbAdapter = Zend_Db::factory($adaptorType, array(
                            'dbname' => $dbFile,
                        ));
                $schemaSql = file_get_contents($schemaFile);
                $dbAdapter->getConnection()->exec($schemaSql);
            }
            return $this->_helper->redirector('step-two');
        }

        $this->view->form = $form;
    }

}

