$(function () 
{


      var images = ($("#fileold").val() == "") ? true : false;
    jQuery.validator.addMethod("fileType", function (val, element) 
    {
        if (val == "") {
            return true;
        }

        var type = element.files[0].type;
        //  alert(type)
//        if (type == 'application/zip' || type =='application/x-zip-compressed')// checks the file more than 1 MB
        if (type.indexOf('jpeg') != -1 || type.indexOf('jpg') != -1 || type.indexOf('png') != -1)// checks the file more than 1 MB
        {
            return true;
        } else {
            return false;
        }
    }, "* Please select jpg, jpeg and png file type only.");

$("#add_services").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
            serviceName: {
                required : true,
                

                // remote: $('#base_url').val() + 'admin/country-name?txtid=' + $('#txtid').val()
            },
            serviceimage: {
                required: images,
                accept: "JPG|PNG|JPEG",
            },
        },
        // Specify the validation error messages
        messages: {
            serviceName: {
                required: "* Please enter sevice name  .",
                // remote: "* This country is already exists."
            },
            serviceimage: {
                required: "* Select image .",
                accept: "* image type JPG|PNG|JPEG.",
            },
        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});