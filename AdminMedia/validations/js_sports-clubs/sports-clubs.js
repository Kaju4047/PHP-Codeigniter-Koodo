$(function () 
{


$("#add_clubs").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            name: {
                required : true,
                accept: "[a-zA-Z]+" ,
            },
            event_location: {
                required : true,
            },
            email: {
                required : true,
                email: true,
            },
            mobile: {
                number: true,
                required: true,
                minlength: 10,
                maxlength: 13,
            }, 
            sport: {
                required : true,
            },
            // 'sport[]': {
            //     required : true,
            // },
           txteditor:   {
                required: true,
                 maxlength:300,
                },
            // url:{
            //      required : true,
            //      url:true,
            // }
        },
        // Specify the validation error messages
        messages: {
             name: {
                required: "* Please enter name  .",
                 accept: 'Only accept letter',
                // remote: "* This country is already exists."
            },
             event_location: {
                required: "* Please enter address  .",
                // remote: "* This country is already exists."
            },
             email: {
                required: "* Please select type  .",
                 email: 'Enter Valid email',
                // remote: "* This country is already exists."
            },
             mobile: {
                required: '* Please enter mobile no.',
                remote: '* This mobile no is already used.',
                minlength:'should be 10 digit',
                maxlength:'should be 13 digit',
                // remote: "* This country is already exists."
            },
             sport: {
                required: "* Please enter sport  .",
                // remote: "* This country is already exists."
            },
            // 'sport[]': {
            //     required: "* Please select type  .",
            //     // remote: "* This country is already exists."
            // },
            txteditor: {
                 required: "* Please enter text content.",
                 maxlength: '* Please enter max 300 characters.',
            },
            // url:{
            //      required : "* Please enter url   .",
            //      url:"* Please enter valid url   .",
            // }
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});