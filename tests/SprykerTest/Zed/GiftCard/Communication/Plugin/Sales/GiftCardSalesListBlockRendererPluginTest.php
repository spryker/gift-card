<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\GiftCard\Communication\Plugin\Sales\GiftCardSalesListBlockRendererPlugin;
use SprykerTest\Zed\GiftCard\GiftCardCommunicationTester;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group GiftCardSalesListBlockRendererPluginTest
 * Add your own group annotations below this line
 */
class GiftCardSalesListBlockRendererPluginTest extends Unit
{
    protected const string BLOCK_URL = '/gift-card/sales/list';

    protected const string OTHER_URL = '/other/url';

    protected GiftCardCommunicationTester $tester;

    public function testIsApplicableReturnsTrueForMatchingUrl(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->isApplicable(static::BLOCK_URL);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsApplicableReturnsFalseForNonMatchingUrl(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->isApplicable(static::OTHER_URL);

        // Assert
        $this->assertFalse($result);
    }

    public function testGetTemplatePathReturnsExpectedPath(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->getTemplatePath(static::BLOCK_URL);

        // Assert
        $this->assertSame('@GiftCard/Sales/list.twig', $result);
    }

    public function testGetDataReturnsGiftCardsAndOrder(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();
        $orderTransfer = (new OrderTransfer())->setIdSalesOrder(0);

        // Act
        $result = $plugin->getData(new Request(), $orderTransfer, static::BLOCK_URL);

        // Assert
        $this->assertArrayHasKey('giftCards', $result);
        $this->assertArrayHasKey('order', $result);
        $this->assertSame($orderTransfer, $result['order']);
    }

    public function getBlockRendererPlugin(): GiftCardSalesListBlockRendererPlugin
    {
        return new GiftCardSalesListBlockRendererPlugin();
    }
}
