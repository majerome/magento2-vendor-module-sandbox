<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;
use Vendor\Module\Api\Data\SkillInterface;
use Vendor\Module\Api\Data\SkillSearchResultsInterface;
use Vendor\Module\Api\Data\SkillSearchResultsInterfaceFactory as SkillSearchResultsFactory;
use Vendor\Module\Api\SkillRepositoryInterface;
use Vendor\Module\Model\ResourceModel\Skill as SkillResource;
use Vendor\Module\Model\ResourceModel\Skill\CollectionFactory as SkillCollectionFactory;

class SkillRepository implements SkillRepositoryInterface
{
    /**
     * Constructor
     *
     * @param SkillFactory $skillFactory
     * @param SkillResource $skillResource
     * @param SkillSearchResultsFactory $skillSearchResultsFactory
     * @param SkillCollectionFactory $skillCollectionFactory
     * @param MessageManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected SkillFactory              $skillFactory,
        protected SkillResource             $skillResource,
        protected SkillSearchResultsFactory $skillSearchResultsFactory,
        protected SkillCollectionFactory    $skillCollectionFactory,
        protected MessageManagerInterface   $messageManager,
        protected LoggerInterface           $logger,
    ) {
    }

    /**
     * Save skill.
     *
     * @param SkillInterface $skill
     * @return SkillInterface
     */
    public function save(SkillInterface $skill)
    {
        try {
            $this->skillResource->save($skill);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('There was a problem saving the skill record: %1', $e->getMessage())
            );
            $this->logger->critical($e);
        }
        return $skill;
    }

    /**
     * Retrieve skill.
     *
     * @param int $id
     * @return SkillInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $skill = $this->skillFactory->create();
        $this->skillResource->load($skill, $id);
        if (!$skill->getId()) {
            throw new NoSuchEntityException(__('Skill with id %1 does not exist.', $id));
        }
        return $skill;
    }

    /**
     * Retrieve skills matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SkillSearchResultsInterface|null
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $skillCollection = $this->skillCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $skillCollection->addFieldToFilter(
                    $filter->getField(),
                    [
                        $condition => $filter->getValue()
                    ]
                );
            }
        }

        foreach ($searchCriteria->getSortOrders() as $sortOrder) {
            $skillCollection->addOrder(
                $sortOrder->getField(),
                ($sortOrder->getDirection() === 'ASC') ? 'ASC' : 'DESC'
            );
        }

        $skillCollection->setPageSize($searchCriteria->getPageSize());
        $skillCollection->setCurPage($searchCriteria->getCurrentPage());

        $skillSearchResults = $this->skillSearchResultsFactory->create();
        $skillSearchResults->setSearchCriteria($searchCriteria);
        $skillSearchResults->setItems($skillCollection->getItems());
        $skillSearchResults->setTotalCount($skillCollection->getSize());

        return $skillSearchResults;
    }

    /**
     * Delete skill.
     *
     * @param SkillInterface $skill
     * @return bool
     */
    public function delete(SkillInterface $skill)
    {
        try {
            $this->skillResource->delete($skill);
            return true;
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('There was a problem deleting the skill record: %1', $e->getMessage())
            );
            $this->logger->critical($e);
            return false;
        }
    }

    /**
     * Delete skill by ID.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $skill = $this->getById($id);
        return $this->delete($skill);
    }
}
