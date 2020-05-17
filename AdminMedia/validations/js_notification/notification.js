$(function () 
{


$("#notification").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            type: {
                required : true,
                

                // remote: $('#base_url').val() + 'admin/country-name?txtid=' + $('#txtid').val()
            },
            subject: {
                required : true,
                

                // remote: $('#base_url').val() + 'admin/country-name?txtid=' + $('#txtid').val()
            },
            message: {
                required : true,
            },
        },
        // Specify the validation error messages
        messages: {
             type: {
                required: "* Please select type  .",
                // remote: "* This country is already exists."
            },
            subject: {
                required: "* Please enter subject  .",
                // remote: "* This country is already exists."
            },
            message: {
                 required: "* Please enter message   .",
            },
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});