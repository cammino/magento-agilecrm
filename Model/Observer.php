<?php
class Cammino_Agile_Model_Observer extends Varien_Object
{

	public function injectScript( Varien_Event_Observer $observer ) {
        $block = Mage::app()->getFrontController()->getAction()->getLayout()->createBlock( 'agile/config' );
        $blockContent = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock( 'content' );

		if ( $blockContent ) {
			$blockContent->append( $block );
        }        
    }
    
}