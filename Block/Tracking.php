<?php
class Cammino_Agilecrm_Block_Tracking extends Mage_Core_Block_Template {
    
    private $_enabled;
    private $_javascriptapikey;
    private $_domain;
    private $_webrules;
    private $_sendsubscriber;
    
    protected function _construct() {
        $this->_enabled = Mage::getStoreConfig("newsletter/agilecrm/active");
        $this->_javascriptapikey = Mage::getStoreConfig("newsletter/agilecrm/javascriptapikey");
        $this->_domain = Mage::getStoreConfig("newsletter/agilecrm/domain");
        $this->_webrules = Mage::getStoreConfig("newsletter/agilecrm/webrules");
        $this->_sendsubscriber = Mage::getStoreConfig("newsletter/agilecrm/sendsubscriber");
    }

    protected function _toHtml() {
        $html = "";
        Mage::app()->getStore()->isCurrentlySecure();
        if (strval($this->_enabled) == "1") {

            $api = Mage::getModel('agilecrm/api');
            $subscriber = Mage::getSingleton('core/session')->getAgilecrmSubscriber();
            Mage::getSingleton('core/session')->setAgilecrmSubscriber('');

            $html .=
                '<script id="_agile_min_js" async type="text/javascript" src="https://'. $this->_domain .'.agilecrm.com/stats/min/agile-min.js"></script>
                <script type="text/javascript" >
                var Agile_API = Agile_API || {}; Agile_API.on_after_load = function(){
                    _agile.set_account(\''. $this->_javascriptapikey .'\', \''. $this->_domain .'\');
                    _agile.track_page_view();
                    ' . ((strval($this->_webrules) == "1") ? '_agile_execute_web_rules();' : '') . '
                };
                </script>';

            if ((strval($subscriber) != '') && (strval($this->_sendsubscriber) == "1")) {
                $html .= $api->getSubscriberScript($subscriber);
            }
        }

        return $html;
    }

}