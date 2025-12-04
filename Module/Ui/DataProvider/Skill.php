<?php

declare(strict_types=1);

namespace Vendor\Module\Ui\DataProvider;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Vendor\Module\Model\ResourceModel\Skill\Collection as SkillCollection;
use Vendor\Module\Model\ResourceModel\Skill\CollectionFactory as SkillCollectionFactory;

class Skill extends AbstractDataProvider
{
    /**
     * @var array
     */
    public array $loadedData;

    /**
     * @var SkillCollection
     */
    protected $collection;

    /**
     * Constructor for the Skill Data Provider
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param SkillCollectionFactory $collectionFactory
     * @param Json $jsonSerializer
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                            $name,
        string                            $primaryFieldName,
        string                            $requestFieldName,
        protected SkillCollectionFactory $collectionFactory,
        protected readonly Json           $jsonSerializer,
        array                             $meta = [],
        array                             $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * GetData function
     *
     * @return array
     */
    public function getData(): array
    {
        if (!isset($this->loadedData)) {
            $this->loadedData = [];

            foreach ($this->collection->getItems() as $item) {
                $itemData = $item->getData();
                $itemData['skill_id'] = $item->getData('skill_id');
                $itemData['name'] = $item->getData('name');

                if ($itemData['assigned_people']) {
                    $relatedSkills = $this->jsonSerializer->unserialize($item->getData('assigned_people'));
                    $itemData['skill_people_ids'] = $relatedSkills['dynamic-rows'];
                    $itemData['skill_people_listing'] = $relatedSkills['listing-rows'];
                }

                $this->loadedData[$item->getData('skill_id')] = $itemData;
            }
        }
        return $this->loadedData;
    }
}
