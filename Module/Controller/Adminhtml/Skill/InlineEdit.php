<?php

declare(strict_types=1);

namespace Vendor\Module\Controller\Adminhtml\Skill;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Vendor\Module\Model\SkillFactory;
use Vendor\Module\Model\SkillValidator;
use Vendor\Module\Model\ResourceModel\Skill as SkillResource;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Vendor_Module::skill_save';

    /**
     * Constructor class
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param SkillFactory $skillFactory
     * @param SkillResource $skillResource
     */
    public function __construct(
        private readonly Context          $context,
        private readonly JsonFactory      $jsonFactory,
        private readonly SkillFactory  $skillFactory,
        private readonly SkillResource $skillResource,
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
                $skillId = $item['skill_id'];
                try {
                    $skill = $this->skillFactory->create();
                    $this->skillResource->load($skill, $skillId);
                    $skill->addData($item);
                    $this->skillResource->save($skill);
                } catch (Exception $e) {
                    $messages[] = __("Something went wrong while saving item $skillId: ") . $e->getMessage();
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
