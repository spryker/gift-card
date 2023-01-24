<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardRepositoryInterface getRepository()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $orderTransfer = $this->getOrder($request);

        $giftCardTransfers = $this->getFacade()->findGiftCardsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        return [
            'giftCards' => $giftCardTransfers,
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrder(Request $request): OrderTransfer
    {
        // @deprecated Exists for BC reasons. Will be removed in the next major release.
        if ($request->request->has('orderTransfer')) {
            /** @phpstan-var \Generated\Shared\Transfer\OrderTransfer */
            return $request->request->get('orderTransfer');
        }

        if (!$request->request->has('serializedOrderTransfer')) {
            throw new InvalidArgumentException('`serializedOrderTransfer` not found in request');
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->unserialize((string)$request->request->get('serializedOrderTransfer'));

        return $orderTransfer;
    }
}
