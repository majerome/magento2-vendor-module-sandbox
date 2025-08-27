<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Vendor\Module\Model\ResourceModel\People\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        private readonly Context           $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter            $filter
    ) {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Redirect
     * @throws LocalizedException
     */
    public function execute(): Redirect
    {
        $collection = $this->collectionFactory->create();
        $items = $this->filter->getCollection($collection);
        $itemsSize = $items->getSize();

        foreach ($items as $item) {
            try {
                $item->delete();
                $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $itemsSize));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(
                    __('An error occurred while deleting the record with ID %1: %2', $item->getId(), $e->getMessage())
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __(
                        'An unexpected error occurred while deleting the record with ID %1: %2',
                        $item->getId(),
                        $e->getMessage()
                    )
                );
            }
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*');
    }
}
