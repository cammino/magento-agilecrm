<?php
class Cammino_Agile_Block_Config extends Mage_Core_Block_Template
{
    protected function _toHtml() {
		$orderId = Mage::getSingleton( 'checkout/session' )->getLastRealOrderId();
        $order = Mage::getModel( 'sales/order' )->loadByIncrementId( $orderId );
        
		return '
			<script src="https://vitalatman.agilecrm.com/stats/min/agile-min.js"></script>
			<script>
			_agile.set_account("h6b7oo76aikrvs94e8li7nkrdt0000","vitalatman");
			_agile.track_page_view();</script>
		';
    }
}