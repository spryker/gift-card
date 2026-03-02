<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Deleter;

use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;

interface PaymentGiftCardDeleterInterface
{
    public function deletePaymentGiftCardCollection(
        PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
    ): void;
}
