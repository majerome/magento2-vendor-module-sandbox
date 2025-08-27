<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Http\Discovery\Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Vendor\Module\Model\PeopleFactory;
use Vendor\Module\Model\ResourceModel\People as PeopleResource;

class Save extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PeopleFactory $peopleFactory
     * @param PeopleResource $peopleResource
     */
    public function __construct(
        Action\Context                     $context,
        private readonly PeopleFactory    $peopleFactory,
        private readonly PeopleResource    $peopleResource
    ) {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Redirect
     * @throws NotFoundException|AlreadyExistsException
     */
    public function execute(): Redirect
    {
        /* @var Request $request */
        $request = $this->getRequest();
        $post = $request->getPost();

        $isExistingPeople = (bool)$post->people_id;
        $people = $this->peopleFactory->create();
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($isExistingPeople) {
            try {
                $this->peopleResource->load($people, $post->people_id);
                if (!$people->getData('people_id')) {
                    throw new NotFoundException(__('This record no longer exists.'));
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->people_id);
        }

        $people->setData(array_merge($people->getData(), $post->toArray()));

        try {
            $this->peopleResource->save($people);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the record: %1', $e->getMessage()));
            if ($isExistingPeople) {
                return $redirect->setPath('*/*/edit', [
                    'people_id' => $people->getData('people_id'),
                ]);
            } else {
                return $redirect->setPath('*/*/new');
            }
        }

        // On success, redirect back to the admin grid
        return $redirect->setPath('*/*/index');
    }
}
