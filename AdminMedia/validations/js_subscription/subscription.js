$(function () 
{
    jQuery.validator.addMethod("offer", function(value, element) {
var mrpcost = $("#mrp").val();
var offercost = $("#offer").val();
// alert(value);
if(value !="" && mrpcost!=""){
 var offercost = parseInt(value);
    
      mrpcost=parseInt(mrpcost);

    // alert(value);

  return (mrpcost >= offercost);
}else{
     return true;
}
}, "Offer price must be smaller than mrp price.");

 var base_url = "<?php echo base_url(); ?>";
$("#add_subscription").validate({

        onfocusout: false,
// Specify the validation rules
        rules: {
            category: {
                required : true,
            },
            catlist: {
                required : true,

            },
          listtype: {
                required : true,
            },
             sport: {
                required : true,
            },
             city: {
                required : true,
            },
             duration: {
                required : true, 
            },
          
           mrp: {
                required : true,
                number: true,
                 maxlength:10,
            },
          
           offer: {
                offer:true,
                required : true,
                number: true,
                maxlength:10,
            },
          
           desc: {
                required : true,
                  maxlength:300,
            },

          
          
        },
        // Specify the validation error messages
        messages: {
            category: {
                required: "* Please select plan  .",
                // remote: "* This country is already exists."
            },
              catlist: {
                required: "* Please select list .",
                // remote: "* This country is already exists."
            },
            listtype: {
                required: "* Please select type  .",
              
            },
            sport: {
                required: "* Please select sport  .",
              
            },
            city: {
                required: "* Please select city  .",
              
            },
            duration: {
                required: "* Please enter duration  .",             
            },
            mrp: {
                required: "* Please enter mrp  .",
                 number: "* Please enter number  .",
                  maxlength: '* Please enter max 10 numbers.',

              
            },
            offer: {
                required: "* Please enter offer  .",
                  number: "* Please enter number  .",
                  maxlength: '* Please enter max 10 characters.',
              
            },
             desc: {
                required: "* Please enter description  .",
                  maxlength: '* Please enter max 300 characters.',             
            },            

        },
        submitHandler: function (form)
        { // <- pass 'form' argument in
            $("#submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
        
    });
});