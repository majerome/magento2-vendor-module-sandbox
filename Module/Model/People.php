<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class People extends AbstractModel
{
    /**
     * Initialize the People model
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\People::class);
    }
}
