<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->

<?php

$fld = 'UA_priviliges';
$userid = $this->session->userdata['UID'];
 
$condition = array('UA_pkey' => $userid);
$privilige = $this->Md_database->getData('useradmin', $fld, $condition, '', '');
$privilige = !empty($privilige[0]['UA_priviliges']) ? explode(',', $privilige[0]['UA_priviliges']) : '';
// print_r($privilige1);exit();
?>
<?php
// print_r($privilige);die();
(in_array('CMS', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>


<!-- <?php
(in_array('superAdmin', $privilige) || (in_array('CMS', $privilige) ) ) ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?> -->

<div class="content-wrapper">
    <section class="content-header">
        <h1>Content Management System</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border"></div>
                    <div class="box-body">
                        <form method="post" name="cmdFrm" id="cmsFrm" >
                            <div class="col-md-4 form-group ">
                                <label>Pages <span style="color: red;">*</span></label>
                                <select class="select2 form-control" id="cmsTitle" name="cmsTitle" onchange="getCMS(this.value);">
                                    <option value="" selected disabled>--Select--</option>
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
                                <label>Content  <span style="color: red;">*</span></label>
                                <!--<textarea class="ckeditor form-control htmlEditor" id="editor2" name="summernote" cols="10" rows="10"></textarea>-->


                                <textarea class="form-control" id="editor" style="resize: none;height: 110px" name="description" placeholder=""></textarea>
                                <div for="editor" generated="true" class="error"></div>

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
                            <div class="col-md-12">
                                <?php if (in_array('CMS', $privilige)) { ?>   
                                <button type="submit" name="cmsBtn" value="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                                 <?php } ?>
                                   <?php if (in_array('CMS', $privilige)) { ?> 
                                <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i> Cancel</button></a>
                                     <?php } ?>
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

                        CKEDITOR.instances.editor.setData(data.cms_text);
                        $("#meta_title").val(data.cms_meta_title);
                        $("#meta_desc").val(data.cms_meta_desc);
                        $("#meta_keys").val(data.cms_meta_keyword);
                    } else {

                        CKEDITOR.instances.editor.setData("");
                        $("#meta_title").val("");
                        $("#meta_desc").val("");
                        $("#meta_keys").val("");
                    }

                }
            });
        } else {
            CKEDITOR.instances.editor.setData("");
            $("#meta_title").val("");
            $("#meta_desc").val("");
            $("#meta_keys").val("");
        }
    }
    /*[end::get cms data on chage]*/
</script>

<!--start::code for ck editor-->
<script src="<?php echo base_url(); ?>AdminMedia/editor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        filebrowserUploadUrl: $("#base_url").val() + "admin/cms/Cn_cms/ImageUpload"
    });

    CKEDITOR.replace('editor');
    /*[start:: code to upload local images]*/
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
        '.chosen-select-width': {width: "95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
                                    /*[end:: code to upload local images]*/
</script>

<!--end::code for ck editor-->
</body>
</html>