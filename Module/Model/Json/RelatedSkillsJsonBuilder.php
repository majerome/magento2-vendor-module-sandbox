<?php

declare(strict_types=1);

namespace Vendor\Module\Model\Json;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Vendor\Module\Api\SkillRepositoryInterface;

class RelatedSkillsJsonBuilder
{
    /**
     * Constructor for RelatedSkillsJsonBuilder
     *
     * @param SkillRepositoryInterface $skillRepository
     * @param Json $jsonSerializer
     */
    public function __construct(
        private readonly SkillRepositoryInterface $skillRepository,
        private readonly Json $jsonSerializer
    ) {
    }

    /**
     * Build JSON representation of related skills
     *
     * @param int[] $skillIds
     * @return string|null
     */
    public function build(array $skillIds): ?string
    {
        $dynamicRows = [];
        $listingRows = [];

        foreach ($skillIds as $index => $skillId) {
            try {
                $skill = $this->skillRepository->getById($skillId);
                $dynamicRows[] = [
                    'id' => $skill->getId(),
                    'name' => $skill->getName(),
                    'position' => $index + 1,
                    'record_id' => $skill->getId(),
                    'initialize' => 'true'
                ];
                $listingRows[] = [
                    'skill_id' => $skill->getId()
                ];
            } catch (NoSuchEntityException | LocalizedException) {
                continue;
            }
        }

        $result = [
            'dynamic-rows' => $dynamicRows,
            'listing-rows' => $listingRows
        ];

        return $this->jsonSerializer->serialize($result);
    }
}
