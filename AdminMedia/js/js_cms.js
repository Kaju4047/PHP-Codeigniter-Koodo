
jQuery(document).ready(function () {

    jQuery.validator.addMethod("htmlEditor", function (value, element) {
        var _editor = CKEDITOR.instances[element.id];
        _editor.updateElement();

        var elValue = $(element).val();
        if (elValue.length > 0) {
            return true;
        }
        return false;
    }, "* Please enter content");


    $("#cmsFrm").validate({
        //AS THE CKEDITOR HIDE THE ORIGINAL TEXTAREA WE NEED TO ADD AN NEW IGNORE RULE, BASED ON THE CLASS "htmlEditor" IN THIS CASE
        ignore: "input:hidden:not(input:hidden.required),input:hidden:not(input:hidden.htmlEditor)",
        rules: {
            cmsTitle: {
                required: true,
            },
            ckeditor: {
                htmlEditor: true,
            },
        },
        messages: {
            cmsTitle: {
                required: "* Please select page title .",
            },
            ckeditor: {
                htmlEditor: "* Please enter content.",
            },
        },
        errorPlacement: function (error, element) {

        },
        submitHandler: function () {
            $.blockUI({theme: true});

        }
    });
});

