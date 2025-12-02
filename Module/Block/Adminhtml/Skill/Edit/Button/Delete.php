<?php

declare(strict_types=1);

namespace Vendor\Module\Block\Adminhtml\Skill\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get the button data for the Delete button
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];

        if ($this->getSkillId()) {
            $data = [
                'label' => __('Delete Skill'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Get the URL for the delete action
     *
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['skill_id' => $this->getSkillId()]);
    }
}
