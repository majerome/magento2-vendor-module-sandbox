<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Vendor\Module\Model\PeopleValidator;
use Vendor\Module\Api\PeopleRepositoryInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_save';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param PeopleRepositoryInterface $peopleRepository
     */
    public function __construct(
        private readonly Context          $context,
        private readonly JsonFactory      $jsonFactory,
        private readonly PeopleRepositoryInterface $peopleRepository,
    ) {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Json
     */
    public function execute(): Json
    {
        $json = $this->jsonFactory->create();
        $messages = [];
        $error = false;
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $items = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($items)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($items as $item) {
                $peopleId = $item['people_id'];
                try {
                    $people = $this->peopleRepository->getById($peopleId);
                    $people->addData($item);
                    $this->peopleRepository->save($people);
                } catch (Exception $e) {
                    $messages[] = __("Something went wrong while saving item $peopleId: ") . $e->getMessage();
                    $error = true;
                }
            }
        }

        return $json->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
