<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $options = $this->getOptions();
        $log = $options['resources']['log']['stream']['writerParams']['stream'];
        $logFolder = dirname(realpath($log));
        $dataFolder = realpath($logFolder . '/../');
        
        if(is_writable($dataFolder) && !is_dir($logFolder)) {
            mkdir($logFolder);
        }
        
        if(!is_writable($logFolder)) {
            chmod($logFolder, 0777);
        }
        
        if(!is_writable($logFolder)) {
            die('Please make this writable: ' . $dataFolder);
        }
        
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath'  => dirname(__FILE__),
        ));

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('ZendTickets_');

        return $autoloader;
    }

    protected function _initDojo()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        $view->dojo()->setLocalPath('/js/dojo/dojo.js');
        $view->dojo()->addStylesheetModule('dijit.themes.tundra');
        $view->dojo()->setDjConfigOption('usePlainJson',true);
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        Zend_Dojo_View_Helper_Dojo::setUseProgrammatic();
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML4_STRICT');
    }
}
