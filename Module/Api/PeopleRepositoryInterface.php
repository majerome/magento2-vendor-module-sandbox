<?php

declare(strict_types=1);

namespace Vendor\Module\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\Module\Api\Data\PeopleInterface;
use Vendor\Module\Api\Data\PeopleSearchResultsInterface;

/**
 * People CRUD interface.
 * @api
 * @since 1.0.0
 */
interface PeopleRepositoryInterface
{
    /**
     * Save people.
     *
     * @param PeopleInterface $people
     * @return PeopleInterface
     * @throws LocalizedException
     */
    public function save(PeopleInterface $people);

    /**
     * Retrieve people.
     *
     * @param int $id
     * @return PeopleInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve peoples matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return PeopleSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete people.
     *
     * @param PeopleInterface $people
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(PeopleInterface $people);

    /**
     * Delete people by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id);
}
