<?php


class Vinai_Catalog_Model_Resource_Product_CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 0
     * @magentoDataFixture Vinai/Catalog/_files/filterCollectionProducts.php
     */
    public function testFilterCollectionByAttribute()
    {
        $collection = new Mage_Catalog_Model_Resource_Product_Collection;
        $collection->addAttributeToFilter('special_attribute', 1);

        $this->assertFalse(
            $collection->isEnabledFlat(),
            "The flat product table is being used to load the product collection."
        );

        $this->assertEquals(
            2, count($collection),
            "Expected 2 items in the product collection, found {$collection->count()}"
        );
    }

    /**
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     * @magentoDataFixture Vinai/Catalog/_files/filterCollectionProducts.php
     */
    public function testFilterCollectionByAttributeWithFlatCatalog()
    {
        $indexer = new Mage_Index_Model_Indexer();
        $process = $indexer->getProcessByCode('catalog_product_flat');
        $process->reindexEverything();

        $collection = new Mage_Catalog_Model_Resource_Product_Collection;
        $collection->addAttributeToFilter('special_attribute', 1);

        $this->assertTrue(
            $collection->isEnabledFlat(),
            "The flat product table is NOT being used to load the product collection."
        );

        $this->assertEquals(
            2, count($collection),
            "Expected 2 items in the product collection, found {$collection->count()}"
        );
    }
}