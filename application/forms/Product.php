<?php
class Default_Form_Product extends Zend_Dojo_Form
{
    public function init()
    {
        $this->setDecorators(array(
            'FormElements',
            array('TabContainer', array(
                'id'          => 'tabContainer',
                'style'       => 'width: 640px; height: 500px;',
                'dijitParams' => array(
                    'tabPosition' => 'top'
                ),
            )),
            'DijitForm',
        ));
        // Set the method for the display form to POST
        $this->setMethod('post');
                
        $basicForm = new Zend_Dojo_Form_SubForm();

        $basicForm->setAttribs(array(
            'name'   => 'toggletab',
            'legend' => 'Basics',
        ));
        
        // Add an email element
        $basicForm->addElement('TextBox', 'code', array(
            'label'      => 'Product Code:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 32))
            )
        ));
        
        // Add an email element
        $basicForm->addElement('DateTextBox', 'created', array(
            'label'      => 'Created On:',
            'required'   => false,
            'filters'    => array('StringTrim')
        ));
        
        // Add an email element
        $basicForm->addElement('TextBox', 'name', array(
            'label'      => 'Product Name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 80))
            )
        ));

        // Add the comment element
        $basicForm->addElement('Textarea', 'description', array(
            'label'      => 'Description:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 200))
            )
        ));
        
        // Add an email element
        $basicForm->addElement('CurrencyTextBox', 'listPrice', array(
            'label'      => 'Product List Price:',
            'required'   => true,
            'currency'   => 'USD',
            'symbol'     => 'USD',
            'type'       => 'currency',
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'Float')
            )
        ));
        
        $xtraForm = new Zend_Dojo_Form_SubForm();

        $xtraForm->setAttribs(array(
            'name'   => 'toggle2',
            'legend' => 'Extra',
        ));
        
        // Add an email element
        $xtraForm->addElement('TextBox', 'nada', array(
            'label'      => 'Foo Bar!:',
            'required'   => false,
            'filters'    => array('StringTrim')
        ));
        

        // Add the submit button
        $basicForm->addElement('SubmitButton', 'submit', array(
            'ignore'   => true,
            'label'    => 'Save Product',
        ));
        
        $this->addSubForm($basicForm, 'textboxtab');
        $this->addSubForm($xtraForm, 'textboxtab2');

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
        /*
        $this->setElementDecorators(array(
            'DijitElement',
            'Errors',
            array(array('data'=>'HtmlTag'),array('tag'=>'td')),
            array('Label',array('tag'=>'td')),
            array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));
        $this->setDecorators(array(
            'FormElements',                       
            array(array('data'=>'HtmlTag'),
            array('tag'=>'table','cellspacing'=>'4')),
            'DijitForm'
        ));
         *
         */
    }
}
?>
