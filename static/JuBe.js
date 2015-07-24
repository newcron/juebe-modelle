/** Author: mhuttar@ebay.com */
(function () {
    JuBe = {};
    $(function () {
        $("#purchase-order-cancel").click(history.back);

        $(".items-header").click(function () {
            var $this = $(this);
            var isOpen = $this.hasClass("items-open");
            $(".items-open").removeClass("items-open");

            if (isOpen) {
                return;
            }
            $this.addClass("items-open");
            var targetClassSelector = "." + $this.attr("data-target");
            $(targetClassSelector).addClass("items-open");
        });


        $("#purchase-form-submit").click(validate);
    });

    function validate() {
        var success =
            validateField("#inputName", "Bitte geben Sie einen Namen an")
            & validateField("#inputStreet", "Bitte geben Sie Ihre Addresse an")
            & validateField("#inputZip", "Bitte geben Sie Ihre Addresse an")
            & validateField("#inputCity", "Bitte geben Sie Ihre Addresse an ")
            & validateField("#inputMail", "Bitte geben Sie Ihre E-Mail Addresse an")

        var totalOrderAmount = 0;
        $(".order-item").each(function (ix) {

            var val = parseInt($(this).val());
            if (val > 0) {
                totalOrderAmount += val;
            }
        });

        if(totalOrderAmount==0) {
            alert("Sie haben keine Artikel ausgewählt. ")
            success = false;
        }

        if(success) {
            var $form = $("#purchase-form");
            $form.attr("action", $form.attr("data-action"));
            $form.submit();
        }

    }

    function validateField(id, errorMessage) {
        var val = $(id).val();
        $(id).removeClass("is-erroneous");
        $(id + " ~ .error-desc").remove();
        if (val == null || $.trim(val) === "") {
            $(id).after('<div class="error-desc">' + errorMessage + '</div> ');
            $(id).addClass("is-erroneous");
            return false;
        }
        return true;
    }
})()