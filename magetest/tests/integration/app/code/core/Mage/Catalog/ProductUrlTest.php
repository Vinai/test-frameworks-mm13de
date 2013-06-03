<?php


class Mage_Catalog_ProductUrlTest extends MageTest_PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    public function setUp()
    {
        $this->_product = MageTest_Fixture_Product::create();

        // Reindex product URL key
        $event = Mage::getSingleton('index/indexer')->logEvent(
            $this->_product,
            $this->_product->getResource()->getType(),
            Mage_Index_Model_Event::TYPE_SAVE,
            false
        );
        Mage::getSingleton('index/indexer')
            ->getProcessByCode('catalog_url')
            ->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)
            ->processEvent($event);
    }

    public function tearDown()
    {
        // Fixtures aren't cleaned up automatically
        if ($this->_product && $this->_product->getId()) {
            $store = Mage::app()->getStore();
            Mage::app()->setCurrentStore('admin');
            $this->_product->delete();
            Mage::app()->setCurrentStore($store->getCode());
        }
    }

    /**
     * @test
     */
    public function productStoreUrl()
    {
        $urlKeyPart = $this->_product->getUrlKey() . Mage::helper('catalog/product')->getProductUrlSuffix();
        $productUrl = $this->_product->getProductUrl();

        $this->assertEquals(
            $urlKeyPart, strstr($productUrl, $urlKeyPart),
            "Product url '{$productUrl}' doesn't end with the product url key '{$urlKeyPart}'."
        );
    }
}
