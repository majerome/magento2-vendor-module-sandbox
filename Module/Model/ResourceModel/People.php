<?php

declare(strict_types=1);

namespace Vendor\Module\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Serialize\Serializer\Json;

class People extends AbstractDb
{
    /** @var string Main table name */
    public const string MAIN_TABLE = 'vendor_module_people';

    /** @var string Main table primary key field name */
    public const string ID_FIELD_NAME = 'people_id';

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
     * Constructor for the People Resource Model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    /**
     * Hydrate data before save
     *
     * @param AbstractModel $object
     * @return People
     */
    protected function _beforeSave(AbstractModel $object): People
    {
        if (array_key_exists('people_skill_ids', $object->getData())) {
            $skills = $object->getData('people_skill_ids');
            if (is_array($skills) && empty($skills)) {
                $object->setData('related_skills');
            } else {
                $object->setData(
                    'related_skills',
                    $this->jsonSerializer->serialize([
                        'dynamic-rows' => $object->getData('people_skill_ids'),
                        'listing-rows' => $object->getData('people_skill_listing')
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
     * @return People
     */
    protected function _afterSave(AbstractModel $object): People
    {
        $peopleId = (int)$object->getData('people_id');
        $this->_collectAndSaveSkillRelations($peopleId, $object);
        return parent::_afterSave($object);
    }

    /**
     * Collect and save skill relations
     *
     * @param int $peopleId
     * @param AbstractModel $object
     * @return void
     */
    private function _collectAndSaveSkillRelations(int $peopleId, AbstractModel $object): void
    {
        $hasSkills = $object->getData('people_skill_ids');
        if (!is_array($hasSkills)) {
            return;
        }
        $skillIdsGrid = [];
        foreach ($hasSkills as $skill) {
            if (isset($skill['id'])) {
                $skillIdsGrid[] = (int)$skill['id'];
            }
        }

        $this->peopleSkillResource->saveSkillRelations($peopleId, $skillIdsGrid);
    }
}
