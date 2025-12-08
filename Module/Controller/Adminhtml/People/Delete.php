<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Vendor\Module\Api\PeopleRepositoryInterface;
use Vendor\Module\Model\ResourceModel\PeopleSkill;

class Delete extends Action implements HttpGetActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param PeopleSkill $peopleSkillResource
     * @param PeopleRepositoryInterface $peopleRepository
     */
    public function __construct(
        private readonly Context          $context,
        private readonly PeopleSkill   $peopleSkillResource,
        private readonly PeopleRepositoryInterface  $peopleRepository,
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
            $peopleId = $this->getRequest()->getParam('people_id');
            $people = $this->peopleRepository->getById($peopleId);
            if ($people->getId()) {
                $relatedSkillIds = $this->peopleSkillResource->getSkillIds((int)$peopleId);
                if (!empty($relatedSkillIds)) {
                    $this->messageManager->addErrorMessage(
                        __('This person cannot be deleted because they are associated with one or more skills.')
                    );
                    /** @var Redirect $redirect */
                    $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    return $redirect->setPath('*/*');
                }
                $this->peopleRepository->delete($people);
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
