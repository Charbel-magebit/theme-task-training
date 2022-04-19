define([
    'ko',
    'uiElement'
], function (ko, Element) {
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
                this.error("Item to add to cart should be bigger than one");
            } else {
                this.error("");
            }
        }
    });
});