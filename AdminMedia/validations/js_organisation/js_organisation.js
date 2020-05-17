
$(function () {
    jQuery.validator.addMethod("lettersonly", function(value, element) {
       return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    }, "* Enter only letters.");
    var imgFlag = ($("#txtCmpLogo").val() != "") ? false : true;
    $("#frmOrgMaster").validate({
// Specify the validation rules
        rules: {
            txtCmpName: {
                required: true
            },
            selCmpType: {
                required: true
            },
            fileCmpLogo: {
//                required: imgFlag,
                accept: "jpg,jpeg,png"
            },
            txtCmpState: {
               lettersonly: true,
            },
           txtCmpCity: {
               lettersonly: true,
               // required: true,
           },
            txtCmpEmail: {
                required: true,
                email: true,
            },
            txtsupportEmail: {

                email: true,
            },
            txtCmpMobile: {
                required: true,
                number: true,
                minlength: 10,
            },
            txtCmpPhone: {
                number: true,
                minlength: 10
            },
            txtCmpWebsite: {
                url: true
            },
            txtCmpFaxNo: {
                number: true,
                minlength: 6
            },
            txtCmpFBLink: {
                url: true
            },
            txtCmpTwitterLink: {
                url: true
            },
            txtCmpLinkedInLink: {
                url: true
            },
            txtCmpGoogleLink: {
                url: true
            },
            om_mapUrl: {
                required: true,
                url: true
            },
        },
        // Specify the validation error messages
        messages: {
            txtCmpName: {
                required: "* Please enter company name."
            },
            selCmpType: {
                required: "* Please select company type."
            },
            fileCmpLogo: {
                required: "* Please select company logo.",
                accept: "* Please select jpg/jpeg/png file type only.",
            },
            txtCmpAddress: {
                required: "* Please enter company address."
            },
            txtCmpCity: {
                lettersonly: "* Accept only letters.",
                 // required: "* Please select company type."

            },
            txtCmpEmail: {
                required: "* Please enter Email ID.",
            },
            txtCmpMobile: {
                required: "* Please enter mobile no.",
                minlength: "* Please enter at least 10 digits.",
            },
            txtCmpPhone: {
                required: "* Please enter phone no.",
                minlength: "* Please enter at least 10 digits.",
            },
            txtCmpFaxNo: {
                required: "* Please enter fax no.",
            },
            om_mapUrl: {
                required: "* Please enter map url.",
            },
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });

});
