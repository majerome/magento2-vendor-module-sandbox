<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Serialize\Serializer\Json;

class Skill extends AbstractDb
{
    /** @var string Main table name */
    public const string MAIN_TABLE = 'vendor_module_skill';

    /** @var string Main table primary key field name */
    public const string ID_FIELD_NAME = 'skill_id';

    /**
     * Constructor for the People Resource Model
     *
     * @param Context $context
     * @param Json $jsonSerializer
     * @param PeopleSkill $peopleSkillResource
     * @param string|null $connectionName
     */
    public function __construct(
        Context                        $context,
        protected readonly Json        $jsonSerializer,
        protected readonly PeopleSkill $peopleSkillResource,
        ?string                        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Define main table and primary key field
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    /**
     * Hydrate data before save
     *
     * @param AbstractModel $object
     * @return Skill
     */
    protected function _beforeSave(AbstractModel $object): Skill
    {
        if (array_key_exists('skill_people_ids', $object->getData())) {
            $people = $object->getData('skill_people_ids');
            if (is_array($people) && empty($people)) {
                $object->setData('assigned_people');
            } else {
                $object->setData(
                    'assigned_people',
                    $this->jsonSerializer->serialize([
                        'dynamic-rows' => $object->getData('skill_people_ids'),
                        'listing-rows' => $object->getData('skill_people_listing')
                    ])
                );
            }
        }
        return parent::_beforeSave($object);
    }

    /**
     * After save handler
     *
     * @param AbstractModel $object
     * @return Skill
     */
    protected function _afterSave(AbstractModel $object): Skill
    {
        $skillId = (int)$object->getData('skill_id');
        $this->_collectAndSavePeopleRelations($skillId, $object);
        return parent::_afterSave($object);
    }

    /**
     * Collect and save people relations
     *
     * @param int $skillId
     * @param AbstractModel $object
     * @return void
     */
    private function _collectAndSavePeopleRelations(int $skillId, AbstractModel $object): void
    {
        $hasPeople = $object->getData('skill_people_ids');
        if (!is_array($hasPeople)) {
            return;
        }
        $peopleIdsGrid = [];
        foreach ($hasPeople as $people) {
            if (isset($people['id'])) {
                $peopleIdsGrid[] = (int)$people['id'];
            }
        }

        $this->peopleSkillResource->savePeopleRelations($skillId, $peopleIdsGrid);
    }
}
