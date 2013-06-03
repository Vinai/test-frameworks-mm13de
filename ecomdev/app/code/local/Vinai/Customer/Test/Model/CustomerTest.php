<?php


class Vinai_Customer_Test_Model_CustomerTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @param string|int $website The website id or code to set on the customer before attempting to authenticate
     * @param string $email
     * @param string $password
     *
     * @test
     * @loadFixture
     * @dataProvider dataProvider
     */
    public function authenticate($website, $email, $password)
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($this->app()->getWebsite($website)->getId());

        $expected = $this->expected("%s  %s", $email, $password);

        if ($exceptionClass = $expected->getExceptionClass()) {
            $this->setExpectedException(
                $exceptionClass,
                $expected->getExceptionMessage(),
                $expected->getExceptionCode()
            );
        }

        $result = $customer->authenticate($email, $password);

        $this->assertEventDispatched('customer_customer_authenticated');
        $this->assertEquals($expected->getValid(), $result);
    }
}