# Description

This module fix the saleable qty after product a SYSTEM->Import->Stock Sources import

# Install

`composer require ghostunicorns/module-stock-item-update-defaults`

# How to use

First run:

`bin/magento stock-item:update-defaults`

Then:

`bin/magento index:reset cataloginventory_stock`

And finally:

`bin/magento index:reindex cataloginventory_stock`

Now your products should have the minimal cataloginventory data. 

# Contribution

Yes, of course you can contribute sending a pull request to propose improvements and fixes.

