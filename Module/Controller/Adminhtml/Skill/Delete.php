<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\Skill;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Vendor\Module\Model\SkillFactory;
use Vendor\Module\Model\ResourceModel\Skill as SkillResource;

class Delete extends Action implements HttpGetActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::skill_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param SkillFactory $skillFactory
     * @param SkillResource $skillResource
     */
    public function __construct(
        private readonly Context          $context,
        private readonly SkillFactory  $skillFactory,
        private readonly SkillResource $skillResource
    )
    {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $skillId = $this->getRequest()->getParam('skill_id');
            $skill = $this->skillFactory->create();
            $this->skillResource->load($skill, $skillId);
            if ($skill->getData('skill_id')) {
                $this->skillResource->delete($skill);
                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*');
    }
}
