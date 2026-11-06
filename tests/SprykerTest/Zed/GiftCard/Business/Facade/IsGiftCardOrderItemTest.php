<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group IsGiftCardOrderItemTest
 * Add your own group annotations below this line
 */
class IsGiftCardOrderItemTest extends Unit
{
    protected const string DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected GiftCardBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    public function testGivenAnOrderItemWithGiftCardMetadataWhenIsGiftCardOrderItemThenReturnsTrue(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createSalesOrderItemGiftCard($idSalesOrderItem);

        // Act
        $isGiftCardOrderItem = $this->tester->getFacade()->isGiftCardOrderItem($idSalesOrderItem);

        // Assert
        $this->assertTrue($isGiftCardOrderItem);
    }

    public function testGivenAnOrderItemWithoutGiftCardMetadataWhenIsGiftCardOrderItemThenReturnsFalse(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();

        // Act
        $isGiftCardOrderItem = $this->tester->getFacade()->isGiftCardOrderItem($idSalesOrderItem);

        // Assert
        $this->assertFalse($isGiftCardOrderItem);
    }
}
