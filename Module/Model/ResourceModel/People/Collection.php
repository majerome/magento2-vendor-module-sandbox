<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel\People;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\Module\Model\People;

class Collection extends AbstractCollection
{
    /**
     * Initialize collection model and resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(People::class, \Vendor\Module\Model\ResourceModel\People::class);
    }
}
