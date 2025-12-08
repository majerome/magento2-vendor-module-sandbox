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
use Vendor\Module\Model\ResourceModel\PeopleSkill;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_delete';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param PeopleSkill $peopleSkillResource
     */
    public function __construct(
        private readonly Context           $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter            $filter,
        private readonly PeopleSkill   $peopleSkillResource
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
        $deletedCount = 0;

        foreach ($items as $item) {
            try {
                $relatedSkillsIds = $this->peopleSkillResource->getSkillIds((int)$item->getId());
                if (!empty($relatedSkillsIds)) {
                    throw new LocalizedException(
                        __('This person cannot be deleted because they are associated with one or more skills.')
                    );
                }
                $item->delete();
                $deletedCount++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(
                    __(
                        'An error occurred while deleting the record with ID %1: %2',
                        $item->getId(),
                        $e->getMessage()
                    )
                );
            }
        }
        if ($deletedCount > 0) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $deletedCount)
            );
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*');
    }
}
