<?php

declare(strict_types=1);

namespace Vendor\Module\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\Module\Api\PeopleRepositoryInterface;
use Vendor\Module\Api\PeopleSkillSyncInterface;
use Vendor\Module\Api\SkillRepositoryInterface;
use Vendor\Module\Model\Json\AssignedPeopleJsonBuilder;
use Vendor\Module\Model\Json\RelatedSkillsJsonBuilder;
use Vendor\Module\Model\ResourceModel\PeopleSkill;

class PeopleSkillSync implements PeopleSkillSyncInterface
{
    /**
     * Constructor for PeopleSkillSync
     *
     * @param PeopleRepositoryInterface $peopleRepository
     * @param SkillRepositoryInterface $skillRepository
     * @param PeopleSkill $peopleSkillResource
     * @param RelatedSkillsJsonBuilder $relatedSkillsJsonBuilder
     * @param AssignedPeopleJsonBuilder $assignedPeopleJsonBuilder
     */
    public function __construct(
        private readonly PeopleRepositoryInterface $peopleRepository,
        private readonly SkillRepositoryInterface  $skillRepository,
        private readonly PeopleSkill               $peopleSkillResource,
        private readonly RelatedSkillsJsonBuilder  $relatedSkillsJsonBuilder,
        private readonly AssignedPeopleJsonBuilder $assignedPeopleJsonBuilder
    ) {
    }

    /**
     * Synchronize skills for a given people
     *
     * @param int $peopleId
     * @param int[] $skillIds
     * @return void
     */
    public function syncPeopleSkills(int $peopleId, array $skillIds): void
    {
        $oldSkillIds = $this->peopleSkillResource->getSkillIds($peopleId);

        $this->peopleSkillResource->updatePeopleSkills($peopleId, $skillIds);

        try {
            $people = $this->peopleRepository->getById($peopleId);
            if (empty($skillIds)) {
                $people->setData('related_skills');
            } else {
                $people->setData(
                    'related_skills',
                    $this->relatedSkillsJsonBuilder->build($skillIds)
                );
            }
            $this->peopleRepository->save($people);
        } catch (NoSuchEntityException|LocalizedException) {
            return;
        }

        foreach ($oldSkillIds as $oldSkillId) {
            try {
                $skill = $this->skillRepository->getById($oldSkillId);
                $peopleIds = $this->peopleSkillResource->getPeopleIds((int)$oldSkillId) ?? [];
                $skill->setData(
                    'assigned_people',
                    empty($peopleIds) ? null : $this->assignedPeopleJsonBuilder->build($peopleIds)
                );
                $this->skillRepository->save($skill);
            } catch (NoSuchEntityException|LocalizedException) {
                continue;
            }
        }

        foreach ($skillIds as $skillId) {
            try {
                $skill = $this->skillRepository->getById($skillId);
                $peopleIds = $this->peopleSkillResource->getPeopleIds((int)$skillId) ?? [];
                if (empty($peopleIds)) {
                    $skill->setData('assigned_people');
                } else {
                    $skill->setData(
                        'assigned_people',
                        $this->assignedPeopleJsonBuilder->build($peopleIds)
                    );
                }
                $this->skillRepository->save($skill);
            } catch (NoSuchEntityException|LocalizedException) {
                continue;
            }
        }
    }

    /**
     * Synchronize people for a given skill
     *
     * @param int $skillId
     * @param int[] $peopleIds
     * @return void
     */
    public function syncSkillPeople(int $skillId, array $peopleIds): void
    {
        $oldPeopleIds = $this->peopleSkillResource->getPeopleIds($skillId);

        $this->peopleSkillResource->updateSkillPeople($skillId, $peopleIds);

        try {
            $skill = $this->skillRepository->getById($skillId);
            if (empty($peopleIds)) {
                $skill->setData('assigned_people');
            } else {
                $skill->setData(
                    'assigned_people',
                    $this->assignedPeopleJsonBuilder->build($peopleIds)
                );
            }
            $this->skillRepository->save($skill);
        } catch (NoSuchEntityException|LocalizedException) {
            return;
        }

        foreach ($oldPeopleIds as $oldPeopleId) {
            try {
                $people = $this->peopleRepository->getById($oldPeopleId);
                $skillIds = $this->peopleSkillResource->getSkillIds((int)$oldPeopleId) ?? [];
                $people->setData(
                    'related_skills',
                    empty($skillIds) ? null : $this->relatedSkillsJsonBuilder->build($skillIds)
                );
                $this->peopleRepository->save($people);
            } catch (NoSuchEntityException|LocalizedException) {
                continue;
            }
        }

        foreach ($peopleIds as $peopleId) {
            try {
                $people = $this->peopleRepository->getById($peopleId);
                $skillIds = $this->peopleSkillResource->getSkillIds((int)$peopleId) ?? [];
                if (empty($skillIds)) {
                    $people->setData('related_skills');
                } else {
                    $people->setData(
                        'related_skills',
                        $this->relatedSkillsJsonBuilder->build($skillIds)
                    );
                }
                $this->peopleRepository->save($people);
            } catch (NoSuchEntityException|LocalizedException) {
                continue;
            }
        }
    }
}
