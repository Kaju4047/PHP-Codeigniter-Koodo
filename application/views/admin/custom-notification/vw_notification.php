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
(in_array('cust_note', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 555px;">
  <!-- <section class="content-header">
    <h1>Notification List</h1>
  </section> -->
  <!-- Main content -->
  <section class="content">
    <div class="col-md-4 no-pad">
      <h1 style="font-size: 24px; margin: 0 0 15px 0;">Send Notification </h1>
       <form id="notification" action="<?php echo base_url(); ?>admin/notification-action" method='post'   enctype="multipart/form-data">
       <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
      <div class="box box-primary no-height">
        <div class="box-body no-height mob-bot">
           <div class="form-group col-md-12">
              <label class="label-name">Type</label>
              <select class="form-control" name="type">
                   <option selected disabled>Select Type</option>
                <option value="4">All</option> 
                   <?php if (!empty($notificationTypeDetails)) {
                    foreach ($notificationTypeDetails as $key => $value){
                      // print_r($value);
                    ?>
                  <!--   <?= $value['custtype'];?>
                    <?= $value['pk_id'];?> -->
                    


                    <option value="<?= $value['pk_id'];?>"><?= $value['custtype'];?></option>
                  <?php }}?>
              </select>
          </div> 
          <div class="col-md-12 form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control">
          </div>

          <div class="col-md-12 form-group">
             <label>Message</label>
             <textarea class="form-control" name="message" rows="8" style="resize: none;"></textarea>
          </div>

          <!-- End form-group -->                 
          <div class="clearfix"></div>
          <div class="col-md-12 form-group" style="margin-top: 10px;">          
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button>
          </div>
          <!-- End form-group -->  
        </div>
        <!-- End box-body -->
      </div>
      <!-- End box -->
    </form>
    </div>
    <!-- End col-md-4 -->
    <div class="col-md-8 no-pad-right no-mob-pad">
      <h1 style="font-size: 24px; margin: 0 0 15px 0;">Notification List</h1>

     
      <div class="box box-primary no-height">


        <div class="box-body no-height">
          <div class="col-md-12">
             <div class="pull-right">
             <!-- <div class="box box-primary no-height"> -->
          <!-- <div class="box-body no-height mg-bot-10"> -->
             <form id="filter"  method='get'  enctype="multipart/form-data"> 
             <div class="row">
                          

             <!--    <div class="col-md-3 form-group">
                    <label>User Type</label>
                    <select class="form-control" name="type">
                      <option  value="">Select Type</option>
                      <!-- 
                      <option value="<?php echo !empty($value['pk_id']) ?$value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php echo !empty($value['usertype']) ?$value['usertype'] : '';?></option> -->
                     <!--   <option value="2">Dealer</option>
                      <option value="3">Coach</option> 
                
                   
                    </select>
                </div> --> 
              
                 
              

               <!--   <div class="col-md-3 form-group">
                 
                     <button type="submit" class="btn btn-primary filter-btn fa fa-filter" onclick="javascript: form.action='<?php echo base_url('admin/filter-user');?>';">       Filter    </button>
                  </div> -->
                

                  <div class="col-md-3 form-group">

              <?php if(!empty($notificationDetails)){  

                                  ?>     
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/cust-note-export-to-excel');?>';"><i class="fa fa-report" aria-hidden="true"></i> Export to Excel</button>
                      <?php  } ?>
                  </div> 
                </div>
          </form>
         </div> 


            <div class="row">
                
          <table id="example" class="table table-bordered table-striped table-hover">
           
            <thead class="table-example">
              <tr>
                <th width="8%">Sr. No.</th>
                <th width="22%">Date &amp; Time</th>
                <th width="11%">Type</th>
                <th width="22%">Subject</th>
                <th width="37%">Message</th>
               </tr>
            </thead>
            <tbody>
            <?php if (!empty($notificationDetails)) {
                     $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                          $i = ($page_no * 10) - 9;
                    foreach ($notificationDetails as $key => $value) {
                       $newDate = date("d-m-Y g:i A", strtotime($value['created_date']));

             ?>
              <tr>
                <td class="text-center"><?php  echo $i; ?></td>
                <td><?php echo $newDate?></td>
                
                <td><?php if($value['type']=='4'){
                              echo"All";
                           }else{
                            echo ucfirst($value['custtype']);
                          } ?></td>
                <td><?php echo ucfirst($value['subject']); ?></td>
                <td><?php echo ucfirst($value['message']); ?></td>
              </tr>
            
              <?php $i++; }}?>
            </tbody>
          </table>
          <ul class="pagination pull-right" >
                    <?php if (isset($follow_links) && !empty($follow_links)) { ?>
                   <p><?php echo $follow_links ?></p>
                 <?php } ?>
              </ul>
        </div>
      </div>
          <!-- <nav aria-label="..." class="pagn-left">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item">
              <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
            </li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav> -->
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

<!-- Modal -->
<!--   <div class="modal fade" id="viewprodModal" role="dialog" TABINDEX=-1>
    <div class="modal-dialog">
    
      <!-- Payment Modal start-->
    <!--   <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Used Product Details</h4>
        </div>
        <div class="modal-body">
         <div class="col-md-12 no-pad">
          
         <div class="col-md-4">
            <label>User</label>
            <h2 class="view-cnt">John Doe</h2>
         </div>
         
         <div class="col-md-3">
            <label>Mobile No.</label>
            <h2 class="view-cnt">9876543210</h2>
         </div>
         <div class="col-md-5">
            <label>Email Id</label>
            <h2 class="view-cnt">johndoe@gmail.com</h2>
         </div>
         <div class="col-md-12">
            <label>Address</label>
            <h2 class="view-cnt">Landmark 203, Second Floor, Above Andhra Bank ,Vishrantwadi, Pune 411015</h2>
         </div>
         <div class="col-md-4">
            <label>Product Name</label>
            <h2 class="view-cnt">Product1</h2>
         </div>
         <div class="col-md-4">
            <label>Category</label>
            <h2 class="view-cnt">Category1</h2>
         </div>
         <div class="col-md-4">
            <label>Cost</label>
            <h2 class="view-cnt"><i class="fa fa-rupee"></i> 2000</h2>
         </div>
          <div class="col-md-12">
            <label>Description</label>
            <h2 class="view-cnt">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur</h2>
         </div>
        </div>
     </div>
        <div class="modal-footer">
         </div>
      </div>
      
    </div>
  </div> -->
<!----Modal End---->

<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_notification/notification.js'); ?>"></script>
<script>
   $(".notifiLi").addClass("active")
   // $("#example").DataTable();

   $(".select2").select2();

   $('#fromdate').datepicker(
      { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
      });

    $('#todate').datepicker(
      { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
      });

</script>
</body>
</html>