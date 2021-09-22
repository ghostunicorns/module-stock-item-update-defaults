<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\StockItemUpdateDefaults\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetProductSequenceIds
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
     * @return array
     */
    public function execute(): array
    {
        $connection = $this->resourceConnection->getConnection();

        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('sequence_product');

        $qry = $connection->select()
            ->from($tableName);

        return $connection->fetchAll($qry);
    }
}
