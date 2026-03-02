<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

class ShipmentMethodGiftCardChecker implements ShipmentMethodGiftCardCheckerInterface
{
    public function containsOnlyGiftCardItems(ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            if (!$this->isGiftCard($itemTransfer)) {
                return false;
            }
        }

        return true;
    }

    protected function isGiftCard(ItemTransfer $itemTransfer): bool
    {
        $giftCardMetadataTransfer = $itemTransfer->getGiftCardMetadata();
        if ($giftCardMetadataTransfer === null) {
            return false;
        }

        return (bool)$giftCardMetadataTransfer->getIsGiftCard();
    }
}
