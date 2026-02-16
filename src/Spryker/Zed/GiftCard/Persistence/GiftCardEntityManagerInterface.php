<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

interface GiftCardEntityManagerInterface
{
    /**
     * @param array<int> $salesPaymentIds
     *
     * @return void
     */
    public function deletePaymentGiftCardsBySalesPaymentIds(array $salesPaymentIds): void;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemGiftCardsBySalesOrderItemIds(array $salesOrderItemIds): void;
}
