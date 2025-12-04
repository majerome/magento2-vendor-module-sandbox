<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;
use Vendor\Module\Api\Data\PeopleInterface;
use Vendor\Module\Api\Data\PeopleSearchResultsInterface;
use Vendor\Module\Api\Data\PeopleSearchResultsInterfaceFactory as PeopleSearchResultsFactory;
use Vendor\Module\Api\PeopleRepositoryInterface;
use Vendor\Module\Model\ResourceModel\People as PeopleResource;
use Vendor\Module\Model\ResourceModel\People\CollectionFactory as PeopleCollectionFactory;

class PeopleRepository implements PeopleRepositoryInterface
{
    /**
     * Constructor
     *
     * @param PeopleFactory $peopleFactory
     * @param PeopleResource $peopleResource
     * @param PeopleSearchResultsFactory $peopleSearchResultsFactory
     * @param PeopleCollectionFactory $peopleCollectionFactory
     * @param MessageManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected PeopleFactory              $peopleFactory,
        protected PeopleResource             $peopleResource,
        protected PeopleSearchResultsFactory $peopleSearchResultsFactory,
        protected PeopleCollectionFactory    $peopleCollectionFactory,
        protected MessageManagerInterface    $messageManager,
        protected LoggerInterface            $logger,
    ) {
    }

    /**
     * Save people.
     *
     * @param PeopleInterface $people
     * @return PeopleInterface
     */
    public function save(PeopleInterface $people)
    {
        try {
            $this->peopleResource->save($people);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('There was a problem saving the people record: %1', $e->getMessage())
            );
            $this->logger->critical($e);
        }
        return $people;
    }

    /**
     * Retrieve people.
     *
     * @param int $id
     * @return PeopleInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $people = $this->peopleFactory->create();
        $this->peopleResource->load($people, $id);
        if (!$people->getId()) {
            throw new NoSuchEntityException(__('People with id %1 does not exist.', $id));
        }
        return $people;
    }

    /**
     * Retrieve peoples matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return PeopleSearchResultsInterface|null
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $peopleCollection = $this->peopleCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $peopleCollection->addFieldToFilter(
                    $filter->getField(),
                    [
                        $condition => $filter->getValue()
                    ]
                );
            }
        }

        foreach ($searchCriteria->getSortOrders() as $sortOrder) {
            $peopleCollection->addOrder(
                $sortOrder->getField(),
                ($sortOrder->getDirection() === 'ASC') ? 'ASC' : 'DESC'
            );
        }

        $peopleCollection->setPageSize($searchCriteria->getPageSize());
        $peopleCollection->setCurPage($searchCriteria->getCurrentPage());

        $peopleSearchResults = $this->peopleSearchResultsFactory->create();
        $peopleSearchResults->setSearchCriteria($searchCriteria);
        $peopleSearchResults->setItems($peopleCollection->getItems());
        $peopleSearchResults->setTotalCount($peopleCollection->getSize());

        return $peopleSearchResults;
    }

    /**
     * Delete people.
     *
     * @param PeopleInterface $people
     * @return bool
     */
    public function delete(PeopleInterface $people)
    {
        try {
            $this->peopleResource->delete($people);
            return true;
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('There was a problem deleting the people record: %1', $e->getMessage())
            );
            $this->logger->critical($e);
            return false;
        }
    }

    /**
     * Delete people by ID.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $people = $this->getById($id);
        return $this->delete($people);
    }
}
