<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\StockItemUpdateDefaults\Model;

use GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel\GetInventorySourceItemBySku;
use GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel\GetProductSequenceIds;
use GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel\SetCatalogInventoryStockItem;
use GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel\SetCatalogInventoryStockStatus;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\CatalogInventory\Model\ResourceModel\Stock\Item;
use Magento\CatalogInventory\Model\Stock\ItemFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

class CreateCatalogInventoryStockItemDefaults
{
    /**
     * @var SetAreaCode
     */
    private $setAreaCode;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var Item
     */
    private $itemResource;

    /**
     * @var GetProductSequenceIds
     */
    private $getProductSequenceIds;

    /**
     * @var SetCatalogInventoryStockItem
     */
    private $setCatalogInventoryStockItem;

    /**
     * @var Product
     */
    private $productResource;

    /**
     * @var GetInventorySourceItemBySku
     */
    private $getInventorySourceItemBySku;

    /**
     * @var SetCatalogInventoryStockStatus
     */
    private $setCatalogInventoryStockStatus;

    /**
     * @param SetAreaCode $setAreaCode
     * @param ItemFactory $itemFactory
     * @param Item $itemResource
     * @param GetProductSequenceIds $getProductSequences
     * @param SetCatalogInventoryStockItem $setCatalogInventoryStockItem
     * @param GetInventorySourceItemBySku $getInventorySourceItemBySku
     * @param SetCatalogInventoryStockStatus $setCatalogInventoryStockStatus
     * @param Product $productResource
     */
    public function __construct(
        SetAreaCode $setAreaCode,
        ItemFactory $itemFactory,
        Item $itemResource,
        GetProductSequenceIds $getProductSequences,
        SetCatalogInventoryStockItem $setCatalogInventoryStockItem,
        GetInventorySourceItemBySku $getInventorySourceItemBySku,
        SetCatalogInventoryStockStatus $setCatalogInventoryStockStatus,
        Product $productResource
    ) {
        $this->setAreaCode = $setAreaCode;
        $this->itemFactory = $itemFactory;
        $this->itemResource = $itemResource;
        $this->getProductSequenceIds = $getProductSequences;
        $this->setCatalogInventoryStockItem = $setCatalogInventoryStockItem;
        $this->productResource = $productResource;
        $this->getInventorySourceItemBySku = $getInventorySourceItemBySku;
        $this->setCatalogInventoryStockStatus = $setCatalogInventoryStockStatus;
    }

    /**
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->setAreaCode->execute('adminhtml');

        $sequenceIds = $this->getProductSequenceIds->execute();

        foreach ($sequenceIds as $sequence) {
            $itemId = (int)$sequence['sequence_value'];

            $item = $this->itemFactory->create();
            $this->itemResource->load($item, $itemId, 'product_id');
            if ($item->getId()) {
                continue;
            }

            $sku = current($this->productResource->getProductsSku([$itemId]))['sku'];
            $inventorySourceItem = $this->getInventorySourceItemBySku->execute($sku);

            $stockItemData = [
                'product_id' => $itemId,
                'stock_id' => 1,
                'website_id' => 0,
                'qty' => $inventorySourceItem['quantity'],
                'max_sale_qty' => 10000.0000,
                'is_in_stock' => $inventorySourceItem['status'],
                'notify_stock_qty' => 1.0000,
                'manage_stock' => 1,
                'qty_increments' => 1.0000
            ];
            $this->setCatalogInventoryStockItem->execute($stockItemData);

            $stockStatusData = [
                'product_id' => $itemId,
                'website_id' => 0,
                'stock_id' => 1,
                'qty' => $inventorySourceItem['quantity'],
                'stock_status' => $inventorySourceItem['status']
            ];
            $this->setCatalogInventoryStockStatus->execute($stockStatusData);
        }
    }
}
