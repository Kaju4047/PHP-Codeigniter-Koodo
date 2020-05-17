$(function () 
{


$("#add_tax").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            tax: {
                required : true,
                number: true,
                maxlength:5,
                min: 1,
                max:100
                

                // remote: $('#base_url').val() + 'admin/country-name?txtid=' + $('#txtid').val()
            },
           
        },
        // Specify the validation error messages
        messages: {
            tax: {
                required: "* Please enter tax  .",
                number: "Enter only number",
                maxlength: '* Please enter max 5 digit.',
                 min: "Value must be greater than 0",
                    max: "Value must be less than 100",
                // remote: "* This country is already exists."
            },
          
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});