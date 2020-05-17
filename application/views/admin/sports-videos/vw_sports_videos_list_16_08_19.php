<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Sports Videos List 
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/add-sports-videos"><button type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Video</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="15%">Date Time</th>
                        <th width="14%">Type</th>
                        <th width="52%">Video Heading</th>
                        <th width="2%">Status</th>
                        <th width="9%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="text-center">1</td>
                        <td>18-6-2019 9:30 AM</td>
                        <td>Cricket</td>
                        <td>natus error sit unde omnis</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-on " aria-hidden="true" title="Active"></i></td>
                        <td class="text-center">
                           <button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#viewvideosModal"><i class="fa fa-eye"></i></button>
                           <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
                           <a href="#"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                        </td>
                     </tr>
                     <tr>
                        <td class="text-center">2</td>
                        <td>18-6-2019 10:30 AM</td>
                        <td>Hockey</td>
                        <td>sit voluptatem accusantium</td>
                        <td class="text-center"><i class="fa fa-toggle-on tgle-off fa-rotate-180" aria-hidden="true" title="Inactive"></i></td>
                        <td class="text-center">
                           <button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#viewvideosModal"><i class="fa fa-eye"></i></button>
                           <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
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
<!-- Modal -->
<div class="modal fade" id="viewvideosModal" role="dialog" TABINDEX=-1>
   <div class="modal-dialog">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
            <h4 class="modal-title">Sport Video Details</h4>
         </div>
         <div class="modal-body">
           
               <div class="col-md-3">
                  <label>Type</label>
                  <h2 class="view-cnt">Cricket</h2>
               </div>
               <div class="col-md-9">
                  <label>Video Heading</label>
                  <h2 class="view-cnt">Quis autem vel eum iure reprehenderit</h2>
               </div>
            
            <div class="col-md-12">
               <label>Description</label>
               <h2 class="view-cnt">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur</h2>
            </div>
              <div class="col-md-9">
                  <label>Youtube Video</label>
                  <h2 class="view-cnt">https://www.youtube.com/watch?v=20wUSsmzo48</h2>
               </div>
      </div>
      <div class="modal-footer">
      </div>
   </div>
</div>
</div>
<!----Modal End---->
<script>
   $(".sportvidLi").addClass("active");
   $("#example").DataTable();
</script>
</body>
</html>