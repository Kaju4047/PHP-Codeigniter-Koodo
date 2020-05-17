<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>

<?php include("application/views/admin/section/vw_sidebar.php"); ?>

<?php
(in_array('superAdmin', $privilige) ) ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- END:: Header -->
<style type="text/css">
    .comp-logo {
        margin-bottom: 10px;
    }
    .comp-logo img {
        width: 100%;
        height: auto;
        min-height: 50px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1> Owner Master</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 no-pad">
            <div class="box box-primary">
                <div class="box-body">
                    <form class="horizontal-form"  role="form" method="post" id="frmOrgMaster" name="frmOrgMaster" action="<?php echo base_url(); ?>organisation-master-action" enctype="multipart/form-data">

                        <div class="col-md-9 no-pad">
                            <div class="col-sm-4 form-group">
                                <label>Company Name<span style="color: red;">*</span></label>
                                <input name="txtCmpName" id="txtCmpName" placeholder="Company Name" type="text" class="form-control" value="<?php echo!empty($orgData['om_CmpName']) ? ucwords($orgData['om_CmpName']) : ''; ?>">

                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Primary Email <span style="color: red;">*</span></label>
                                <input name="txtCmpEmail" id="txtCmpEmail"  type="text" class="form-control" placeholder="Primary Email" value="<?php echo!empty($orgData['om_CmpEmail']) ? $orgData['om_CmpEmail'] : ''; ?>">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Support Email</label>
                                <input name="txtsupportEmail" id="txtsupportEmail"  type="text" class="form-control" placeholder="Support Email" value="<?php echo!empty($orgData['om_supportEmail']) ? $orgData['om_supportEmail'] : ''; ?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 form-group">
                                <label>Mobile No.<span style="color: red;">*</span></label>
                                <input  name="txtCmpMobile"  id="txtCmpMobile"  type="text" class="form-control isInteger" maxlength="13" placeholder="Mobile No" value="<?php echo!empty($orgData['om_CmpMobile']) ? $orgData['om_CmpMobile'] : ''; ?>">
                            </div>

                            <div class="col-sm-4 form-group">
                                <label>Landline No.</label>
                                <input name="txtCmpPhone" id="txtCmpPhone"type="text"   class="form-control  isInteger" maxlength="20" placeholder="Landline No" value="<?php echo!empty($orgData['om_CmpPhone']) ? $orgData['om_CmpPhone'] : ''; ?>">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>City</label>
                                <input name="txtCmpCity" id="txtCmpCity"   type="text" class="form-control" placeholder="City" value="<?php echo!empty($orgData['om_CmpCity']) ? $orgData['om_CmpCity'] : ''; ?>">

                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 form-group">
                                <label>State</label>
                                <input name="txtCmpState" id="txtCmpState" type="text" class="form-control isAlpha" placeholder="State" value="<?php echo!empty($orgData['om_CmpState']) ? $orgData['om_CmpState'] : ''; ?>">

                            </div>
                            <div class="col-sm-8 form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="Address" name="txtCmpAddress" id="txtCmpAddress" value="<?php echo!empty($orgData['om_CmpAddress']) ? $orgData['om_CmpAddress'] : ''; ?>">
                            </div>  <div class="clearfix"></div>

                            <div class="clearfix"></div>

                            <div class="col-sm-4 form-group">
                                <label>Website</label>

                                <span class="mandSpan"> (http://www.example.com)</span>
                                <input  name="txtCmpWebsite" id="txtCmpWebsite"  type="text" class="form-control" placeholder="http://www.example.com" value="<?php echo!empty($orgData['om_CmpWebsite']) ? $orgData['om_CmpWebsite'] : ''; ?>">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Facebook Link</label><span class="mandSpan"> (https://www.facebook.com)</span>
                                <input name="txtCmpFBLink" id="txtCmpFBLink"  type="text" class="form-control" placeholder="https://www.facebook.com" value="<?php echo!empty($orgData['om_CmpFBLink']) ? $orgData['om_CmpFBLink'] : ''; ?>">

                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Twitter Link</label><span class="mandSpan"> (https://www.twitter.com)</span>
                                <input  name="txtCmpTwitterLink" id="txtCmpTwitterLink" type="text" class="form-control" placeholder="https://www.twitter.com" value="<?php echo!empty($orgData['om_CmpTwitterLink']) ? $orgData['om_CmpTwitterLink'] : ''; ?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 form-group">
                                <label>Linkedin Link</label><span class="mandSpan"> (https://www.linkedin.com)</span>
                                <input name="txtCmpLinkedInLink" id="txtCmpLinkedInLink"  type="text" class="form-control" placeholder="https://www.linkedin.com" value="<?php echo!empty($orgData['om_CmpLinkedInLink']) ? $orgData['om_CmpLinkedInLink'] : ''; ?>">
                            </div>

                            <div class="col-sm-4 form-group">
                                <label>Google+ Link</label><span class="mandSpan"> (https://www.plus.google.com)</span>
                                <input name="txtCmpGoogleLink" id="txtCmpGoogleLink"  type="text" class="form-control" placeholder="https://www.plus.google.com" value="<?php echo!empty($orgData['om_CmpGoogleLink']) ? $orgData['om_CmpGoogleLink'] : ''; ?>">
                            </div>

                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <label>Company Logo</label>
                            <div class="comp-logo">
                                <?php $LogoLink = !empty($orgData['om_LogoImage']) ? 'AdminMedia/upload/OrgnizationLogo/' . $orgData['om_LogoImage'] : 'AdminMedia/images/default.png'; ?>

                                <img src="<?php echo (base_url() . $LogoLink); ?>" class="abc" >
                            </div>

                            <input name="fileCmpLogo" id="my-ownprf" type="file" class="form-control" accept="image/*" >
                            <input type="hidden" name="txtCmpLogo" id="txtCmpLogo" value="<?php echo!empty($orgData['om_LogoImage']) ? $orgData['om_LogoImage'] : '' ?>">
                            <span class="img-note">Note: (Image size - Width: 150px , Height:100px)</span>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
                            <a href="<?php echo base_url(); ?>admin/organisation" >  <button  type="button" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i> Cancel</button></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col-md-12 -->
        <div class="clearfix"></div>
    </section>
    <!-- End .content -->
</div>
<!-- End .content-wrapper --> <!-- End .content-wrapper -->
<!-- START:: Footer -->

<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_organisation/js_organisation.js"></script>

<script type="text/javascript">
    $('#upload').on('click', function () {
        $('#my-ownprf').trigger('click');
    });

    $("#my-ownprf").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
    function imageIsLoaded(e) {
        $('.abc').attr("src", e.target.result);
        // $('.comp-logo').css('background', 'url("' + event.target.result + '")');
    }
    ;

</script>
</body>
</html>