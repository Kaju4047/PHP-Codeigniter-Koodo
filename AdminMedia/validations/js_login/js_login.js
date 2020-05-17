
$(function () {

    $("#frmLogin").validate({
// Specify the validation rules
        rules: {
            txtUserName: {
                required: true,
                email: true,
            },
            txtPassword: {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
        },
        // Specify the validation error messages
        messages: {
            txtUserName: {
                required: "* Please enter email ID.",
                email: "* Please enter vaild email ID.",
            },
                txtPassword: {
                required: "* Please enter password.",
                minlength: "* Please enter atleast 6 characters.",
                maxlength: "* Please enter only 20 characters.",
            },
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
});

});
