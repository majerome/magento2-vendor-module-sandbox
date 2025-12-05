<?php

declare(strict_types=1);

namespace Vendor\Module\Model\Json;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Vendor\Module\Api\PeopleRepositoryInterface;

class AssignedPeopleJsonBuilder
{
    /**
     * Constructor for AssignedPeopleJsonBuilder
     *
     * @param PeopleRepositoryInterface $peopleRepository
     * @param Json $jsonSerializer
     */
    public function __construct(
        private readonly PeopleRepositoryInterface $peopleRepository,
        private readonly Json $jsonSerializer
    ) {
    }

    /**
     * Build JSON representation of assigned people
     *
     * @param int[] $peopleIds
     * @return string|null
     */
    public function build(array $peopleIds): ?string
    {
        $dynamicRows = [];
        $listingRows = [];

        foreach ($peopleIds as $index => $peopleId) {
            try {
                $people = $this->peopleRepository->getById($peopleId);
                $dynamicRows[] = [
                    'id' => $people->getId(),
                    'name' => $people->getName(),
                    'position' => $index + 1,
                    'record_id' => $people->getId(),
                    'initialize' => 'true'
                ];
                $listingRows[] = [
                    'people_id' => $people->getId()
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
