<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class People extends AbstractDb
{
    /** @var string Main table name */
    public const string MAIN_TABLE = 'vendor_module_people';

    /** @var string Main table primary key field name */
    public const string ID_FIELD_NAME = 'people_id';

    /**
     * Constructor for the People Resource Model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
