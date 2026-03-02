<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\CartCode;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardCartCodeClearer implements GiftCardCartCodeClearerInterface
{
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer
            ->setGiftCards(new ArrayObject())
            ->setPayment(null);
    }
}
