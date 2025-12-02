<?php

declare(strict_types=1);

namespace Vendor\Module\Ui\Component\Skill\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface              $context,
        UiComponentFactory            $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array                         $components = [],
        array                         $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            if (!isset($item['skill_id'])) {
                continue;
            }

            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl('module/skill/edit', [
                        'skill_id' => $item['skill_id'],
                    ]),
                    'label' => __('Edit'),
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl('module/skill/delete', [
                        'skill_id' => $item['skill_id'],
                    ]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete Skill Id %1', $item['skill_id']),
                        'message' => __("Are you sure you want to delete Skill Id %1?", $item['skill_id'])
                    ],
                ],
            ];
        }

        return $dataSource;
    }
}
