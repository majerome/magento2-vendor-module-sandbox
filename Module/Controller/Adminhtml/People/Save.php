<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\People;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Vendor\Module\Model\PeopleFactory;
use Vendor\Module\Api\PeopleRepositoryInterface;

class Save extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::people_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PeopleFactory $peopleFactory
     * @param PeopleRepositoryInterface $peopleRepository
     */
    public function __construct(
        Action\Context                  $context,
        private readonly PeopleFactory  $peopleFactory,
        private readonly PeopleRepositoryInterface $peopleRepository,
    ) {
        parent::__construct($context);
    }

    /**
     * Execute a controller action.
     *
     * @return Redirect
     * @throws LocalizedException
     * @throws NoSuchEntityException
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
            $people = $this->peopleRepository->getById($post->people_id);
            if (!$people->getData('people_id')) {
                $this->messageManager->addErrorMessage(__('That record no longer exists.'));
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->people_id);
        }

        $arrayPost = $post->toArray();
        if (!array_key_exists('people_skill_ids', $arrayPost)) {
            $arrayPost['people_skill_ids'] = [];
        }

        $people->setData(array_merge($people->getData(), $post->toArray()));

        try {
            $this->peopleRepository->save($people);
            $this->messageManager->addSuccessMessage(__('The record has been saved.'));
        } catch (Exception $e) {
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
