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
 * @group CreateGiftCardForOrderItemTest
 * Add your own group annotations below this line
 */
class CreateGiftCardForOrderItemTest extends Unit
{
    protected const string DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected const string CODE_PATTERN = '{prefix}-{randomPart}';

    protected const string GENERATED_CODE_REGULAR_EXPRESSION_TEMPLATE = '/^%s-[0-9A-Z]{%d}$/';

    protected const string ORDER_ITEM_NAME = 'Gift card 50';

    protected const int GIFT_CARD_VALUE = 5000;

    protected const int ORDER_ITEM_PRICE = 4200;

    protected GiftCardBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    public function testGivenAGiftCardOrderItemWithCodePatternWhenCreateGiftCardForOrderItemThenAGiftCardWithGeneratedCodeIsPersisted(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $salesOrderItemEntity->setName(static::ORDER_ITEM_NAME)->save();
        $this->tester->createSalesOrderItemGiftCardWithCodePattern(
            $salesOrderItemEntity->getIdSalesOrderItem(),
            static::CODE_PATTERN,
            static::GIFT_CARD_VALUE,
        );
        $giftCardConfig = $this->tester->getModuleConfig();
        $generatedCodeRegularExpression = sprintf(
            static::GENERATED_CODE_REGULAR_EXPRESSION_TEMPLATE,
            preg_quote($giftCardConfig->getCodePrefix(), '/'),
            $giftCardConfig->getCodeRandomPartLength(),
        );

        // Act
        $giftCardTransfer = $this->tester->getFacade()->createGiftCardForOrderItem($salesOrderItemEntity->getIdSalesOrderItem());

        // Assert
        $persistedGiftCardTransfer = $this->tester->getFacade()->findById($giftCardTransfer->getIdGiftCardOrFail());

        $this->assertNotNull($persistedGiftCardTransfer);
        $this->assertMatchesRegularExpression($generatedCodeRegularExpression, $persistedGiftCardTransfer->getCode());
        $this->assertSame($giftCardTransfer->getCode(), $persistedGiftCardTransfer->getCode());
        $this->assertSame(static::GIFT_CARD_VALUE, $persistedGiftCardTransfer->getValue());
        $this->assertSame(static::ORDER_ITEM_NAME, $persistedGiftCardTransfer->getName());
        $this->assertTrue($persistedGiftCardTransfer->getIsActive());
    }

    public function testGivenAGiftCardOrderItemWithCodePatternWhenCreateGiftCardForOrderItemThenTheGeneratedCodeIsStoredOnTheOrderItemMetadata(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemGiftCardEntity = $this->tester->createSalesOrderItemGiftCardWithCodePattern(
            $idSalesOrderItem,
            static::CODE_PATTERN,
            static::GIFT_CARD_VALUE,
        );

        // Act
        $giftCardTransfer = $this->tester->getFacade()->createGiftCardForOrderItem($idSalesOrderItem);

        // Assert
        $salesOrderItemGiftCardEntity->reload();
        $orderItemGiftCardTransfer = $this->tester->getFacade()->findGiftCardByIdSalesOrderItem($idSalesOrderItem);

        $this->assertSame($giftCardTransfer->getCode(), $salesOrderItemGiftCardEntity->getCode());
        $this->assertNotNull($orderItemGiftCardTransfer);
        $this->assertSame($giftCardTransfer->getIdGiftCard(), $orderItemGiftCardTransfer->getIdGiftCard());
    }

    public function testGivenAGiftCardOrderItemWithoutConfiguredValueWhenCreateGiftCardForOrderItemThenTheGiftCardValueIsTheOrderItemPrice(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $salesOrderItemEntity->setPrice(static::ORDER_ITEM_PRICE)->save();
        $this->tester->createSalesOrderItemGiftCardWithCodePattern(
            $salesOrderItemEntity->getIdSalesOrderItem(),
            static::CODE_PATTERN,
        );

        // Act
        $giftCardTransfer = $this->tester->getFacade()->createGiftCardForOrderItem($salesOrderItemEntity->getIdSalesOrderItem());

        // Assert
        $persistedGiftCardTransfer = $this->tester->getFacade()->findById($giftCardTransfer->getIdGiftCardOrFail());

        $this->assertNotNull($persistedGiftCardTransfer);
        $this->assertSame(static::ORDER_ITEM_PRICE, $persistedGiftCardTransfer->getValue());
    }
}
