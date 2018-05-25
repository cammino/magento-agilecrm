<?php
class Cammino_Agilecrm_Model_Api extends Mage_Core_Model_Abstract {

    public function getOrderScript($orderId) {

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $address = $order->getBillingAddress();
        $street = $address->getStreet();
        $tags = array('customer');
        $items = $order->getAllVisibleItems();
        $note = 'Subtotal: ' . $order->getSubtotal() . '|Shipping: ' . $order->getShippingAmount() . '|Total: ' . $order->getGrandTotal();

        foreach ($items as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $tags[] = $product->getUrlKey();
            $note .= '|' . intval($item->getQtyOrdered()) . 'x ' . $product->getName() . ' \n';

            $categories = $product->getCategoryIds();

            foreach( $categories as $categoryId ) {
                $category = Mage::getModel( 'catalog/category' )->load( $categoryId );
                $tags[] = $category->getUrlKey();
            }
        }

        return '<script type="text/javascript">
            window.setTimeout(function() {

                var contact = {};
                contact.email = "'. $order->getCustomerEmail() .'";
                contact.first_name = "'. $order->getCustomerFirstname() .'";
                contact.last_name = "'. $order->getCustomerLastname() .'";
                contact.phone = "'. $address->getTelephone() .'";
                var address = { "address": "'. implode(", ", $street) .'", "city": "'. $address->getCity() .'", "state": "'. $address->getRegion() .'", "zip": "'. $address->getPostcode() .'" };
                contact.address = JSON.stringify(address);
                contact.tags = "'. implode(", ", $tags) .'";

                _agile.create_contact(contact, {
                    success: function (data) {
                        var note = {};
                        note.subject = "Order #'. $order->getIncrementId() .'";
                        note.description = "'. $note .'".replace(/\|/ig, "\n");
                        _agile.add_note(note);
                    }
                });

                _agile.set_email(contact.email);
            }, 1000);
            </script>';
    }

    public function getSubscriberScript($subscriber) {
        return '<script type="text/javascript">
            window.setTimeout(function() {

                var contact = {};
                contact.email = "'. $subscriber .'";
                contact.tags = "subscriber";

                _agile.create_contact(contact);
                _agile.set_email(contact.email);
            }, 1000);
            </script>';
    }
}