<?php
class Cammino_Agilecrm_Block_Order extends Mage_Core_Block_Template {
    
    private $_enabled;
    
    protected function _construct() {
        $this->_enabled = Mage::getStoreConfig("newsletter/agilecrm/active");
    }

    protected function _toHtml() {
        $html = "";
        Mage::app()->getStore()->isCurrentlySecure();

        if (strval($this->_enabled) == "1") {

            $session = Mage::getSingleton('checkout/session');
            $api = Mage::getModel('agilecrm/api');
            $orderId = $this->getRequest()->getParam("id");
            
            if(!$orderId) {
                $orderId = $session->getLastRealOrderId();
            }

            $html .= $api->getOrderScript($orderId);
        }

        return $html;
    }

}