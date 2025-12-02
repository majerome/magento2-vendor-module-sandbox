<?php

declare(strict_types=1);

namespace Vendor\Module\Block\Adminhtml\Skill\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save implements ButtonProviderInterface
{
    /**
     * Get the button data for the Save button
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                    ],
                ],
                'form-role' => 'save',
            ],
            'sort_order' => 30,
        ];
    }
}
