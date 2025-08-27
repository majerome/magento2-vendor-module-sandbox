<?php

declare(strict_types=1);

namespace Vendor\Module\Block\Adminhtml\People\Index\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class OpenPeopleFormModal implements ButtonProviderInterface
{
    /**
     * Get the button data for the Open Modal button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Open People Form Modal'),
            'class' => 'action-secondary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'modal_people_form',
                                'actionName' => 'destroyInserted'
                            ],
                            [
                                'targetName' => 'people_listing.people_listing.people_form_modal',
                                'actionName' => 'openModal'
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 10
        ];
    }
}
