<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Vendor\Module\Api\Data\SkillInterface;

class Skill extends AbstractModel implements SkillInterface
{
    /**
     * Define resource model
     *
     * @throws LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Skill::class);
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
     * @return SkillInterface
     */
    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }
}
