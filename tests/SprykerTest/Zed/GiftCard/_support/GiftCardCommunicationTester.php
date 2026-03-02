<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard;

use Codeception\Actor;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\GiftCard\PHPMD)
 */
class GiftCardCommunicationTester extends Actor
{
    use _generated\GiftCardCommunicationTesterActions;

    public function ensureSalesOrderItemGiftCardDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesOrderItemGiftCardQuery(),
        );
    }

    public function findSalesOrderItemGiftCard(int $idSalesOrderItem): SpySalesOrderItemGiftCard
    {
        return $this->getSalesOrderItemGiftCardQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    public function getSalesOrderItemGiftCardQuery(): SpySalesOrderItemGiftCardQuery
    {
        return SpySalesOrderItemGiftCardQuery::create();
    }
}
