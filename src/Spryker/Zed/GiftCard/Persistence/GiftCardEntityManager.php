<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardPersistenceFactory getFactory()
 */
class GiftCardEntityManager extends AbstractEntityManager implements GiftCardEntityManagerInterface
{
    /**
     * @param array<int> $salesPaymentIds
     *
     * @return void
     */
    public function deletePaymentGiftCardsBySalesPaymentIds(array $salesPaymentIds): void
    {
        $this->getFactory()
            ->createSalesOrderGiftCardQuery()
            ->filterByFkSalesPayment_In(array_unique($salesPaymentIds))
            ->delete();
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemGiftCardsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createSpySalesOrderItemGiftCardQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
