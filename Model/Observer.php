<?php
class Cammino_Agilecrm_Model_Observer extends Varien_Object {

    public function injectTracking(Varien_Event_Observer $observer)
    {
        try {
            $block = Mage::app()->getFrontController()->getAction()->getLayout()->createBlock("agilecrm/tracking");
            $blockContent = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('before_body_end');
            if ($blockContent) {
                $blockContent->append($block);
            }
        } catch(Exception $ex) {}
    }

    public function injectOrder(Varien_Event_Observer $observer)
    {
        try {
            $block = Mage::app()->getFrontController()->getAction()->getLayout()->createBlock("agilecrm/order");
            $blockContent = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('before_body_end');
            if ($blockContent) {
                $blockContent->append($block);
            }
        } catch(Exception $ex) {}
    }

    public function saveSubscriber(Varien_Event_Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            $subscriber = $event->getSubscriber();
            Mage::getSingleton('core/session')->getAgilecrmSubscriber($subscriber->getEmail());
        } catch(Exception $ex) {}
    }
}