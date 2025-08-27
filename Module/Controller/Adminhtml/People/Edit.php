<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private readonly Context     $context,
        private readonly PageFactory $pageFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Vendor_ProductAttachments::people');
        $page->getConfig()->getTitle()->prepend(__('Edit People'));
        return $page;
    }
}
