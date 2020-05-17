<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>User Product List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
          <div class="box-body no-height mg-bot-10">
             <div class="row">
                <div class="col-md-2 form-group">
                    <label>Start Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="startdate" class="form-control" placeholder="dd-mm-yyyy">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label>End Date </label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="enddate" class="form-control" placeholder="dd-mm-yyyy">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label>User</label>
                    <select class="form-input form-control select2">
                      <option selected disabled>Select User</option>
                      <option>Joy Mathur</option>
                      <option>David Morgan</option>
                    </select>
                </div>

                 <div class="col-md-2 form-group">
                     <button type="button" class="btn btn-primary filter-btn"><i class="fa fa-filter"></i> Filter</button>
                  </div>
              </div>
            </div>
          </div>
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="8%">Date</th>
                        <th width="39%">User Details</th>
                        <th width="7%">Image</th>
                        <th width="14%">Product Name</th>
                        <th width="12%">Category</th>
                        <th width="10%">Cost (Rs.)</th>
                        <th width="1%">Status</th>
                        <th style="text-align: center !important;" width="1%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="text-center">1</td>
                        <td>18-6-2019</td>
                        <td>David Morgan davidmorgan@gmail.com 9966338822 Viman Nagar,Pune.</td>
                        <td class="text-center"><img src="<?php echo base_url(); ?>AdminMedia/images/default.png" width="100%"></td>
                        <td>Product2</td>
                        <td>Category2</td>
                        <td>2200</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-on " aria-hidden="true" title="Active"></i></td>
                        <td class="text-center">
                           <a href="#"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                        </td>
                     </tr>
                     <tr>
                        <td class="text-center">2</td>
                        <td>19-6-2019</td>
                        <td>David Morgan davidmorgan@gmail.com 9638527410 Kothrud,Pune.</td>
                        <td class="text-center"><img src="<?php echo base_url(); ?>AdminMedia/images/default.png" width="100%"></td>
                        <td>Product3</td>
                        <td>Category3</td>
                        <td>2400</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-off fa-rotate-180" aria-hidden="true" title="Inactive"></i></td>
                        <td class="text-center">
                           <a href="#"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
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
   $(".userprodsLi").addClass("active");
   $(".prodsLi").addClass("active");
   $("#example").DataTable();

   $(".select2").select2();

   $('#startdate').datepicker(
      { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
      });

    $('#enddate').datepicker(
      { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
      });

</script>
</body>
</html>