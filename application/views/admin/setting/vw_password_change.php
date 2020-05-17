<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>

<!-- END:: Sidebar -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1 style="text-align: center;">Settings</h1>
    </section>
    <!-- Main content -->
    <section class="content Setting ">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 no-pad">
            <div class="box box-primary" style="min-height: 0px;">
                <form role="form" action="<?php echo base_url(); ?>user-administration-setting-action" class="horizontal-form" method="post" id="frmSetting" autocomplete="off" >

                    <div class="box-body">
                        <div class="col-md-12 form-group">
                            <label>Old Password<span style="color: red;">*</span></label>
                            <input type="password" id="txtoldPass" name="txtOldPass" minlength="6" maxlength="20" class="form-control" placeholder="" autocomplete="off">

                        </div>  <!-- End col-md-3 -->
                        <div class="clearfix"></div>

                        <div class="col-md-12 form-group">
                            <label>New Password<span style="color: red;">*</span></label>
                            <input type="password" id="txtNewPass" name="txtNewPass" class="form-control" minlength="6" maxlength="20" placeholder="" autocomplete="off">
                        </div>  <!-- End col-md-3 -->
                        <div class="clearfix"></div>

                        <div class="col-md-12 form-group">
                            <label>Confirm Password<span style="color: red;">*</span></label>
                            <input type="password" id="txtNewConfrmPass" name="txtNewConfrmPass" class="form-control" minlength="6" maxlength="20" placeholder="" autocomplete="off">
                        </div>  <!-- End col-md-3 -->

                        <div class="col-md-12 form-group">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
                            <a href="<?php echo base_url('admin/setting'); ?>"> <button type="button" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i> Cancel</button>
                            </a>
                        </div>  <!-- End col-md-12 -->
                        <div class="clearfix"></div>


                    </div>  <!-- End box-body -->
                </form>
            </div>  <!-- End box -->
        </div>  <!-- End col-md-12 -->
        <div class="clearfix"></div>
    </section>  <!-- End .content -->
</div>  <!-- End .content-wrapper -->

<!-- START:: Footer -->

<?php include("application/views/admin/section/vw_footer.php"); ?>

<!-- END:: Footer -->
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_setting/js_setting.js"></script>

</body>
</html>
