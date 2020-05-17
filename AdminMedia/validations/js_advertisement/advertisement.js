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
    jQuery.validator.addMethod("lettersonly", function(value, element) {
return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Only alphabetical characters");

    $.validator.addMethod(
    "maxfilesize",
    function (value, element) {
    if (this.optional(element) || !element.files || !element.files[0]) {
    return true;
    } else {
    return element.files[0].size <= 1024*500;
    }
    },
    'The file size can not exceed 500KB.'
    );

 // $.validator.addMethod('maxfilesize', function (value, element, arg) {
 //            var minsize=1000; // min 1kb
 //            if((value>minsize)&&(value<=arg)){
 //                return true;
 //            }else{
 //                return false;
 //            }
 //        });
$("#add_adv").validate({
        onfocusout: false,
// Specify the validation rules
        rules: {
           place: {
              required : true,
            },
            advname: {
                required : true,
                maxlength:50,
                lettersonly: true,
            },
            mob: {
                 number: true,
                required: true,
                minlength: 10,
            },
            state:{
                 required : true,
            } ,
            city:{
                 required : true,
            } , 
            fromdate:{
                required : true,
              
            },
             todate:{
                required : true,
            },
             price:{
                required : true,
                number:true,
                maxlength:50,
            },
             url:{
                required : true,
                url:true,
            },
            advimg:{
                 required: images,
                accept: "JPG|PNG|JPEG",
                // maxfilesize: 500000 ,
                maxfilesize: true ,

            }
       
        },
        messages: {
             place: {
                required: "* Please Enter place  .",
                // remote: "* This country is already exists."
            },
            advname: {
                required: "* Please Enter Name  .",
                maxlength: '* Please enter max 50 characters.',
              
                // remote: "* This country is already exists."
            },
            mob:{
                  required: '* Please enter mobile no.',
                minlength:'at least 10 digit number',
            },
             state: {
                required: "* Please Enter state  .",
                // remote: "* This country is already exists."
            },
             city: {
                required: "* Please Enter city  .",
                // remote: "* This country is already exists."
            },
            fromdate: {
                required: "* Please Enter from date  .",
                // remote: "* This country is already exists."
            },
              todate: {
                required: "* Please Enter to date  .",
                // remote: "* This country is already exists."
            },
              price: {
                required: "* Please Enter price  .",
                  number :"Enter number only",
                   maxlength: '* Please enter max 50 characters.',
                // remote: "* This country is already exists."
            },
              url: {
                required: "* Please Enter url  .",
                url:"Enter valid URL",
                // remote: "* This country is already exists."
            },
            advimg:{
                required: "* Select image .",
                accept: "* image type JPG|PNG|JPEG.",
                // maxfilesize: "* file size must be less than 500 KB.",
            }

        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});