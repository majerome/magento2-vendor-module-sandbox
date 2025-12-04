<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\Skill;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Vendor\Module\Model\ResourceModel\PeopleSkill;
use Vendor\Module\Api\SkillRepositoryInterface;

class Delete extends Action implements HttpGetActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::skill_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param PeopleSkill $peopleSkillResource
     * @param SkillRepositoryInterface $skillRepository
     */
    public function __construct(
        private readonly Context       $context,
        private readonly PeopleSkill   $peopleSkillResource,
        private readonly SkillRepositoryInterface $skillRepository
    ) {
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
            $skill = $this->skillRepository->getById((int)$skillId);
            if ($skill->getId()) {
                $relatedPeopleIds = $this->peopleSkillResource->getPeopleIds((int)$skillId);
                if (!empty($relatedPeopleIds)) {
                    $this->messageManager->addErrorMessage(
                        __('This skill cannot be deleted because it is associated with one or more people.')
                    );
                    /** @var Redirect $redirect */
                    $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    return $redirect->setPath('*/*');
                }
                $this->skillRepository->delete($skill);
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
