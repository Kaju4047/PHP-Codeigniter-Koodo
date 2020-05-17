var baseurl = document.getElementById('baseurl').value;
// // var name = document.getElementById('sportName').value;
// var name = $("#sportName").val(); 
// alert(baseurl);
// alert(name);
 var images = ($("#fileold").val() == "") ? true : false;
// $(function () 
// {

//       var images = ($("#fileold").val() == "") ? true : false;
//     jQuery.validator.addMethod("fileType", function (val, element) 
//     {
//         if (val == "") {
//             return true;
//         }

//         var type = element.files[0].type;
//         //  alert(type)
// //        if (type == 'application/zip' || type =='application/x-zip-compressed')// checks the file more than 1 MB
//         if (type.indexOf('jpeg') != -1 || type.indexOf('jpg') != -1 || type.indexOf('png') != -1)// checks the file more than 1 MB
//         {
//             return true;
//         } else {
//             return false;
//         }
//     }, "* Please select jpg, jpeg and png file type only.");


// $('#sportName').rules('add', {
//                        required: true,
//                        maxlength: 25,
//                        remote: {

//                             url: base_url+"dmin/master/Cn_master/check_sport",
//                             type: "post",
//                             data: {
//                                     sportName: function(){ return $("#sportName").val(); },
//                                     pk_id: function(){ return $("#txtid").val(); },
                                   
//                                   }
//                           },
                       
//                        messages: {
//                             required: "* Please enter sport name  .",
//                             maxlength:'* Please enter  max 25 characters.',
//                             remote: "* Sport Name already exists."
//                         }
//                     });

$("#add_sport").validate({
        // onfocusout: false,
// Specify the validation rules
        rules: {
            // sportName: {
            //     required : true,
            //     maxlength: 25,
            //     // remote: $('#base_url').val() + 'admin/check-sport?txtid=' + $('#txtid').val()
            //     // remote: $('#base_url').val() + 'admin/check-sport?txtid=' + $('#txtid').val()
            //     remote: {

            //             url: baseurl+"admin/master/Cn_master/check_sport",
            //             type: "post",
            //             data: {                            
            //                    sportName: function(){ return $("#sportName").val(); },
            //                    pk_id: function(){ return $("#txtid").val(); },
            //                   }
            //     },
         
            // },
            sportimage: {
                required: images,
                accept: "JPG|PNG|JPEG",
            },
        },
        // Specify the validation error messages
        messages: {
            // sportName: {
            //     required: "* Please enter sport name  .",
            //     maxlength:'* Please enter  max 25 characters.',
            //     remote: "* Sport Name already exists."
            // },
            sportimage: {
                required: "* Select image .",
                accept: "* image type JPG|PNG|JPEG.",
            },
        },
        // submitHandler: function (form)
        // { // <- pass 'form' argument in
        //     $(".submit").attr("disabled", true);
        //     form.submit(); // <- use 'form' argument here.
        // }
    });
// });