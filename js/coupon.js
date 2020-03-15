
    var discnt = 0; // no default percent discount

var coupons = new Array( // place to put coupon codes
    "coup1", // 1st coupon value - comma seperated
    "firecracker0724", // special coupon for ebay guy
    "coup2", // 2nd coupon value - add all you want
    "coup3" // 3rd coupon value
);
var coupdc = new Array( // place to put discounts for coupon vals
    5,
    15,
    10,
    20
);
var coupval = "(blanket)"; // what user entered as coupon code

function validate() { // check user coupon entry
    var i;
    discnt = 0; // assume the worst

    for (i = 0; i < coupons.length; i++) {
        if (coupval == coupons[i]) {
            discnt = coupdc[i]; // remember the discount amt

            alert("Valid coupon number! \n\n" + discnt +
                "% discount now in effect.");
            return;
        }
    }
    alert("'" + coupval + "' is not a valid code!");
}

function CalculateOrder(form) {
    if (form.coupcode.value == coupval) {
        form.discount_rate.value = discnt;
        form.discount_rate2.value = discnt;
        form.on3.value = "Coupon Entered";
        form.os3.value = coupval;
    }
}
