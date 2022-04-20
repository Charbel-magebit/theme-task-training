<?php

namespace Magebit\EnhancedProductAttributeDisplay\Block\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class EnhancedProductAttributeDisplay implements ArgumentInterface
{
    // List of product properties we wish to display by default if available
    private const DEFAULT_ATTRIBUTES = [
        'color',
        'dimension',
        'material'
    ];

    private const NUMBER_OF_ATTRIBUTES_TO_DISPLAY = 3;
    private const HTML_ALLOWED_ON_FRONTEND = 'is_html_allowed_on_front';

    /** @var Product $product */
    private $product;

    /**
     * @param Product $product
     * @return array
     */
    public function getAttributesToRender(Product $product): array
    {
        $this->product = $product;

        return $this->getProductAttributes();
    }

    /**
     * @return array
     */
    private function getProductAttributes(): array
    {
        $attributesToRender = [];
        $productAttributes = $this->product->getAttributes();

        //Try to get all default attributes
        foreach (self::DEFAULT_ATTRIBUTES as $defaultAttribute) {
            if (
                isset($productAttributes[$defaultAttribute]) &&
                !empty($this->product->getAttributeText($productAttributes[$defaultAttribute]->getName()))
            ) {
                $attributeName = $productAttributes[$defaultAttribute]->getName();
                $attributesToRender[$attributeName] = $this->formatAttributeText($this->product->getAttributeText($attributeName));
                unset($productAttributes[$defaultAttribute]);
            }
        }

        //Check if some default attributes could not be loaded and replace them by other attributes
        $itemsToStillRender = self::NUMBER_OF_ATTRIBUTES_TO_DISPLAY - count($attributesToRender);
        foreach ($productAttributes as $attribute) {
            if ($itemsToStillRender > 0) {
                if ($attribute->getData(self::HTML_ALLOWED_ON_FRONTEND)) {
                    $attributeName = $attribute->getName();
                    $attributesToRender[$attributeName] = $this->formatAttributeText($this->product->getAttributeText($attributeName));
                    $itemsToStillRender -= 1;
                }
            } else {
                break;
            }
        }

        return $attributesToRender;
    }


    /**
     * @param array|string $attributeText
     */
    private function formatAttributeText($attributeText): string
    {
        return is_array($attributeText) ? implode(',', $attributeText) : $attributeText;
    }
}