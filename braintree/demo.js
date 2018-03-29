function Demo(config) {
    this.config = config;
    this.config.development = config.development || false;

    this.paymentForm = $('#' + config.formID);
    this.inputs = $('input[type=text], input[type=email], input[type=tel]');
    this.button = this.paymentForm.find('.button');

    this.states = {
        'show': 'active',
        'wait': 'loading'
    };
    this.focusClass = "has-focus";
    this.valueClass = "has-value";

    this.initialize();
}


Demo.prototype.initialize = function () {
    var self = this;

    this.events();
    this.inputs.each(function (index, element) {
        self.labelHander($(element));
    });
    this.notify('error');
};


Demo.prototype.events = function () {
    var self = this;

    this.inputs.on('focus', function () {
        $(this).closest('label').addClass(self.focusClass);
        self.labelHander($(this));
    }).on('keydown', function () {
        self.labelHander($(this));
    }).on('blur', function () {
        $(this).closest('label').removeClass(self.focusClass);
        self.labelHander($(this));
    });
};


Demo.prototype.labelHander = function (element) {
    var self = this;
    var input = element;
    var label = input.closest('label');

    window.setTimeout(function () {
        var hasValue = (input.val().length > 0) ? true : false;

        if (hasValue) {
            label.addClass(self.valueClass);
        } else {
            label.removeClass(self.valueClass);
        }
    }, 10);
};


Demo.prototype.notify = function (status) {
    var self = this;
    var notice = $('.notice-' + status);
    var delay = (this.config.development === true) ? 4000 : 2000;

    notice.show();

    window.setTimeout(function () {
        notice.addClass('show');
        self.button.removeClass(self.states.wait);

        window.setTimeout(function () {
            notice.removeClass('show');
            window.setTimeout(function () {
                notice.hide();
            }, 310);
        }, delay);
    }, 10);
};

/**
 * function validateForm - checks that all required inputs are not empty
 */
function validateForm() {
    // console.log('Validating');
    var validForm = true;

    $('input').each(function () {
        var options = {
            times: 2,
            distance: 5
        };
        // console.log('Input: ', this);
        if ($(this).val().length === 0 || $(this).val() === 0) {
            $(this).closest('label').addClass("missing-input");//.effect("shake", "linear", 200, removeInvalidClasses);
            $(this).addClass('missing-input-placeholder').effect("shake", options, 400);
            validForm = false;
        }
    });
}

/**
 * function removeInvalidClasses - removes all invalid inputs' classes set by validateForm function
 */
function removeInvalidClasses() {
    // console.log('remove invalid class called');
    $(this).closest('label').removeClass("missing-input");
    $(this).removeClass('missing-input-placeholder');
}

/**
 * function printReceipt - Prints the receipt
 */
function printReceipt() {
}

function applyClickHandler() {
    $('#submit_button').click(validateForm);
    $('input').on('focus', removeInvalidClasses);
    $("button#print_button").click(function () {
        $("header.PrintArea, section.PrintArea").printArea();
    });
}

$(document).ready(applyClickHandler, removeInvalidClasses);
