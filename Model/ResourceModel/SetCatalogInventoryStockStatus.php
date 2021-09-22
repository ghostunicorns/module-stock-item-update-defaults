<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class SetCatalogInventoryStockStatus
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param array $data
     */
    public function execute(array $data)
    {
        $connection = $this->resourceConnection->getConnection();

        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('cataloginventory_stock_status');

        $connection->insert($tableName, $data);
    }
}
