<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\Skill;

use Http\Discovery\Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Vendor\Module\Model\SkillFactory;
use Vendor\Module\Model\ResourceModel\Skill as SkillResource;

class Save extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::skill_save';

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param SkillFactory $skillFactory
     * @param SkillResource $skillResource
     */
    public function __construct(
        Action\Context                     $context,
        private readonly SkillFactory    $skillFactory,
        private readonly SkillResource    $skillResource
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

        $isExistingSkill = (bool)$post->skill_id;
        $skill = $this->skillFactory->create();
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($isExistingSkill) {
            try {
                $this->skillResource->load($skill, $post->skill_id);
                if (!$skill->getData('skill_id')) {
                    throw new NotFoundException(__('This record no longer exists.'));
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $redirect->setPath('*/*/');
            }
        } else {
            // If new, build an object with the posted data to save it
            unset($post->skill_id);
        }

        $skill->setData(array_merge($skill->getData(), $post->toArray()));

        try {
            $this->skillResource->save($skill);
        } catch (\Exception $e) {
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
