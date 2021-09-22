<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GhostUnicorns\StockItemUpdateDefaults\Console\Command;

use GhostUnicorns\StockItemUpdateDefaults\Model\CreateCatalogInventoryStockItemDefaults;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockItemUpdateDefaults extends Command
{
    /**
     * @var CreateCatalogInventoryStockItemDefaults
     */
    private $createCatalogInventoryStockItemDefaults;

    /**
     * @param string|null $name
     * @param CreateCatalogInventoryStockItemDefaults $createCatalogInventoryStockItemDefaults
     */
    public function __construct(
        CreateCatalogInventoryStockItemDefaults $createCatalogInventoryStockItemDefaults,
        string $name = null
    ) {
        parent::__construct($name);
        $this->createCatalogInventoryStockItemDefaults = $createCatalogInventoryStockItemDefaults;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('stock-item:update-defaults')
            ->setDescription('Stock Item Update Defaults');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createCatalogInventoryStockItemDefaults->execute();
        $output->writeln('Done! Now please execute the cataloginventort_stock reindex by this command:');
        $output->writeln('bin/magento index:reindex cataloginventory_stock');
    }
}
