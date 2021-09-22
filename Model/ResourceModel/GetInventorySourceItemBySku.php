<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetInventorySourceItemBySku
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
     * @param string $sku
     * @return array
     */
    public function execute(string $sku): array
    {
        $connection = $this->resourceConnection->getConnection();

        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('inventory_source_item');

        $where = $connection->quoteInto('sku = ?', $sku) .
            $connection->quoteInto(' AND source_code = ?', 'default');

        $qry = $connection->select()
            ->from($tableName)
            ->where($where);

        return $connection->fetchRow($qry);
    }
}
