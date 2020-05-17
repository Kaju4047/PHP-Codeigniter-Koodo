
$(function () {
    /*[start::check old password is same or not]*/
    $.validator.addMethod("chkOldPass", function (value, element) {
        var pass = $('#txtOldPass1').val();
        if ($('#txtOldPass').val() != '' && pass != '') {
            return $('#txtOldPass').val() == pass
        } else {
            return true;
        }
    }, "* Old password is wrong.");
    /*[end::check old password is same or not]*/

    /*[start::check old and new password is same or not]*/
    $.validator.addMethod("chkOldNewPass", function (value, element) {
        var pass = $('#txtNewPass').val();

        if ($('#txtOldPass').val() != '' && pass != '') {
            return $('#txtOldPass').val() != pass
        } else {
            return true;
        }
    }, "* Old and new password cannot not be same.");
    /*[end::check old and new password is same or not]*/

    /*[start::check old password is same or not]*/
    $.validator.addMethod("chkNewCnfPass", function (value, element) {
        var pass = $('#txtNewConfrmPass').val();
        if ($('#txtNewPass').val() != '' && pass != '') {
            return $('#txtNewPass').val() == pass
        } else {
            return true;
        }
    }, "* New and confirm password must be same.");
    /*[end::check old password is same or not]*/
    $("#frmSetting").validate({
// Specify the validation rules
        rules: {
            txtOldPass: {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
            txtNewPass: {
                required: true,
                chkOldNewPass: true,
                minlength: 6,
                maxlength: 20,
            },
            txtNewConfrmPass: {
                required: true,
                chkNewCnfPass: true,
                minlength: 6,
                maxlength: 20,
            },
        },
        // Specify the validation error messages
        messages: {
            txtOldPass: {
                required: "* Please enter old password.",
                minlength: "* Please enter atleast 6 characters.",
                maxlength: "* Please enter only 20 characters.",
            },
            txtNewPass: {
                required: "* Please enter new password.",
                minlength: "* Please enter atleast 6 characters.",
                maxlength: "* Please enter only 20 characters.",
            },
            txtNewConfrmPass: {
                required: "* Please enter confirm password.",
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
