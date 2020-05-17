$(function () {

  var imgFlag = ($("#txtPkey").val() != "") ? false : true;
    $("#Frmuser").validate({
// Specify the validation rules
        rules: {
            txtName: {
                required: true
            },
            txtMobile: {
                number: true,
                required: true,
                minlength: 10,
           remote: $('#base_url').val() + 'admin/subuser/Cn_subuser/check_mobile?pk=' + $("#um_pkey").val()
            },
            txtEmail: {
                required: true,
                email: true,
                remote: $('#base_url').val() + 'admin/subuser/Cn_subuser/check_email?id=' + $("#txtPkey").val()
            },
             txtAddress: {
                required: true,
            },
              txtCity: {
                required: true,
                accept: "[a-zA-Z]+" ,
            },
            txtPassword: {
                required: true,
                minlength: 6,
            },
            
            fileCmpLogo: {

                required: imgFlag,
                accept: 'jpg,png,jpeg'
            },
          
        },
        // Specify the validation error messages
        messages: {
            txtName: {
                required: '* Please enter name.'
            },
             txtMobile: {
                required: '* Please enter mobile no.',
                remote: '* This mobile no is already used.',
                minlength:'should be 10 digit',
                
            },
              txtEmail: {
                required: '* Please enter email id.',
                remote: '* This email id is already used.',
                email: 'Enter Valid email',
            },
             txtAddress: {
                required: '* Please enter address.',
            },
            txtCity: {
                required: '* Please enter city.',
                  accept: 'Only accept letter',

            },
            txtPassword: {
                required: '* Please enter password.',
                minlength: '* Please enter at least 6 characters.',
            },
             fileCmpLogo: {
                required: '* select Profile.',
                accept: "* Please select jpg/jpeg/png file type only.",
            },
          
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});

$(".Nonlist").change(function () {
    var chkLengt = $(this).closest('tr').find('.Nonlist:checked').length;
    if (parseInt(chkLengt) > parseInt(0)) {
        $(this).closest('tr').find('.list').prop('checked', true);
    } else {
        $(this).closest('tr').find('.list').prop('checked', false);
    }

});
$(".list").change(function () {
    var chkLengt = $(this).closest('tr').find('.list:checked').length;
    if (parseInt(chkLengt) > parseInt(0)) {
//        $(this).closest('tr').find('.Nonlist').prop('checked', true);
    } else {
        $(this).closest('tr').find('.Nonlist').prop('checked', false);
    }

});