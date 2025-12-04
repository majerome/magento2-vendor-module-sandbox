<?php

declare(strict_types=1);

namespace Vendor\Module\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\Module\Api\Data\SkillInterface;
use Vendor\Module\Api\Data\SkillSearchResultsInterface;

/**
 * Skill CRUD interface.
 * @api
 * @since 1.0.0
 */
interface SkillRepositoryInterface
{
    /**
     * Save skill.
     *
     * @param SkillInterface $skill
     * @return SkillInterface
     * @throws LocalizedException
     */
    public function save(SkillInterface $skill);

    /**
     * Retrieve skill.
     *
     * @param int $id
     * @return SkillInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve skills matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SkillSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete skill.
     *
     * @param SkillInterface $skill
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(SkillInterface $skill);

    /**
     * Delete skill by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id);
}
