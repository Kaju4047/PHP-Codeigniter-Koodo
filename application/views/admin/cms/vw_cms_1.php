<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Content Management System</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border"></div>
                    <div class="box-body">
                        <form method="post" name="cmdFrm" id="cmsFrm" >
                            <div class="col-md-12 form-group ">
                                <label>Pages <span class="mandSpan">*</span></label>
                                <select class="select2 form-control" id="cmsTitle" name="cmsTitle" onchange="getCMS(this.value);">
                                    <option value="">--Select--</option>
                                    <?php
                                    if (!empty($cmsData)) {
                                        foreach ($cmsData as $val) {
                                            ?>
                                            <option value="<?= !empty($val['cms_pkey']) ? $val['cms_pkey'] : '' ?>"><?= !empty($val['cms_title']) ? ucfirst($val['cms_title']) : '' ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div for="cmsTitle" generated="true" class="error"></div>
                            </div>
                            <div class="col-md-12 form-group ">
                                <label>Content  <span class="mandSpan">*</span></label>
                                <textarea class="ckeditor form-control htmlEditor" id="editor2" name="summernote" cols="10" rows="10"></textarea>
                                <div for="editor2" generated="true" class="error"></div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="lablefnt">Title </label>
                                <input type="text" class="form-control" name="meta_title" id="meta_title">
                            </div><!--form-group -->
                            <div class="col-md-12 form-group">
                                <label class="lablefnt">Meta Keyword</label>
                                <input type="text" class="form-control" name="meta_keys" id="meta_keys">
                            </div><!-- form-group -->
                            <div class="col-md-12 form-group">
                                <label class="lablefnt">Meta Description </label>
                                <textarea rows="3" class="form-control" name="meta_desc" id="meta_desc"></textarea>
                            </div><!-- form-group -->
                            <div class="clearfix"></div>
                            <div class="col-md-2">
                                <button type="submit" name="cmsBtn" value="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                            </div>
                        </form>
                    </div>  <!-- End box-body -->
                </div>  <!-- End box-primary -->
            </div>  <!-- End col-md-12 -->
        </div>  <!-- End row -->
    </section>  <!-- End content -->
</div>
<!-- END:: content-wrapper -->
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_cms/js_cms.js"></script>

<script type="text/javascript">
                                    $(".staffLi").addClass("active");

                                    /*[start::get cms data on chage]*/
                                    function getCMS(cmsId) {
                                        if (cmsId != '') {
                                            var base_url = "<?php echo base_url(); ?>";

                                            $.ajax({
                                                type: "get",
                                                data: {cmsId: cmsId},
                                                url: base_url + "admin/cms/Cn_cms/getDataCMSById",
                                                dataType: 'json',
                                                success: function (data)
                                                {

                                                    if (data != "") {

                                                        CKEDITOR.instances.editor2.setData(data.cms_text);
                                                        $("#meta_title").val(data.cms_meta_title);
                                                        $("#meta_desc").val(data.cms_meta_desc);
                                                        $("#meta_keys").val(data.cms_meta_keyword);
                                                    } else {

                                                        CKEDITOR.instances.editor2.setData("");
                                                        $("#meta_title").val("");
                                                        $("#meta_desc").val("");
                                                        $("#meta_keys").val("");
                                                    }

                                                }
                                            });
                                        } else {
                                            CKEDITOR.instances.editor2.setData("");
                                            $("#meta_title").val("");
                                            $("#meta_desc").val("");
                                            $("#meta_keys").val("");
                                        }
                                    }
                                    /*[end::get cms data on chage]*/

                                    CKEDITOR.replace('editor2', {
                                        extraPlugins: 'uploadimage,image2',
                                        height: 300,
                                        // Upload images to a CKFinder connector (note that the response type is set to JSON).
                                        uploadUrl: '/design/admin-panel/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                                        // Configure your file manager integration. This example uses CKFinder 3 for PHP.
                                        filebrowserBrowseUrl: '/design/admin-panel/ckfinder/ckfinder.html',
                                        filebrowserImageBrowseUrl: '/design/admin-panel/ckfinder/ckfinder.html?type=Images',
                                        filebrowserUploadUrl: '/design/admin-panel/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                        filebrowserImageUploadUrl: '/design/admin-panel/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                        // The following options are not necessary and are used here for presentation purposes only.
                                        // They configure the Styles drop-down list and widgets to use classes.

                                        stylesSet: [
                                            {name: 'Narrow image', type: 'widget', widget: 'image', attributes: {'class': 'image-narrow'}},
                                            {name: 'Wide image', type: 'widget', widget: 'image', attributes: {'class': 'image-wide'}}
                                        ],
                                        // Load the default contents.css file plus customizations for this sample.
                                        contentsCss: [CKEDITOR.basePath + 'contents.css', 'http://sdk.ckeditor.com/samples/assets/css/widgetstyles.css'],
                                        // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
                                        // resizer (because image size is controlled by widget styles or the image takes maximum
                                        // 100% of the editor width).
                                        image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
                                        image2_disableResizer: true
                                    });


</script>
</body>
</html>