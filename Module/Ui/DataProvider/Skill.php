<?php

declare(strict_types=1);

namespace Vendor\Module\Ui\DataProvider;

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
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                            $name,
        string                            $primaryFieldName,
        string                            $requestFieldName,
        protected SkillCollectionFactory $collectionFactory,
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
                $this->loadedData[$item->getData('skill_id')] = $itemData;
            }
        }
        return $this->loadedData;
    }
}
