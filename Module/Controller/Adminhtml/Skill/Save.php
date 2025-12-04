<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\Skill;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Vendor\Module\Api\SkillRepositoryInterface;
use Vendor\Module\Model\SkillFactory;

class Save extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::skill_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param SkillFactory $skillFactory
     * @param SkillRepositoryInterface $skillRepository
     */
    public function __construct(
        Action\Context                            $context,
        private readonly SkillFactory             $skillFactory,
        private readonly SkillRepositoryInterface $skillRepository,
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

        $isExistingSkill = (bool)$post->skill_id;
        $skill = $this->skillFactory->create();
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($isExistingSkill) {
            $skill = $this->skillRepository->getById($post->skill_id);
            if (!$skill->getData('skill_id')) {
                $this->messageManager->addErrorMessage(__('That record no longer exists.'));
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->skill_id);
        }

        $arrayPost = $post->toArray();
        if (!array_key_exists('skill_people_ids', $arrayPost)) {
            $arrayPost['skill_people_ids'] = [];
        }

        $skill->setData(array_merge($skill->getData(), $post->toArray()));

        try {
            $this->skillRepository->save($skill);
            $this->messageManager->addSuccessMessage(__('The record has been saved.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the record: %1', $e->getMessage()));
            if ($isExistingSkill) {
                return $redirect->setPath('*/*/edit', [
                    'skill_id' => $skill->getData('skill_id'),
                ]);
            } else {
                return $redirect->setPath('*/*/new');
            }
        }

        // On success, redirect back to the admin grid
        return $redirect->setPath('*/*/index');
    }
}
