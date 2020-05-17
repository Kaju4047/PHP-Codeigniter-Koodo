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
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Add City </h1>
         <div class="box box-primary no-height">
            <div class="box-body no-height">
                <div class="col-md-12 form-group">
                  <label>State</label>
                 <select class="form-control">
                    <option selected disabled>Select State</option>
                    <option>Andhra Pradesh</option>
                    <option>Arunachal Pradesh</option>
                    <option>Assam</option>
                    <option>Bihar</option>
                    <option>Chhattisgarh</option>
                    <option>Goa</option>
                    <option>Gujarat</option>
                    <option>Haryana</option>
                    <option>Himachal Pradesh</option>
                    <option>Jammu and Kashmir</option>
                    <option>Jharkhand</option>
                    <option>Karnataka</option>
                    <option>Kerala</option>
                    <option>Madhya Pradesh</option>
                    <option>Maharashtra</option>
                    <option>Manipur</option>
                    <option>Meghalaya</option>
                    <option>Mizoram</option>
                    <option>Nagaland</option>
                    <option>Odisha</option>
                    <option>Punjab</option>
                    <option>Rajasthan</option>
                    <option>Sikkim</option>
                    <option>Tamil Nadu</option>
                    <option>Telangana</option>
                    <option>Tripura</option>
                    <option>Uttar Pradesh</option>
                    <option>Uttarakhand</option>
                    <option>West Bengal</option>
                    <option>Andaman and Nicobar Islands</option> 
                    <option>Chandigarh</option>
                    <option>Dadar and Nagar Haveli</option>
                    <option>Daman and Diu</option>
                    <option>Delhi</option>
                    <option>Lakshadweep</option>
                    <option>Puducherry</option>
                 </select>
               </div>
               <div class="col-md-12 form-group">
                  <label>City</label>
                  <input type="text" class="form-control">
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
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">City List</h1>
         <div class="box box-primary" >
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="13%">Sr. No.</th>
                        <th width="32%">State</th>
                        <th width="47%">City</th>
                        <th width="4%">Status</th>
                        <th width="4%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="text-center">1</td>
                        <td>Maharashtra</td>
                        <td>Pune</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-on " aria-hidden="true" title="Active"></i></td>
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
   $(".cityLi").addClass("active");

   $("#example").DataTable();
</script>
</body>
</html>