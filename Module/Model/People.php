<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Vendor\Module\Api\Data\PeopleInterface;

class People extends AbstractModel implements PeopleInterface
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

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return PeopleInterface
     */
    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get Related Skills
     *
     * @return string
     */
    public function getRelatedSkills()
    {
        return $this->getData(self::RELATED_SKILLS);
    }

    /**
     * Set Related Skills
     *
     * @param string $jsonRelatedSkills
     * @return PeopleInterface
     */
    public function setRelatedSkills(string $jsonRelatedSkills)
    {
        return $this->setData(self::RELATED_SKILLS, $jsonRelatedSkills);
    }
}
