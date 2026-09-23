<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 */
class GiftCardOrderPaymentExpanderPlugin extends AbstractPlugin implements OrderPaymentExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets `PaymentTransfer.giftCard` from `spy_payment_gift_card` when this payment redeemed a
     *   gift card, so a payment read back from a persisted order carries the same gift card detail
     *   a freshly placed one has in memory. Leaves the transfer unchanged for every other payment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function expand(OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer): PaymentTransfer
    {
        return $paymentTransfer->setGiftCard(
            $this->getBusinessFactory()->createGiftCardReader()->findGiftCardByIdSalesPayment($paymentTransfer->getIdSalesPayment()),
        );
    }
}
