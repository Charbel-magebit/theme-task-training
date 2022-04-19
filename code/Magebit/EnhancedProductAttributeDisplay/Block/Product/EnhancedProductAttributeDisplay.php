<?php

namespace Magebit\EnhancedProductAttributeDisplay\Block\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class EnhancedProductAttributeDisplay implements ArgumentInterface
{
    // List of product properties we wish to display by default if available
    public const DEFAULT_ATTRIBUTES = [
        'color',
        'dimension',
        'material'
    ];

    public const NUMBER_OF_ATTRIBUTES_TO_DISPLAY = 3;

    private $attributesToRender = [];
    private $productAttributes = [];
    /** @var Product $product */
    private $product;

    public function getAttributesToRender($product): array
    {
        $this->product = $product;
        $this->productAttributes = $product->getAttributes();
        $this->getDefaultAttributes()->getRemainingAttributes();

        return $this->attributesToRender;
    }

    private function getRemainingAttributes(): void
    {
        $itemsToStillRender = self::NUMBER_OF_ATTRIBUTES_TO_DISPLAY - count($this->attributesToRender);

        foreach ($this->productAttributes as $attribute) {
            if ($itemsToStillRender > 0) {
                if ($attribute->getData("is_html_allowed_on_front")) {
                    $attributeName = $attribute->getName();
                    $this->attributesToRender[$attributeName] = $this->formatAttributeText($this->product->getAttributeText($attributeName));
                    $itemsToStillRender -= 1;
                }
            } else {
                break;
            }
        }
    }

    private function getDefaultAttributes(): self
    {
        foreach (self::DEFAULT_ATTRIBUTES as $defaultAttribute) {
            if (
                isset($this->productAttributes[$defaultAttribute]) &&
                $this->product->getAttributeText($this->productAttributes[$defaultAttribute]->getName()) !== ''
            ) {
                $attributeName = $this->productAttributes[$defaultAttribute]->getName();
                $this->attributesToRender[$attributeName] = $this->formatAttributeText($this->product->getAttributeText($attributeName));
                unset($this->productAttributes[$defaultAttribute]);
            }
        }
        return $this;
    }

    private function formatAttributeText($attributeText): string
    {
        return is_array($attributeText) ? implode(",", $attributeText) : $attributeText;
    }
}