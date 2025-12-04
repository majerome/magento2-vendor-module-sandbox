<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Api\SearchResults;
use Vendor\Module\Api\Data\SkillInterface;
use Vendor\Module\Api\Data\SkillSearchResultsInterface;

class SkillSearchResults extends SearchResults implements SkillSearchResultsInterface
{
    /**
     * Set items
     *
     * @param SkillInterface[] $items
     * @return SkillSearchResultsInterface
     */
    public function setItems(array $items)
    {
        parent::setItems($items);
        return $this;
    }
}
