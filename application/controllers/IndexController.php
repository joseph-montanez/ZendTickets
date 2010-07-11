<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

        // Check to see if the application has been installed!
        $installFile = realpath(getcwd() . '/../data/installed');
        if(!is_file($installFile)) {
            $this->_redirect('/install');
        }
    }

    public function indexAction()
    {
        // action body
    }


}

