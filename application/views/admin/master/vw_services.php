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
// print_r($privilige);exit();
?>
<?php
// print_r($privilige);die();
(in_array('master', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="col-md-4 no-pad">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Update service</h1>
         <div class="box box-primary no-height">
            <form id="add_services" action="<?php echo base_url(); ?>admin/services-action" method='post'   enctype="multipart/form-data">
            <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
            <div class="box-body no-height">
               <div class="col-md-12 form-group">
                  <label>Service Name</label>
                  <input type="text" name="serviceName" readonly  class="form-control"  value=" <?php echo (!empty($edit['sportname']) ? $edit['sportname'] : ''); ?>" autocomplete="off">
               </div>
               <div class="col-sm-12 form-group">
                  <label>Upload Icon</label>

                  <input type="file"  name="serviceimage" id="serviceimage" class="form-control upld-file">
                  <?php $imgdata = !empty($edit['sportimg']) ? 'uploads/master/sportimage/' . $edit['sportimg'] : 'AdminMedia/images/default.png'; ?>
                    <input type="hidden" name="fileold" id="fileold" class="form-control" value="<?php echo (!empty($edit['sportimg']) ? $edit['sportimg'] : ''); ?>">
                  <img src="<?php echo base_url(). $imgdata;?>" class="img-upload" width="50%">

               
                  <span class="img-note">Note: (Upload PNG file & Image size - Width: 100px , Height:100px)</span>
               </div>
               <!-- End form-group -->           
               <div class="clearfix"></div>
               <div class="col-md-12 form-group">
                <?php if (!empty($edit)) { ?>
                
                  <button type="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Save</button>
               <? } ?>
                  <!-- <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
               </div>
               <!-- End form-group -->  
            </div>
            <!-- End box-body -->
          </form>
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="col-md-8 no-pad-right">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Service List</h1>
         <div class="box box-primary" >
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="11%">Sr. No.</th>
                        <th width="12%">Icon</th>
                        <th width="59%">Service Name</th>
                      <!--   <th width="10%">Status</th> -->
                        <th width="8%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($seviceDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                         foreach ($seviceDetails as $key => $value) {
             
                       ?>
                     <tr>
                        <td class="text-center"><?php echo $i ;?></td>
                        <td class="text-center">
                            <?php $imgdata = !empty($value['sportimg']) ? 'uploads/master/sportimage/' . $value['sportimg'] : 'AdminMedia/images/default.png'; ?>
                             <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="100%">

                          <!-- <img src="<?php echo base_url(); ?>AdminMedia/images/default.png" width="100%"></td> -->
                        <td><?php echo ucwords($value['sportname']); ?></td>
                    <!--     <td class="text-center">
                                            <?php
                                            $status = ""; 
                           
                                            if ($value['status'] == "1") {
                                                $status = "2";
                                                $class = "fa fa-toggle-on tgle-on";
                                                $title = "Active";
                                            } else if ($value['status'] == "2") {
                                                $status = "1";
                                                $class = "fa fa-toggle-on fa-rotate-180 tgle-off";
                                                $title = "Inactive";
                                            }
                                            ?>
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/services-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td> -->
                        <td class="text-center">
                         <!--   <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a> -->
                         <?php $page = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 1;?>
                            <a href="<?php echo base_url(); ?>admin/services/<?php echo $page?>?edit=<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
                        </td>
                     </tr>
                      <?php $i++;}}?>
                  </tbody>
               </table>
                <ul class="pagination pull-right" >
                    <?php if (isset($follow_links) && !empty($follow_links)) { ?>
                   <p><?php echo $follow_links ?></p>
                 <?php } ?>
              </ul>
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
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_master/services.js'); ?>"></script>
<script>

  $(".masterLi").addClass("active");
  $(".serviceLi").addClass("active");

   // $("#example").DataTable();

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