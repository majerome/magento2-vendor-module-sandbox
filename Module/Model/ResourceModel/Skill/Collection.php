<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel\Skill;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\Module\Model\Skill;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(Skill::class, \Vendor\Module\Model\ResourceModel\Skill::class);
    }
}
