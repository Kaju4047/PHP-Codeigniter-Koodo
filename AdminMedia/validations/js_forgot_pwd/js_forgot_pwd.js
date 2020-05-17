
$(function () {

    $("#frmForgotPwd").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            email: {
                required: true,
                email: true
            },
        },
        // Specify the validation error messages
        messages: {
            email: {
                required: "* Please enter email id.",
                email: "* Please enter vaild email id."
            },
        },
            submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
        form.submit(); // <- use 'form' argument here.
    }
});

});
