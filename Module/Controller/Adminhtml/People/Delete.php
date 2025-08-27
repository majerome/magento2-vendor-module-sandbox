<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Vendor\Module\Model\PeopleFactory;
use Vendor\Module\Model\ResourceModel\People as PeopleResource;

class Delete extends Action implements HttpGetActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param PeopleFactory $peopleFactory
     * @param PeopleResource $peopleResource
     */
    public function __construct(
        private readonly Context          $context,
        private readonly PeopleFactory  $peopleFactory,
        private readonly PeopleResource $peopleResource
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
            $peopleId = $this->getRequest()->getParam('people_id');
            $people = $this->peopleFactory->create();
            $this->peopleResource->load($people, $peopleId);
            if ($people->getData('people_id')) {
                $this->peopleResource->delete($people);
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
