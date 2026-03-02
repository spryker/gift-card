<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business;

use Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydrator;
use Spryker\Zed\GiftCard\Business\Builder\PaymentMapKeyBuilder;
use Spryker\Zed\GiftCard\Business\Builder\PaymentMapKeyBuilderInterface;
use Spryker\Zed\GiftCard\Business\Calculation\GiftCardCalculator;
use Spryker\Zed\GiftCard\Business\Cart\MetadataExpander;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeAdder;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeAdderInterface;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeClearer;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeClearerInterface;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeOperationMessageFinder;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeOperationMessageFinderInterface;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeRemover;
use Spryker\Zed\GiftCard\Business\CartCode\GiftCardCartCodeRemoverInterface;
use Spryker\Zed\GiftCard\Business\Checker\GiftCardItemsChecker;
use Spryker\Zed\GiftCard\Business\Checker\GiftCardItemsCheckerInterface;
use Spryker\Zed\GiftCard\Business\Deleter\PaymentGiftCardDeleter;
use Spryker\Zed\GiftCard\Business\Deleter\PaymentGiftCardDeleterInterface;
use Spryker\Zed\GiftCard\Business\Deleter\SalesOrderItemGiftCardDeleter;
use Spryker\Zed\GiftCard\Business\Deleter\SalesOrderItemGiftCardDeleterInterface;
use Spryker\Zed\GiftCard\Business\Discount\GiftCardDiscountableItemFilter;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGenerator;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreator;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleChecker;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReader;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReplacer;
use Spryker\Zed\GiftCard\Business\Payment\PaymentMethodFilter;
use Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaver;
use Spryker\Zed\GiftCard\Business\Payment\SalesOrderPreChecker;
use Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaver;
use Spryker\Zed\GiftCard\Business\Shipment\ShipmentGroupsSanitizer;
use Spryker\Zed\GiftCard\Business\Shipment\ShipmentGroupsSanitizerInterface;
use Spryker\Zed\GiftCard\Business\Shipment\ShipmentMethodFilter;
use Spryker\Zed\GiftCard\Business\ShipmentGroup\ShipmentGroupMethodFilter;
use Spryker\Zed\GiftCard\Business\ShipmentGroup\ShipmentGroupMethodFilterInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodCollectionRemover;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodCollectionRemoverInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardAllowanceCheckerInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardAllowedChecker;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardChecker;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCheckerInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardDisallowedChecker;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilter;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardReader;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardReaderInterface;
use Spryker\Zed\GiftCard\Business\Writer\OrderGiftCardsRelationshipWriter;
use Spryker\Zed\GiftCard\Business\Writer\OrderGiftCardsRelationshipWriterInterface;
use Spryker\Zed\GiftCard\GiftCardDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardRepositoryInterface getRepository()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface getEntityManager()
 */
class GiftCardBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    public function createGiftCardReader()
    {
        return new GiftCardReader(
            $this->getQueryContainer(),
            $this->createGiftCardActualValueHydrator(),
            $this->getEncodingService(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreatorInterface
     */
    public function createGiftCardCreator()
    {
        return new GiftCardCreator(
            $this->createGiftCardReader(),
            $this->createGiftCardCodeGenerator(),
            $this->getEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGeneratorInterface
     */
    public function createGiftCardCodeGenerator()
    {
        return new GiftCardCodeGenerator(
            $this->createGiftCardReader(),
            $this->getGiftCardCodeCandidateValidationPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaverInterface
     */
    public function createSalesOrderItemSaver()
    {
        return new SalesOrderItemSaver(
            $this->getGiftCardAttributePlugins(),
            $this->getEncodingService(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\GiftCard\Business\Writer\OrderGiftCardsRelationshipWriterInterface
     */
    public function createOrderGiftCardsRelationshipWriter(): OrderGiftCardsRelationshipWriterInterface
    {
        return new OrderGiftCardsRelationshipWriter(
            $this->createSalesOrderItemSaver(),
            $this->createSalesOrderPaymentSaver(),
        );
    }

    public function createGiftCardCartCodeAdder(): GiftCardCartCodeAdderInterface
    {
        return new GiftCardCartCodeAdder();
    }

    public function createGiftCardCartCodeRemover(): GiftCardCartCodeRemoverInterface
    {
        return new GiftCardCartCodeRemover();
    }

    public function createGiftCardCartCodeClearer(): GiftCardCartCodeClearerInterface
    {
        return new GiftCardCartCodeClearer();
    }

    public function createGiftCardCartCodeOperationMessageFinder(): GiftCardCartCodeOperationMessageFinderInterface
    {
        return new GiftCardCartCodeOperationMessageFinder();
    }

    public function createPaymentMapKeyBuilder(): PaymentMapKeyBuilderInterface
    {
        return new PaymentMapKeyBuilder();
    }

    public function createPaymentGiftCardDeleter(): PaymentGiftCardDeleterInterface
    {
        return new PaymentGiftCardDeleter(
            $this->getEntityManager(),
        );
    }

    public function createSalesOrderItemGiftCardDeleter(): SalesOrderItemGiftCardDeleterInterface
    {
        return new SalesOrderItemGiftCardDeleter($this->getEntityManager());
    }

    /**
     * @return array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardCodeCandidateValidationPluginInterface>
     */
    protected function getGiftCardCodeCandidateValidationPlugins()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::GIFT_CARD_CODE_CANDIDATE_VALIDATOR_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardAttributePluginInterface>
     */
    protected function getGiftCardAttributePlugins()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::ATTRIBUTE_PROVIDER_PLUGINS);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getEncodingService()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Cart\MetadataExpanderInterface
     */
    public function createGiftCardMetadataExpander()
    {
        return new MetadataExpander($this->createGiftCardReader());
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Discount\GiftCardDiscountableItemFilterInterface
     */
    public function createGiftCardDiscountableItemFilter()
    {
        return new GiftCardDiscountableItemFilter();
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Payment\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter()
    {
        return new PaymentMethodFilter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Calculation\GiftCardCalculatorInterface
     */
    public function createGiftCardCalculator()
    {
        return new GiftCardCalculator(
            $this->createGiftCardReader(),
            $this->createGiftCardDecisionRuleChecker(),
            $this->createGiftCardActualValueHydrator(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface
     */
    public function createGiftCardActualValueHydrator()
    {
        return new GiftCardActualValueHydrator(
            $this->getGiftCardValueProviderPlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface
     */
    protected function createGiftCardDecisionRuleChecker()
    {
        return new GiftCardDecisionRuleChecker(
            $this->getGiftCardDecisionRulePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface
     */
    protected function getGiftCardValueProviderPlugin()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::GIFT_CARD_VALUE_PROVIDER);
    }

    /**
     * @return array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardDecisionRulePluginInterface>
     */
    protected function getGiftCardDecisionRulePlugins()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::GIFT_CARD_DECISION_RULE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Payment\SalesOrderPreCheckerInterface
     */
    public function createSalesOrderPreChecker()
    {
        return new SalesOrderPreChecker(
            $this->createGiftCardReader(),
            $this->createGiftCardDecisionRuleChecker(),
            $this->createGiftCardActualValueHydrator(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaverInterface
     */
    public function createSalesOrderPaymentSaver()
    {
        return new SalesOrderPaymentSaver(
            $this->getPaymentSaverPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface>
     */
    protected function getPaymentSaverPlugins()
    {
        return $this->getProvidedDependency(GiftCardDependencyProvider::GIFT_CARD_PAYMENT_SAVER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReplacerInterface
     */
    public function createGiftCardReplacer()
    {
        return new GiftCardReplacer(
            $this->createGiftCardReader(),
            $this->createGiftCardCreator(),
            $this->createGiftCardCodeGenerator(),
        );
    }

    /**
     * @deprecated Use {@link createShipmentGroupMethodFilter()} instead.
     *
     * @return \Spryker\Zed\GiftCard\Business\Shipment\ShipmentMethodFilterInterface
     */
    public function createShipmentMethodFilter()
    {
        return new ShipmentMethodFilter($this->getConfig());
    }

    public function createShipmentGroupMethodFilter(): ShipmentGroupMethodFilterInterface
    {
        return new ShipmentGroupMethodFilter(
            $this->createAllowedShipmentMethodGiftCardFilter(),
            $this->createDisallowedShipmentMethodGiftCardFilter(),
            $this->createShipmentMethodGiftCardChecker(),
            $this->createShipmentMethodGiftCardReader(),
        );
    }

    public function createAllowedShipmentMethodGiftCardFilter(): ShipmentMethodGiftCardFilterInterface
    {
        return new ShipmentMethodGiftCardFilter(
            $this->createShipmentMethodCollectionRemover(),
            $this->createShipmentMethodGiftCardAllowedChecker(),
        );
    }

    public function createDisallowedShipmentMethodGiftCardFilter(): ShipmentMethodGiftCardFilterInterface
    {
        return new ShipmentMethodGiftCardFilter(
            $this->createShipmentMethodCollectionRemover(),
            $this->createShipmentMethodGiftCardDisallowedChecker(),
        );
    }

    public function createShipmentMethodCollectionRemover(): ShipmentMethodCollectionRemoverInterface
    {
        return new ShipmentMethodCollectionRemover();
    }

    public function createShipmentMethodGiftCardReader(): ShipmentMethodGiftCardReaderInterface
    {
        return new ShipmentMethodGiftCardReader($this->getConfig());
    }

    public function createShipmentMethodGiftCardChecker(): ShipmentMethodGiftCardCheckerInterface
    {
        return new ShipmentMethodGiftCardChecker();
    }

    public function createShipmentMethodGiftCardAllowedChecker(): ShipmentMethodGiftCardAllowanceCheckerInterface
    {
        return new ShipmentMethodGiftCardAllowedChecker();
    }

    public function createShipmentMethodGiftCardDisallowedChecker(): ShipmentMethodGiftCardAllowanceCheckerInterface
    {
        return new ShipmentMethodGiftCardDisallowedChecker();
    }

    public function createGiftCardItemsChecker(): GiftCardItemsCheckerInterface
    {
        return new GiftCardItemsChecker();
    }

    public function createShipmentGroupSanitizer(): ShipmentGroupsSanitizerInterface
    {
        return new ShipmentGroupsSanitizer($this->createGiftCardItemsChecker());
    }
}
