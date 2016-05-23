<?php
class Canalweb_ContextUrlKeys_Model_Observer
{
    function getAttributeSetIds()
    {
        /* Transform the list entered in b-o to an array */
        $ids = Mage::getStoreConfig('conversaa/urlkeys/attributesetids');
        return explode(',', $ids);
    }
    function getAttributes()
    {
        /* Make an array with the attributes we want in url */
        $attributesCodes = array();
        if (Mage::getStoreConfig('conversaa/urlkeys/attribute1')) {
            $attributesCodes[] = Mage::getStoreConfig('conversaa/urlkeys/attribute1');
        }
        if (Mage::getStoreConfig('conversaa/urlkeys/attribute2')) {
            $attributesCodes[] = Mage::getStoreConfig('conversaa/urlkeys/attribute2');
        }
        if (Mage::getStoreConfig('conversaa/urlkeys/attribute3')) {
            $attributesCodes[] = Mage::getStoreConfig('conversaa/urlkeys/attribute3');
        }
        return $attributesCodes;
    }

    public function updateurl($observer)
    {
        if ($observer->getEvent()->getProduct()) {
            $product = $observer->getEvent()->getProduct();
            $attributeSetIds = $this->getAttributeSetIds();

            // We want custom urls only for specified attributes sets
            if (in_array($product->getAttributeSetId(), $attributeSetIds)) {
                $url = '';
                $attributesCodes = $this->getAttributes();

                // In any case we will want the name at least
                if(!is_null($product->getData('name'))) {
                    $url = $url.$product->getData('name');
                }

                // Add wanted attributes to url
                foreach ($attributesCodes as $attributeCode) {
                    if (!is_null($product->getData($attributeCode))) {
                        $url .= '-'.$product->getAttributeText($attributeCode);
                    }
                }

                $product->setData('url_key', $url);
            } else {
                //Mage::log("current product's attribute set id is: " . $product->getAttributeSetId());
            }

        }
    }

}
