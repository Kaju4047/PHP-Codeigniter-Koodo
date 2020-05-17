$(function () 
{


$("#add_video").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            type: {
                required : true,
                

                // remote: $('#base_url').val() + 'admin/country-name?txtid=' + $('#txtid').val()
            },
            skill_level: {
                required : true,
            },
            
            heading: {
                required : true,
                 maxlength:50,

            },
           txteditor:   {
                required: true,
                 maxlength:300,
                },
            url:{
                 required : true,
                 url:true,
            }
        },
        // Specify the validation error messages
        messages: {
            type: {
                required: "* Please select type  .",
                // remote: "* This country is already exists."
            },
            heading: {
                 required: "* Please enter heading   .",
                 maxlength: '* Please enter max 50 characters.',
            },
            skill_level: {
                required: "* Please select Skill Level   .",
            },
            txteditor: {
                 required: "* Please enter text content.",
                 maxlength: '* Please enter max 300 characters.',
            },
             url:{
                 required : "* Please enter url   .",
                 url:"* Please enter valid url   .",
            }
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});