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
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Add City </h1>
         <div class="box box-primary no-height">
           <form id="add_city" action="<?php echo base_url(); ?>admin/city-action" method='post'   enctype="multipart/form-data">
            
            <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
            <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control">
            <input type="hidden" name="pk_id" id="pk_id" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
            <div class="box-body no-height">
                <div class="col-md-12 form-group">
                <label>State</label>
                 <select class="form-control" id="state" name="state">
                    <option selected disabled>Select State</option>
                    <?php if (!empty($stateDetails)) {
                       foreach ($stateDetails as $key => $value) {
                          
                        ?>
                      
                        <option value="<?= $value['state_name'];?>"<?php echo ((!empty($edit['state_name']) && $edit['state_name'] == $value['state_name']) ? 'selected' : ''); ?>><?= ucfirst($value['state_name']);?></option>
                      <?php }}?>
                  
                 </select>
               </div>
               <div class="col-md-12 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" class="form-control" value="<?php echo (!empty($edit['city_name']) ? $edit['city_name'] : ''); ?>" autocomplete="off">
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
       </form>
      </div>
      <!-- End col-md-4 -->
      <div class="col-md-8 no-pad-right">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">City List</h1>
         <div class="box box-primary" >
            <div class="box-body">
                <form id="filter"  method='get'   enctype="multipart/form-data"> 
             <div class="row">
                 <div class="col-md-3 form-group">

                  <label>City</label>
                  <!-- <input type="text" name="sport_club" id="sport_club"> -->
                     <input type="text" id="city" name="city" class="form-control" value="<?php echo !empty($city) ?$city: '';?>" placeholder="" autocomplete="off">
                 
               </div> 
                <div class="col-md-3 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/city');?>';" >Search</button>
                  </div>
              </div>
            </form>
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
                     <?php if(!empty($cityDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                         foreach ($cityDetails as $key => $value) {
             
                       ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php echo ucfirst($value['state_name']);?></td>
                        <td><?php echo ucfirst($value['city_name']);?></td>
                            <td class="text-center">
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/city-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                          <?php $page = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 1;?>
                           <a href="<?php echo base_url(); ?>admin/city/<?php echo $page?>?edit=<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
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
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_master/city.js'); ?>"></script>
<script>
   $(".masterLi").addClass("active");
   $(".cityLi").addClass("active");

   // $("#example").DataTable();
</script>
</body>
</html>