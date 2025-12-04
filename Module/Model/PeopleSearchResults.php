<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Api\SearchResults;
use Vendor\Module\Api\Data\PeopleInterface;
use Vendor\Module\Api\Data\PeopleSearchResultsInterface;

class PeopleSearchResults extends SearchResults implements PeopleSearchResultsInterface
{
    /**
     * Set items
     *
     * @param PeopleInterface[] $items
     * @return PeopleSearchResultsInterface
     */
    public function setItems(array $items)
    {
        parent::setItems($items);
        return $this;
    }
}
