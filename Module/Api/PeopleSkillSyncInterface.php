<?php

declare(strict_types=1);

namespace Vendor\Module\Api;

interface PeopleSkillSyncInterface
{
    /**
     * Synchronize skills for a given people
     *
     * @param int $peopleId
     * @param int[] $skillIds
     * @return void
     */
    public function syncPeopleSkills(int $peopleId, array $skillIds): void;

    /**
     * Synchronize people for a given skill
     *
     * @param int $skillId
     * @param int[] $peopleIds
     * @return void
     */
    public function syncSkillPeople(int $skillId, array $peopleIds): void;
}
