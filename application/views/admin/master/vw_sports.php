<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="col-md-4 no-pad">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Add Sports </h1>
         <div class="box box-primary no-height">
            <div class="box-body no-height">
               <div class="col-md-12 form-group">
                  <label>Sports Name</label>
                  <input type="text" class="form-control">
               </div>
               <div class="col-sm-12 form-group">
                  <label>Upload Icon</label>
                  <input type="file" class="form-control upld-file">
                  <img src="<?php echo base_url(); ?>AdminMedia/images/default.png" class="img-upload sports-icon" 
               </div>
               <!-- End form-group -->           
               <div class="clearfix"></div>
               <div class="col-md-12 form-group">
                  <button type="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                  <!-- <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
               </div>
               <!-- End form-group -->  
            </div>
            <!-- End box-body -->
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="col-md-8 no-pad-right">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Sports List</h1>
         <div class="box box-primary" >
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="11%">Sr. No.</th>
                        <th width="12%">Icon</th>
                        <th width="59%">Sports Name</th>
                        <th width="10%">Status</th>
                        <th width="8%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="text-center">1</td>
                        <td class="text-center"><img src="<?php echo base_url(); ?>AdminMedia/images/default.png" width="100%"></td>
                        <td>Cricket</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-on " aria-hidden="true" title="Active"></i></td>
                        <td class="text-center">
                           <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
                        </td>
                     </tr>
                      <tr>
                        <td class="text-center">2</td>
                        <td class="text-center"><img src="<?php echo base_url(); ?>AdminMedia/images/default.png" width="100%"></td>
                        <td>Hockey</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-off fa-rotate-180" aria-hidden="true" title="Inactive"></i></td>
                        <td class="text-center">
                           <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <!-- End box-body -->
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-8 -->
      <div class="clearfix"></div>
   </section>
   <!-- End .content -->
</div>
<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
   $(".masterLi").addClass("active");
   $(".sportsLi").addClass("active");

   $("#example").DataTable();

   $(".upld-file").change(function (event) 
   {
        var ev = event.target;
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = imageIsLoaded;
          reader.readAsDataURL(this.files[0]);
      }
      function imageIsLoaded(e) {
       $(ev).siblings('.img-upload').attr("src", e.target.result);
     /*  $(ev).parent().siblings.('.img-upload').css('background', 'url("' + event.target.result + '")');*/
     };
   });    
</script>
</body>
</html>