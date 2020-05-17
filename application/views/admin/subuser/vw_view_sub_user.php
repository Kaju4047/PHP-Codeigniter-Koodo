<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<?php
   (in_array('superAdmin', $privilige) ) ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
   ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         System User Details
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/sub-user"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-pad">
         <div class="box box-primary" style="min-height: 0px;">
            <div class="box-body" >
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-3 form-group">
                        <?php $Subimg = !empty($cmpList[0]['UA_Image']) ? 'AdminMedia/upload/user/' . $cmpList[0]['UA_pkey'] . '/' . $cmpList[0]['UA_Image'] : 'AdminMedia/images/default.png'; ?>
                        <img src="<?php echo!empty($Subimg) ? base_url() . $Subimg : ''; ?>" class="view-cnt" style="width:100%">
                     </div>
                     <!-- End form-group Photocopy -->
                     <div class="col-md-9">
                        <div class="row">
                           <div class="col-md-6 form-group">
                              <label> Name</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_Name']) ? ucfirst($cmpList[0]['UA_Name']) : ''; ?></h2>
                           </div>
                           <!-- End form-group -->
                           <div class="col-md-6 form-group">
                              <label>Mobile No.</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_mobile']) ? $cmpList[0]['UA_mobile'] : ''; ?></h2>
                           </div>
                           <!-- End form-group -->
                        </div>
                        <div class="row">
                           <div class="col-md-6 form-group">
                              <label>Address</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_Address']) ? ucfirst($cmpList[0]['UA_Address']) : ''; ?></h2>
                           </div>
                           <!-- End form-group -->
                           <div class="col-md-6 form-group">
                              <label>City</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_City']) ? ucfirst($cmpList[0]['UA_City']) : ''; ?></h2>
                           </div>
                           <!-- End form-group -->
                        </div>
                        <div class="row">
                           <div class="col-md-6 form-group">
                              <label>Email</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_email']) ? $cmpList[0]['UA_email'] : ''; ?></h2>
                           </div>
                           <!-- End form-group -->
                           <div class="col-md-6 form-group">
                              <?php
                                 if (!empty($cmpList[0]['UA_password'])) {
                                     $UA_password = "";
                                     $UA_password = base64_decode($cmpList[0]['UA_password']);
                                 }
                                 ?>
                              <label>Password</label>
                              <h2 class="view-cnt"><?php echo!empty($UA_password) ? $UA_password : ""; ?></h2>
                           </div>
                           <!-- End form-group -->
                        </div>
                        <div class="row">
                           <div class="col-md-12 form-group">
                              <label>Privileges</label>
                              <h2 class="view-cnt"><?php echo!empty($cmpList[0]['UA_priviliges']) ? ucfirst($cmpList[0]['UA_priviliges']) : '-'; ?></h2>
                           </div>
                           <!-- End form-group -->
                        </div>
                     </div>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <!-- End box-body -->
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="clearfix"></div>
   </section>
   <!-- End .content -->
</div>
<!-- End .content-wrapper -->
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script type="text/javascript">
   $(".sysLi").addClass("active");
</script>
</body>
</html>