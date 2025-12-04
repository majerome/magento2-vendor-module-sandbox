<?php

declare(strict_types=1);

namespace Vendor\Module\Ui\DataProvider;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Vendor\Module\Model\ResourceModel\People\Collection as PeopleCollection;
use Vendor\Module\Model\ResourceModel\People\CollectionFactory as PeopleCollectionFactory;

class People extends AbstractDataProvider
{
    /**
     * @var array
     */
    public array $loadedData;

    /**
     * @var PeopleCollection
     */
    protected $collection;

    /**
     * Constructor for the People Data Provider
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param PeopleCollectionFactory $collectionFactory
     * @param Json $jsonSerializer
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                            $name,
        string                            $primaryFieldName,
        string                            $requestFieldName,
        protected PeopleCollectionFactory $collectionFactory,
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
                $itemData['people_id'] = $item->getData('people_id');
                $itemData['name'] = $item->getData('name');
                if ($itemData['related_skills']) {
                    $relatedSkills = $this->jsonSerializer->unserialize($item->getData('related_skills'));
                    $itemData['people_skill_ids'] = $relatedSkills['dynamic-rows'];
                    $itemData['people_skill_listing'] = $relatedSkills['listing-rows'];
                }

                $this->loadedData[$item->getData('people_id')] = $itemData;
            }
        }
        return $this->loadedData;
    }
}
