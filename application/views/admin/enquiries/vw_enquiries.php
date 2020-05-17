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
(in_array('enquiry', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Enquiries List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">

         <div class="box box-primary no-height">
          <div class="box-body no-height" style="margin-bottom: 10px;">
            <form id="filter"  method='get'   enctype="multipart/form-data"> 
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
                    <label>Enquiry Type</label>
                    <select class="form-control" name="type">
                      <option value="">Select Type</option>
                      <option value="1"<?php echo( (!empty($type) && $type=='1')?'selected' : '') ?>>Advertisement</option>
                      <option value="2"<?php echo( (!empty($type) && $type=='2')?'selected' : '') ?>>Bulk Requirement</option>  
                      <option value="3"<?php echo( (!empty($type) && $type=='3')?'selected' : '') ?>>Sponsors</option>  
                      <option value="4"<?php echo( (!empty($type) && $type=='4')?'selected' : '') ?>>Feedback</option>  
                    </select>
                </div>
               
                <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-enq');?>';" ><i class="fa fa-filter"></i> Filter</button>
                  </div>
                  <div class="col-md-2 form-group">
                    <?php if(!empty($enqDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/enquiry-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div>
              </div>
            </form>
            </div>
          </div>


         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="7%">Sr. No.</th>
                        <th width="9%">Enq. Date</th>
                        <th width="10%">Enq. Type</th>
                        <th width="9%">User Type</th>
                        <th width="15%">Name</th>
                        <th width="10%">Mobile No.</th>
                        <th width="16%">Email Id</th>
                        <th width="24%">Comment</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if (!empty($enqDetails)) {
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                       foreach ($enqDetails as $key => $value) { 

                    ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($value['createdDate']));?></td>
                        <td><?php echo $value['enqtype'];?></td>
                        <td>
                        <?php if(!empty($value[0])){
                          // echo "<pre>";
                          // print_r($value[0]);
                                foreach ($value[0] as $key => $val) {
                        ?>
                        <?php  echo !empty($val['usertype']) ? ucfirst($val['usertype']) : '';
                           ?>

                      <?php }
                    }?>
                    </td>
                        <!-- <td><?php echo $value['name'];?></td> -->
                        <td><?php echo $value['name'];?></td>
                        <td><?php echo $value['mob'];?></td>
                        <td><?php echo $value['email'];?></td>
                        
                        <td><?php echo ucfirst($value['comment']);?></td>
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
   $(".enqsLi").addClass("active");
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

// aler(fromstart);

    $('#fromdate').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: nowDate

    }).on('changeDate', function (selected) {
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