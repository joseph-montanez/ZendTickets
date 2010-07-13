<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author joseph
 */
class ZendTickets_Navigation_Page_Mvc extends Zend_Navigation_Page_Mvc
{
    public function  __toString() {
        $view =  new Zend_View();
        $params = array(
            'href' => $this->getHref(),
            'id' => $this->getId(),
            'class' => $this->getClass() . ($this->getActive() ? 'active' : ''),
        );
        foreach($params as $key => &$param) {
            if(empty($param)) continue;
            $param = $key . '="' . $view->escape($param) . '"';
        }
        return '<a ' . implode(' ', $params) . '>'
                . $view->escape($this->getLabel()) . '</a>';
    }
}
?>
