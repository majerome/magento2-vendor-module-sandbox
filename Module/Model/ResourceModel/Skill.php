<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Skill extends AbstractDb
{
    /** @var string Main table name */
    public const MAIN_TABLE = 'vendor_module_skill';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'skill_id';

    /**
     * Define main table and primary key field
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
