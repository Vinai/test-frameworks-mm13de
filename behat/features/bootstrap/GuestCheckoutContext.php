<?php

use MageTest\MagentoExtension\Context\MagentoContext;
use Behat\Behat\Context\Step\Given;
use Behat\Behat\Exception\PendingException;

class GuestCheckoutContext extends MagentoContext
{
    /**
     * @Given /^the Admin User has disabled guest checkout$/
     */
    public function theAdminUserHasDisabledGuestCheckout()
    {
        return new Given('I set config value for "checkout/options/guest_checkout" to "0" in "default" scope');
    }

    /**
     * @Given /^the Admin User has enabled guest checkout$/
     */
    public function theAdminUserHasEnabledGuestCheckout()
    {
        return new Given('I set config value for "checkout/options/guest_checkout" to "1" in "default" scope');
    }

    /**
     * @When /^I am not logged in as a customer$/
     */
    public function iAmNotLoggedInAsACustomer()
    {
        $this->getSession()->visit($this->locatePath('customer/account/logout'));
    }

    /**
     * @Given /^I add "([^"]*)" to the cart$/
     */
    public function iAddAProductToTheCart($sku)
    {
        /** @var \Mage_Catalog_Model_Product $product */
        $product = \Mage::getModel('catalog/product');
        $productId = $product->getIdBySku($sku);
        if (! $productId) {
            throw new \Exception("Unable to add product to cart: invalid SKU '{$sku}'");
        }
        // Load product page and get the form action to get the right form key value
        $this->getSession()->visit($this->locatePath('catalog/product/view/id/' . $productId));
        $form = $this->getSession()->getPage()->findById('product_addtocart_form');
        $url = $form->getAttribute('action');
        $this->getSession()->visit($url);
        if (! $this->getSession()->getPage()->hasContent('was added to your shopping cart')) {
            throw new \Exception("Unable to add product to cart: response doesn't contain confirmation.");
        }
    }

    /**
     * @Given /^I go the the checkout$/
     */
    public function iGoTheTheCheckout()
    {
        $this->getSession()->visit($this->locatePath('checkout/onepage'));
    }

    /**
     * @Then /^I am not able to check out as guest$/
     */
    public function iAmNotAbleToCheckOutAsGuest()
    {
        $page = $this->getSession()->getPage();
        if (strpos($page->getContent(), 'Shopping Cart is Empty') !== false) {
            throw new \Exception('Unable to check guest checkout availability, no products in cart');
        }
        if ($page->hasField('login:guest')) {
            throw new \Exception('Guest checkout available');
        }

    }

    /**
     * @Then /^I have the option to checkout as guest$/
     */
    public function iHaveTheOptionToCheckoutAsGuest()
    {
        $page = $this->getSession()->getPage();
        if (strpos($page->getContent(), 'Shopping Cart is Empty') !== false) {
            throw new \Exception('Unable to check guest checkout availability, no products in cart');
        }
        if (!$page->hasField('login:guest')) {
            throw new \Exception('Guest checkout not available');
        }
    }

    /**
     * @Then /^I have the option to log in for checking out$/
     */
    public function iHaveTheOptionToLogInForCheckingOut()
    {
        $page = $this->getSession()->getPage();
        if (strpos($page->getContent(), 'Shopping Cart is Empty') !== false) {
            throw new \Exception('Unable to check login in for checkout availability, no products in cart');
        }
        if (!$page->hasContent('login-form')) {
            throw new \Exception('Login for checkout not available');
        }
    }

    /**
     * @Then /^I have the option to register during checkout$/
     */
    public function iHaveTheOptionToRegisterDuringCheckout()
    {
        $page = $this->getSession()->getPage();
        if (strpos($page->getContent(), 'Shopping Cart is Empty') !== false) {
            throw new \Exception('Unable to check registration during checkout availability, no products in cart');
        }
        if (!$page->hasField('login:register') && !$page->hasButton('Register')) {
            throw new \Exception('Registration during checkout not available');
        }
    }
}
