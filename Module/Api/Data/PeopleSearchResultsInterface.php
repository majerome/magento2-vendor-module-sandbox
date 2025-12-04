<?php

declare(strict_types=1);

namespace Vendor\Module\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for people search results.
 * @api
 * @since 1.0.0
 */
interface PeopleSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get people list.
     *
     * @return PeopleInterface[]
     */
    public function getItems();

    /**
     * Set people list.
     *
     * @param PeopleInterface[] $items
     * @return PeopleSearchResultsInterface
     */
    public function setItems(array $items);
}
