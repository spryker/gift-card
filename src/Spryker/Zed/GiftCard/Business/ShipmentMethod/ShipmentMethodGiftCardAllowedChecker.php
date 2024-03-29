<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;

class ShipmentMethodGiftCardAllowedChecker implements ShipmentMethodGiftCardAllowanceCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param array<string> $giftCardOnlyShipmentMethods
     *
     * @return bool
     */
    public function isShipmentMethodSuitable(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        array $giftCardOnlyShipmentMethods
    ): bool {
        return in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods);
    }
}
