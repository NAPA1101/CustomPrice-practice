<?php
namespace Perspective\CustomPrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_PRICE = 'price/';
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PRICE . 'general/' . $code, $storeId);
    }

    public function getPercent()
    {
        return $this->getGeneralConfig('percent');
    }
    public function getEnableModule()
    {
        return $this->getGeneralConfig('enable');
    }
}
