<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PeopleSkill extends AbstractDb
{
    /** @var string Main table name */
    public const string MAIN_TABLE = 'vendor_module_people_skill';

    /** @var string 1st table primary key field name */
    public const string PEOPLE_ID_FIELD_NAME = 'people_id';

    /** @var string 2nd table primary key field name */
    public const string SKILL_ID_FIELD_NAME = 'skill_id';

    /**
     * Constructor for the PeopleSkill Resource Model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::PEOPLE_ID_FIELD_NAME);
    }

    /**
     * Save skill relations for a given people ID
     *
     * @param int $peopleId
     * @param array $skillIds
     * @return PeopleSkill
     */
    public function saveSkillRelations(int $peopleId, array $skillIds): PeopleSkill
    {
        $connection = $this->getConnection();

        $connection->delete(
            self::MAIN_TABLE,
            [self::PEOPLE_ID_FIELD_NAME . ' = ?' => $peopleId]
        );

        $data = [];
        foreach ($skillIds as $skillId) {
            $data[] = [
                self::PEOPLE_ID_FIELD_NAME => $peopleId,
                self::SKILL_ID_FIELD_NAME => $skillId
            ];
        }

        if (!empty($data)) {
            $connection->insertMultiple(self::MAIN_TABLE, $data);
        }

        return $this;
    }

    /**
     * Get people IDs associated with a given skill ID
     *
     * @param int $skillId
     * @return array
     */
    public function getPeopleIds(int $skillId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(self::MAIN_TABLE, [self::PEOPLE_ID_FIELD_NAME])
            ->where(self::SKILL_ID_FIELD_NAME . ' = ?', $skillId);

        return $connection->fetchCol($select);
    }
}
