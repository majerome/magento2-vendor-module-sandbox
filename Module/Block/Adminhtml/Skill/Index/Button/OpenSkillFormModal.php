<?php

declare(strict_types=1);

namespace Vendor\Module\Block\Adminhtml\Skill\Index\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class OpenSkillFormModal implements ButtonProviderInterface
{
    /**
     * Get the button data for the Open Modal button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Add New Skill (Modal)'),
            'class' => 'action-secondary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'skill_listing.skill_listing.skill_form_modal.insert_skill_form',
                                'actionName' => 'destroyInserted'
                            ],
                            [
                                'targetName' => 'skill_listing.skill_listing.skill_form_modal',
                                'actionName' => 'openModal'
                            ],
                            [
                                'targetName' => 'skill_listing.skill_listing.skill_form_modal.insert_skill_form',
                                'actionName' => 'render'
                            ],
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 10
        ];
    }
}
