<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <div class="col-md-12 no-pad" style="margin-bottom: 5px;">
         <h1 style="margin: 0px;">
            Coaching Academy List
            <div class="pull-right">
               <a href="<?php echo base_url(); ?>admin/academic-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
            </div>
         </h1>
      </div>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-pad">
         <div class="row">           
            <div class="col-md-12">
               <div class="box box-primary" style="min-height: 0px;">
                  <div class="box-body">
                     <div class="col-md-3 form-group">
                        <?php $img = !empty($academyViewDetails['img']) ? 'uploads/academy/' . $academyViewDetails['img'] : 'AdminMedia/images/default.png';
                        // print_r($img);
                        // exit(); 
                        ?>
                        
                        <img src="<?php echo!empty($img) ? base_url() . $img : ''; ?>" class="view-cnt" style="width:100%">

                  
                     </div>  
                     <div class="col-md-9">         
                         <div class="col-md-4 no-pad form-group">           
                           <label>Coaching Name</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['coach_name']) ? ucwords($academyViewDetails['coach_name']): '';?></h2>
                        </div>
                        <div class="col-md-4 no-pad form-group">
                           <label>Sport Type</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['sportname']) ? ucfirst($academyViewDetails['sportname']) : '';?></h2>
                        </div> 
                         <div class="col-md-4 no-pad form-group">
                           <label>No. of Students</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['student_number']) ? $academyViewDetails['student_number'] : '';?></h2>
                        </div>                
                        <div class="col-md-4 no-pad form-group">
                           <label>Start Date</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['start_date']) ? date('d-m-Y',strtotime($academyViewDetails['start_date'])) : '';?></h2>
                        </div>

                        <div class="col-md-4 no-pad form-group">
                           <label>End Date</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['end_date']) ?  date('d-m-Y',strtotime($academyViewDetails['end_date'])) : '';?></h2>
                        </div>

                        <div class="col-md-4 no-pad form-group">
                           <label>Time</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['academy_time']) ? date('h:i a',strtotime($academyViewDetails['academy_time'] )): '';?></h2>
                        </div>   
                       
                        <div class="col-md-4 no-pad form-group">
                           <label>Fees/Session</label>
                           <h2 class="view-cnt"><i class="fa fa-inr"></i> <?php  echo !empty($academyViewDetails['fees']) ? $academyViewDetails['fees'] : '';?></h2>
                        </div> 
                        <div class="col-md-4 no-pad form-group">
                           <label>City</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['city_name']) ? ucfirst($academyViewDetails['city_name']) : '';?></h2>
                        </div> 
                        <div class="col-md-12 no-pad form-group">
                           <label>Venue</label>
                           <h2 class="view-cnt"><?php  echo !empty($academyViewDetails['venue']) ? ucfirst($academyViewDetails['venue']) : '';?></h2>
                        </div> 
                        </div>
                                                
                     </div>   
                      
                  <!-- End box-body -->
               </div>
            </div>
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="clearfix"></div>
   </section>
</div>
<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
   $(".academicLi").addClass("active");
    $("#example").DataTable();
</script>
</body>
</html>