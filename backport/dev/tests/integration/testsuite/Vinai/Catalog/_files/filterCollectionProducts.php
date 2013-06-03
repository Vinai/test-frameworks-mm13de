<?php

/**
 * @see Vinai_Catalog_Model_Resource_Product_CollectionTest
 */

// Create special attribute
$installer = new Mage_Catalog_Model_Resource_Setup('default_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', 'special_attribute', array(
    'label' => 'Test Attribute',
    'type' => 'int',
    'required' => 0,
    'visible' => 0,
    'used_in_product_listing' => 1,
    'is_configurable' => 0,
    'group' => 'General'
));
$installer->endSetup();

// Create products
$product = new Mage_Catalog_Model_Product();

// Reset Product Resource Model and eav/config so the Attribute Models are reloaded
// Otherwise if the fixture is run again the attribute ID won't match
$product->getResource()->unsetAttributes();
Mage::getSingleton('eav/config')->clear();
// A (worse) alternative to using the deprecated clear() method:
// Mage::unregister('_singleton/eav/config');

$defaultValues = array(
    'attribute_set_id' => $product->getDefaultAttributeSetId(),
    'type_id' => 'simple',
    'website_ids' => array(1),
    'price' => 10,
    'sku' => 'test-product',
    'name' => 'Test Product',
    'visibility' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
    'status' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
);

for ($i = 0; $i < 3; $i++) {
    // First product has 0, the other two products have 1
    $attrValue = intval((bool) $i);
    $product->clearInstance()
        ->setData($defaultValues)
        ->setData('name', $product->getData('name') . " {$i}")
        ->setData('sku',  $product->getData('sku')  . "-{$i}")
        ->setData('special_attribute', $attrValue)
        ->save();
}
