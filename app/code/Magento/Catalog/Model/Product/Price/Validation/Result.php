<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Catalog\Model\Product\Price\Validation;

/**
 * Validation Result is used to aggregate errors that occurred during price update.
 */
class Result
{
    /**
     * @var \Magento\Catalog\Api\Data\PriceUpdateResultInterfaceFactory
     */
    private $priceUpdateResultFactory;

    /**
     * Failed items.
     *
     * @var array
     */
    private $failedItems = [];

    /**
     * @param \Magento\Catalog\Api\Data\PriceUpdateResultInterfaceFactory $priceUpdateResultFactory
     */
    public function __construct(
        \Magento\Catalog\Api\Data\PriceUpdateResultInterfaceFactory $priceUpdateResultFactory
    ) {
        $this->priceUpdateResultFactory = $priceUpdateResultFactory;
    }

    /**
     * Add failed price identified, message and message parameters, that occurred during price update.
     *
     * @param int $id Failed price identified.
     * @param string $message Failure reason message.
     * @param array $parameters (optional). Placeholder values in ['placeholder key' => 'placeholder value'] format
     * for failure reason message.
     * @return void
     */
    public function addFailedItem($id, $message, array $parameters = [])
    {
        $this->failedItems[$id][] = [
            'message' => $message,
            'parameters' => $parameters
        ];
    }

    /**
     * Get ids of rows, that contained errors during price update.
     *
     * @return int[]
     */
    public function getFailedRowIds()
    {
        return $this->failedItems ? array_keys($this->failedItems) : [];
    }

    /**
     * Get price update errors, that occurred during price update.
     *
     * @return \Magento\Catalog\Api\Data\PriceUpdateResultInterface[]
     */
    public function getFailedItems()
    {
        $failedItems = [];

        foreach ($this->failedItems as $items) {
            foreach ($items as $failedRecord) {
                $resultItem = $this->priceUpdateResultFactory->create();
                $resultItem->setMessage($failedRecord['message']);
                $resultItem->setParameters($failedRecord['parameters']);
                $failedItems[] = $resultItem;
            }
        }

        return $failedItems;
    }
}
