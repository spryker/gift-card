<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Communication\Plugin\SalesPayment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment\GiftCardOrderPaymentExpanderPlugin;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Communication
 * @group Plugin
 * @group SalesPayment
 * @group GiftCardOrderPaymentExpanderPluginTest
 * Add your own group annotations below this line
 */
class GiftCardOrderPaymentExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected GiftCardBusinessTester $tester;

    public function testExpandSetsGiftCardWhenThisPaymentRedeemedOne(): void
    {
        // Arrange
        $this->tester->haveGiftCard([
            GiftCardTransfer::CODE => GiftCardBusinessTester::GIFT_CARD_CODE,
        ]);
        $idSalesPayment = $this->tester->createSalesPaymentEntity();
        $this->tester->createPaymentGiftCardEntity($idSalesPayment);

        $paymentTransfer = (new PaymentTransfer())->setIdSalesPayment($idSalesPayment);

        // Act
        $expandedPaymentTransfer = (new GiftCardOrderPaymentExpanderPlugin())->expand(new OrderTransfer(), $paymentTransfer);

        // Assert
        $this->assertNotNull($expandedPaymentTransfer->getGiftCard());
        $this->assertSame(GiftCardBusinessTester::GIFT_CARD_CODE, $expandedPaymentTransfer->getGiftCard()->getCode());
    }

    public function testExpandLeavesGiftCardNullWhenThisPaymentDidNotRedeemOne(): void
    {
        // Arrange
        $idSalesPayment = $this->tester->createSalesPaymentEntity();
        $paymentTransfer = (new PaymentTransfer())->setIdSalesPayment($idSalesPayment);

        // Act
        $expandedPaymentTransfer = (new GiftCardOrderPaymentExpanderPlugin())->expand(new OrderTransfer(), $paymentTransfer);

        // Assert
        $this->assertNull($expandedPaymentTransfer->getGiftCard());
    }

    public function testExpandLeavesGiftCardNullWhenIdSalesPaymentIsNotSet(): void
    {
        // Arrange
        $paymentTransfer = new PaymentTransfer();

        // Act
        $expandedPaymentTransfer = (new GiftCardOrderPaymentExpanderPlugin())->expand(new OrderTransfer(), $paymentTransfer);

        // Assert
        $this->assertNull($expandedPaymentTransfer->getGiftCard());
    }
}
