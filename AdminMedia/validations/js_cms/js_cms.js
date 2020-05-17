
jQuery(document).ready(function () {

    $("#cmsFrm").validate({
        ignore: [],
        onfocusout: false,
        rules: {
            cmsTitle: {
                required: true,
            },
            description:
                    {
                        required: function ()
                        {
                            CKEDITOR.instances.editor.updateElement();
                        },
                    },
        },
        messages: {
            cmsTitle: {
                required: "* Please select page title .",
            },
            description: {
                required: "* Please enter content.",
            },
        },
        errorPlacement: function (error, element) {

        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        }
    });
});

