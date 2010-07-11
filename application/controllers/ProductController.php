<?php

class ProductController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $product = new Default_Model_Product();
        $this->view->items = $product->fetchAll();
        $data = new Zend_Dojo_Data('id', $this->view->items);
        //echo $data;
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form    = new Default_Form_Product();

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $model = new Default_Model_Product($form->getValues());
            $model->save();
            return $this->_helper->redirector('index');
        }
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $request = $this->getRequest();
        $form    = new Default_Form_Product();
        $product = new Default_Model_Product();
        
        // Get Product Id
        $id = $request->getParam("id", 1);

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $model = new Default_Model_Product($form->getValues());
            $model->setId($id);
            $model->save();
            return $this->_helper->redirector('index');
        }
        
        // Get the Product Data by Id
        $data = $product->find($id);
        // Add Product Data to Form
        $form->populate($data->toArray());
        
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        // action body
    }
}
?>
