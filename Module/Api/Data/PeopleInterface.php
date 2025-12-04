<?php


declare(strict_types=1);

namespace Vendor\Module\Api\Data;

/**
 * People Data Model interface.
 * @api
 * @since 1.0.0
 */
interface PeopleInterface
{
    public const string ID = 'id';
    public const string NAME = 'name';
    public const string RELATED_SKILLS = 'related_skills';

    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * Get name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name);

    /**
     * Get related skills.
     *
     * @return string
     */
    public function getRelatedSkills();

    /**
     * Set name.
     *
     * @param string $jsonRelatedSkills
     * @return $this
     */
    public function setRelatedSkills(string $jsonRelatedSkills);
}
