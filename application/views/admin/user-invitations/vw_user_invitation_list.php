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
(in_array('invitation', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>

<div class="content-wrapper">
   <section class="content-header">
      <h1>User Invitation List
      
            <div class="col-md-3 no-pad pull-right">
            <!-- <input type="text" class="form-control" placeholder="Global Search">
            <button type="button" class="btn btn-danger btn-invusr"><i class="fa fa-search" aria-hidden="true"></i></button> -->
             <form name="frmSearch" id="frmSearch" action="<?php echo base_url(); ?>admin/sub-user" method="GET" autocomplete="off">
                        
                <input type="text" name="search_term" id="search_term" class="form-control" value="<?php echo!empty($this->input->get('search_term')) ? $this->input->get('search_term') : ''; ?>" placeholder="Search" style="width: 70%; display: inline-block;margin-left: 43px;">
               
                <button type="submit" class="btn btn-primary" title="Search" onclick="javascript: form.action='<?php echo base_url('admin/user-invitation-list');?>';" style="position: absolute;top: 0;right: 0;margin-right: 15px;height: 34px;"><i class="fa fa-search"></i></button>

            </form>
            </div>
      
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
          <!-- <div class="box box-primary no-height">

           <form id="filter"  method='get'  enctype="multipart/form-data"> 
         
            <div class="box-body no-height" style="margin-bottom: 10px;">
               <div class="row">
                  <div class="col-md-2 form-group">
                     <label>From Date</label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" autocomplete="off">
                        <div class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2 form-group">
                     <label>To Date </label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" autocomplete="off">
                        <div class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                  </div>
               
                  <div class="col-md-2 form-group">
                     
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/user-invitation-list');?>';"><i class="fa fa-filter"></i>Filter</button>
                  </div>
                 <!--  <div class="col-md-2 form-group">
                    <?php if(!empty($reviewDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/review-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div> -->
              <!-- </div>
            </div>
         </form>
         </div> -->
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="14%">Member Id</th>
                        <th width="42%">Member Name</th>
                        <th width="12%">Mobile No.</th>
                        <th width="19%">Total Registrations</th>
                        <th width="7%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                      <?php if(!empty($invitationList)){
                            $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;

                            foreach ($invitationList as $key => $value) {
                                                                       
                      ?>
                      <tr>
                          <td class="text-center"><?php echo $i; ?></td>
                          <td><?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?></td>
                          <td><?php  echo !empty($value['name']) ? ucfirst($value['name']) : '-';?></td>
                          <td><?php  echo !empty($value['mob']) ? $value['mob'] : '-';?></td>
                          <td><?php  echo !empty($value['count']) ? $value['count'] : '0';?></td>
                          <td class="text-center">
                           <a href="<?php echo base_url(); ?>admin/view-user-invitation?id=<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>

                        </td>
                      </tr>
                    <?php $i++;}}?>
                  </tbody>
               </table>
               <ul class="pagination pull-right" >
                    <?php if (isset($follow_links) && !empty($follow_links)) { ?>
                   <p><?php echo $follow_links ?></p>
                 <?php  } ?>
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
<script>
    $(".usrinvtLi").addClass("active");
    // $("#example").DataTable();
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
    var nowDate = new Date(); // alert(nowDate);

    $('#fromdate').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: nowDate
    }).on('changeDate', function (selected){
        var minDate = new Date(selected.date.valueOf());
        $('#todate').datepicker('setStartDate', minDate);
    });

    $('#todate').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        startDate: nowDate}).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#fromdate').datepicker('setEndDate', maxDate);
    });

    
</script>

</body>
</html>