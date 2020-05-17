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
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Add Tax </h1>
         <div class="box box-primary no-height">
            <form id="add_tax" action="<?php echo base_url(); ?>admin/tax-action" method='post'   enctype="multipart/form-data"> 
            <div class="box-body no-height">
               <div class="col-md-12 form-group">
                  <label>Tax</label>
                  <div class="input-group">
                    <input type="text" name="tax" class="form-control" autocomplete="off">
                    <span class="input-group-addon" id="basic-addon2">%</span>
                  </div>
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
         </form>
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="col-md-8 no-pad-right">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Tax List</h1>
         <div class="box box-primary" >
            <div class="box-body">
               <form id="filter"  method='get'   enctype="multipart/form-data"> 
             <div class="row">
                 <div class="col-md-3 form-group">

                  <label>Tax</label>
                  <!-- <input type="text" name="sport_club" id="sport_club"> -->
                     <input type="text" id="tax" name="tax" class="form-control" value="<?php echo !empty($tax) ? ($tax): '';?>" placeholder="" autocomplete="off">
                 
               </div> 
                <div class="col-md-3 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/tax');?>';" >Search</button>
                  </div>
              </div>
            </form>
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="12%">Sr. No.</th>
                        <th width="18%">Date</th>
                        <th width="70%">Tax</th>
                         <th width="70%">Status</th>
                      </tr>
                  </thead>
                  <tbody>
                     <?php if(!empty($taxDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                            foreach ($taxDetails as $key => $value) {
                        
                             ?>
                     <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= !empty($value['createdDate']) ? date('d-m-Y', strtotime($value['createdDate'])) : "";  ?></td>
                        <td><?= !empty($value['tax'] )? $value['tax'] : "";  ?>%</td>
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/tax-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>

                     </tr>
                   <!--   <tr>
                        <td class="text-center">2</td>
                        <td>19-6-2019</td>
                        <td>16%</td>
                     </tr> -->
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
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_master/tax.js'); ?>"></script>
<script>
   $(".masterLi").addClass("active");
   $(".taxLi").addClass("active");

   // $("#example").DataTable();
</script>
</body>
</html>