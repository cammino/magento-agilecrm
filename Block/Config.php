<?php
class Cammino_Agile_Block_Config extends Mage_Core_Block_Template
{
	private $domain;
    private $key;    
	
	// Agile CMS Configs
    protected function _construct() {
		$this->domain = Mage::getStoreConfig("newsletter/agile/domain");
        $this->key = Mage::getStoreConfig("newsletter/agile/key");        
    }

	// Inject code on success order page, and send contact values to Agile CRM
    protected function _toHtml() {

		// Contact/Order Infos
		$orderId = Mage::getSingleton( 'checkout/session' )->getLastRealOrderId();
		$order = Mage::getModel( 'sales/order' )->loadByIncrementId( $orderId );

        $address = $order->getBillingAddress();
        $street = $address->getStreet();
		$tags = array();
		$categs = array( 'customer' );
        $items = $order->getAllVisibleItems();

		// Load Products
        foreach ( $items as $item ) {
			$product = Mage::getModel( 'catalog/product' )->load( $item->getProductId() );
			
			// Load Produc'ts Categories
			$categories = $product->getCategoryIds();

			foreach( $categories as $categ ) {
				$category = Mage::getModel( 'catalog/category' )->load( $categ );
				$categs[] = $category->getUrlKey();
			}

			$tags[] = $product->getUrlKey();
        }
			 
		// Inject code on success order page
		// and send contact values to Agile CRM
		return '
			<script src="https://vitalatman.agilecrm.com/stats/min/agile-min.js"></script>
			<script>
				_agile.set_account("' . $this->key . '","' . $this->domain . '");
				_agile.track_page_view();
			</script>
			<script type="text/javascript">
            window.setTimeout(function() {		

                var contact = {};
                contact.email = "'. $order->getCustomerEmail() .'";
                contact.first_name = "'. $order->getCustomerFirstname() .'";
                contact.last_name = "'. $order->getCustomerLastname() .'";
                contact.phone = "'. $address->getTelephone() .'";
                var address = { "address": "'. implode(", ", $street) .'", "city": "'. $address->getCity() .'", "state": "'. $address->getRegion() .'", "zip": "'. $address->getPostcode() .'" };
                contact.address = JSON.stringify(address);
				contact.tags = "'. implode( ", ", array_merge( $categs, $tags ) ) .'";

                _agile.create_contact(contact, {
                    success: function (data) {
                        console.log("success");
                    }
                });

                _agile.set_email(contact.email);
            }, 1000);
            </script>';
    }
}