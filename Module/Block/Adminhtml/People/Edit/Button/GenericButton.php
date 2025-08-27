<?php

declare(strict_types=1);

namespace Vendor\Module\Block\Adminhtml\People\Edit\Button;

use Magento\Backend\Block\Widget\Context;

abstract class GenericButton
{
    /**
     * GenericButton Class constructor
     *
     * @param Context $context
     */
    public function __construct(
        protected Context $context
    ) {
    }

    /**
     * Return model ID
     *
     * @return string|null
     */
    public function getPeopleId(): ?string
    {
        return $this->context->getRequest()->getParam('people_id');
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
