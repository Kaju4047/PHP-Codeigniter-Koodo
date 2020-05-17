<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<?php
   (in_array('superAdmin', $privilige) ) ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
   ?>
<!-- END:: Header -->
<style type="text/css">
   input[type=file] {
   padding: 2px;
   display: block;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <?php if (empty($editData['UA_pkey'])) { ?>Add System User<?php } else { ?>Edit System User<?php } ?>
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/sub-user">  <button class="btn btn-danger btn-size"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-pad">
         <div class="box box-primary">
            <form class="" role="form" method="post" id="Frmuser" name="Frmuser" action="<?php echo base_url(); ?>admin/add-sub-user-action" enctype="multipart/form-data">
               <input type="hidden" id="txtPkey" name="txtPkey" value="<?php echo!empty($editData['UA_pkey']) ? $editData['UA_pkey'] : ""; ?>">
               <div class="box-body">
                  <div class="col-sm-12 no-pad">
                     <div class="col-md-9">
                        <div class="row">
                           <div class="col-sm-6 form-group">
                              <label>Name<span style="color: red;">*</span></label>
                              <input type="text" class="form-control isAlpha" id="txtName" name="txtName" value="<?php echo!empty($editData['UA_Name']) ? $editData['UA_Name'] : ""; ?>">
                           </div>
                           <div class="col-sm-6 form-group">
                              <label>Mobile No.<span style="color: red;">*</span></label>
                              <input type="text" id="txtMobile" name="txtMobile" class="form-control isInteger" minlength="10" maxlength="10" value="<?php echo!empty($editData['UA_mobile']) ? $editData['UA_mobile'] : ""; ?>">
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6 form-group">
                              <label>Address<span style="color: red;">*</span></label>
                              <textarea class="form-control" id="txtAddress" name="txtAddress" rows="3" style="resize: none;"><?php echo!empty($editData['UA_Address']) ? $editData['UA_Address'] : ""; ?></textarea>
                           </div>
                           <div class="col-sm-6 form-group">
                              <label>City<span style="color: red;">*</span></label>
                              <input type="text" class="form-control" id="txtCity" name="txtCity" value="<?php echo!empty($editData['UA_City']) ? $editData['UA_City'] : ""; ?>">
                           </div>
                        </div>
                        <!--  <div class="col-sm-4 form-group">
                           <label>Email Id</label>
                               <input type="text" class="form-control">
                           </div> -->
                        <div class="row">
                           <div class="col-sm-6 form-group">
                              <label>Email<span style="color: red;">*</span></label>
                              <input type="text" class="form-control" id="txtEmail" name="txtEmail" value="<?php echo!empty($editData['UA_email']) ? $editData['UA_email'] : ""; ?>" <?php echo!empty($editData['UA_email']) ? 'readonly' : ""; ?>>
                           </div>
                           <div class="col-sm-6 form-group">
                              <label>Password<span style="color: red;">*</span></label>
                              <?php
                                 if (!empty($editData['UA_password'])) {
                                     $UA_password = "";
                                     $UA_password = base64_decode($editData['UA_password']);
                                 }
                                 ?>
                              <input type="text" class="form-control" id="txtPassword" name="txtPassword" value="<?php echo!empty($UA_password) ? $UA_password : ""; ?>" >
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <?php $LogoLink = (!empty($editData['UA_Image']) && !empty($editData['UA_pkey'])) ? 'AdminMedia/upload/user/' . $editData['UA_pkey'] . '/' . $editData['UA_Image'] : 'AdminMedia/images/default.png'; ?>
                        <div class="row">
                           <div class="col-md-12">
                              <label class="lab-photo">Profile Photo</label>
                           </div>
                        </div>
                        <div class="form-group">
                           <img src=" <?php echo!empty($LogoLink) ? base_url() . $LogoLink : ""; ?>" class="prof-photo" width="150">
                        </div>
                        <input name="fileCmpLogo" class="form-control" id="my-prf" type="file" >
                     </div>
                  </div>
                  <div class="col-md-9 no-pad m-t-10">
                     <div class="col-md-12">
                        <label>Privileges<span style="color: red;">*</span></label>
                        <table id="" class="table color-table info-table table-bordered">
                           <thead>
                              <tr>
                                 <th width="10%" class="text-center">Sr. No.</th>
                                 <th width="75%">Pages</th>
                                 <th width="15%" class="text-center">Privilege</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td class="text-center">1</td>
                                 <td>CMS</td>
                                 <td class="text-center"><input value="CMS" id="CMS" name="priviliges[]"  type="checkbox"></td>
                              </tr>
                                <tr>
                                 <td class="text-center">2</td>
                                 <td>Subscription</td>
                                 <td class="text-center"><input value="subscription" id="subscription" name="subscription[]"  type="checkbox"></td>
                              </tr>
                                <tr>
                                 <td class="text-center">3</td>
                                 <td>Users</td>
                                 <td class="text-center"><input value="users" id="users" name="users[]"  type="checkbox"></td>
                              </tr>
                                <tr>
                                 <td class="text-center">4</td>
                                 <td>User Reviews</td>
                                 <td class="text-center"><input value="reviews" id="reviews" name="reviews[]"  type="checkbox"></td>
                              </tr>
                           </tbody>
                        </table>
                        <div for="priviliges[]" generated="true" class="mandSpan"></div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <button type="submit" class="btn btn-success btn submit"><i class="fa fa-check-circle"></i> Submit</button>
                     <a href="<?php echo base_url() . $this->uri->uri_string(); ?>"><button type="button" name="button" id="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a>
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
<script type="text/javascript">
   $.validator.addMethod("alphabetsnspace", function (value, element) {
       return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
   });
</script>
<script type="text/javascript">
   $.validator.addMethod("alphabetsnspace2", function (value, element) {
       return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(value);
   });
</script>
<script type="text/javascript">
  $(".sysLi").addClass("active");
   /*[start::code to set priviliges checked at time of update user]*/
   $(document).ready(function () {
       setSelectedPriviliges();
   });
   
   function setSelectedPriviliges() {
       var priviliges = '<?php echo!empty($editData['UA_priviliges']) ? $editData['UA_priviliges'] : ''; ?>';
   
       if (priviliges != "") {
           var privliges_array = priviliges.split(",");
           var length = privliges_array.length;
           for (var n = 0; n < length; n++) {
               $("#" + privliges_array[n]).prop("checked", true);
           }
       }
   }
   /*[end::code to set priviliges checked at time of update user]*/
   
   /*[start::code to set profile image when it change on time]*/
   $("#my-prf").change(function () {
       if (this.files && this.files[0]) {
           var reader = new FileReader();
           reader.onload = imageIsLoaded;
           reader.readAsDataURL(this.files[0]);
       }
   });
   function imageIsLoaded(e) {
       $('.prof-photo').attr("src", e.target.result);
   }
   /*[start::code to set profile image when it change on time]*/
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_userAdminstration/js_userAdminstration.js"></script>
</body>
</html>