<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GiftCard;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class GiftCardConfig extends AbstractSharedConfig
{
    /**
     * @api
     *
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT
     *
     * @var string
     */
    public const SHIPMENT_METHOD_NAME_NO_SHIPMENT = 'NoShipment';
}
