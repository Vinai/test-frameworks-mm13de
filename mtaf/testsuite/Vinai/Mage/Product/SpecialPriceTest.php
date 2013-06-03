<?php


class Vinai_Mage_Product_SpecialPriceTest extends Mage_Selenium_TestCase
{
    /**
     * Create simple product
     *
     * @return string
     * @test
     */
    public function preconditionsForTests()
    {
        // Navigate to Catalog -> Manage Products
        $this->loginAdminUser();
        $this->navigate('manage_products');

        $productData = $this->loadDataSet('Product', 'product_special_price');
        $this->productHelper()->createProduct($productData);

        // Verification
        $this->assertMessagePresent('success', 'success_saved_product');

        return $productData;
    }

    /**
     * Test special price is visible on product detail page on the frontend
     *
     * @param array $productData
     * @test
     * @depends preconditionsForTests
     */
    public function frontendProductViewDisplaysSpecialPrice($productData)
    {
        $this->addParameter('productName', $productData['general_name']);
        $this->addParameter('productUrlKey', $productData['general_url_key']);

        $this->frontend('product_detail');
        $this->assertTrue(
            $this->controlIsPresent('pageelement', 'special_price'),
            'Special price not found on product detail page.'
        );
    }
}