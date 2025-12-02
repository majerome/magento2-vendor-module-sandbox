<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Model\AbstractModel;

class Skill extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Skill::class);
    }
}
