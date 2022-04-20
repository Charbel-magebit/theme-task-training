define([
    'ko',
    'uiElement',
    'mage/translate'
], function (ko, Element, $_t) {
    "use strict";
    return Element.extend({
        defaults: {
            template: 'Magento_Catalog/input-counter',
            qty: ko.observable(0),
            error: ko.observable(''),
        },
        initialize: function () {
            this._super();
        },
        decreaseQty: function() {
            this.validate();
            if (this.qty() >= 1) {
                this.qty(parseInt(this.qty()) - 1);
            }
        },

        increaseQty: function() {
            this.validate();
            this.qty(parseInt(this.qty()) + 1);
        },

        validate: function () {
            if (this.qty() < 0) {
                this.error($_t("Item to add to cart should be bigger than one"));
            } else {
                this.error("");
            }
        }
    });
});