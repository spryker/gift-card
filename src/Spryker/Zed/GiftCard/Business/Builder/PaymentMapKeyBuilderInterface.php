<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Builder;

use Generated\Shared\Transfer\PaymentTransfer;

interface PaymentMapKeyBuilderInterface
{
    public function buildMapKey(PaymentTransfer $paymentTransfer): string;
}
