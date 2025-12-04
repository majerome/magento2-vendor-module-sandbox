<?php

declare(strict_types=1);

namespace Vendor\Module\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for skill search results.
 * @api
 * @since 1.0.0
 */
interface SkillSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get skill list.
     *
     * @return SkillInterface[]
     */
    public function getItems();

    /**
     * Set skill list.
     *
     * @param SkillInterface[] $items
     * @return SkillSearchResultsInterface
     */
    public function setItems(array $items);
}
