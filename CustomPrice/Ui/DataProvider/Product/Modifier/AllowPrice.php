<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Perspective\CustomPrice\Ui\DataProvider\Product\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\ProductAlert\Model\Price;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;
use Perspective\CustomPrice\Helper\Data;

class AllowPrice extends AbstractModifier
{
    const CUSTOM_PRICE = 'custom_price';
    const CUSTOM_DATA_SCOPE = 'data.product';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        ScopeConfigInterface $scopeConfig,
        Data $_helper
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->scopeConfig = $scopeConfig;
        $this->_helper = $_helper;
    }
    public function modifyMeta(array $meta)
    {
        unset($meta['product-details']['children']['container_custom_price']);
        $meta['custom_price_parent'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Custom Price'),
                        'formElement' => 'container',
                        'dataScope' => static::CUSTOM_DATA_SCOPE,
                        'sortOrder' => 1,
                        'collapsible' => true,
                        'breakLine' => true,
                        'valueUpdate' => 'keyup',
                        'componentType' => Fieldset::NAME,
                    ]
                ]
            ],
            'children' => [
                'custom_price' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => Input::NAME,
                                'componentType' => Field::NAME,
                                'dataScope' => static::CUSTOM_PRICE,
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Price::NAME,
                                'component' => 'Magento_Ui/js/form/element/single-checkbox-use-config',
                                'elementTmpl' => 'ui/form/element/input',
                                'addbefore' => '$',
                                'additionalClasses' => 'admin__field admin__field-small',
                                'description' => __('Custom price'),
                                'label' => __('Custom price')
                            ]
                        ]
                    ]
                ],
                'custom_price_checkbox' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Allow Modify'),
                                'dataType' => Number::NAME,
                                'formElement' => Checkbox::NAME,
                                'componentType' => Field::NAME,
                                'description' => '',
                                'component' => 'Magento_Ui/js/form/element/single-checkbox-use-config',
                                'additionalClasses' => 'admin__field admin__field-x-small',
                                'prefer' => 'Checkbox',
                                'dataScope' => 'use_config_' . static::CUSTOM_PRICE,
                                'valueMap' => [
                                    'false' => '0',
                                    'true' => '1',
                                ],
                                'exports' => [
                                    'checked' => '!${$.parentName}.' . 'custom_price'
                                        . ':isUseConfig', '__disableTmpl' => ['checked' => false],
                                ],
                                'imports' => [
                                    'disabled' => '${$.parentName}.' . 'custom_price'
                                        . ':isUseDefault', '__disableTmpl' => ['disabled' => false],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
        return $meta;
    }

    /**
     * @param array $data
     * @return array<array>
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();
        $currentPrice = $product->getData('price');
        $customPriceDiscount = $this->_helper->getPercent();
        $customPriceValue = $product->getData('custom_price');
        $customCheckbox = $product->getData('custom_price_checkbox');

        if (!$this->_helper->getEnableModule()) {
            return $data;
        }
        if ($customPriceValue == 0 || empty($product->getId())) {
            $customPriceValue = $currentPrice + ($currentPrice * $customPriceDiscount / 100);
            $data[$product->getId()]['product']['custom_price'] = $customPriceValue;
        }
        return $data;
    }
}
