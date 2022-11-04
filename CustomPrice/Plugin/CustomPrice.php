<?php


namespace Perspective\CustomPrice\Plugin;

use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;

class CustomPrice
{
    protected $product;

    protected $_productCollectionFactory;

    protected $_request;

    protected $_helper;

    public function __construct(
        \Perspective\CustomPrice\Helper\Data $_helper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        ProductRepository $product,
        ProductRepositoryInterface $_product
    ) {
        $this->_request = $request;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helper = $_helper;
        $this->product = $product;
        $this->_productRepository = $_product;
    }

    public function afterGetPrice(Product $subject, $result)
    {
        $page = $this->_request->getFullActionName();
        if ($page == 'catalog_category_view') {
            $customPrice = $subject->getData('custom_price');
            if (!empty($customPrice)) {
                $result = $customPrice;
                return $result;
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }
}
