var baseurl = document.getElementById('baseurl').value;
 // alert(baseurl);
$(function () 
{

$("#add_city").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            state: {
                required : true,          
            },
            city: {
                required : true,
                maxlength:25,
                accept: "[a-zA-Z]+" ,
                 remote: {

                        url: baseurl+"admin/master/Cn_master/check_city",
                        type: "post",
                        data: {
                                
                                city: function(){ return $("#city").val(); },
                                state: function(){ return $("#state").val(); },
                               pk_id: function(){ return $("#pk_id").val(); },
                              }
                          },
            },
        },
        // Specify the validation error messages
        messages: {
            state: {
                required: "* Please select state  .",
                // remote: "* This country is already exists."
            },
            city: {
                 required: "* Please enter city   .",
                 maxlength: '* Please enter max 25 characters.',
                 remote: "* This city is already exists.",
                 accept: 'Only accept letter',

            },
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});