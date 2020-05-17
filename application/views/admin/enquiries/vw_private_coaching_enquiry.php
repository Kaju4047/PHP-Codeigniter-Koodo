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
      <h1>Private Coaching Enquiry List </h1>
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

              <!--   <div class="col-md-2 form-group">
                    <label>Enquiry Type</label>
                    <select class="form-control" name="type">
                      <option value="">Select Type</option>
                      <option value="1"<?php echo( (!empty($type) && $type=='1')?'selected' : '') ?>>Advertisement</option>
                      <option value="2"<?php echo( (!empty($type) && $type=='2')?'selected' : '') ?>>Bulk Requirement</option>  
                      <option value="3"<?php echo( (!empty($type) && $type=='3')?'selected' : '') ?>>Sponsors</option>  
                      <option value="4"<?php echo( (!empty($type) && $type=='4')?'selected' : '') ?>>Feedback</option>  
                    </select>
                </div>
                -->
                <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/private-coaching-enquiry');?>';" ><i class="fa fa-filter"></i> Filter</button>
                  </div>
                  <div class="col-md-2 form-group">
                    <?php if(!empty($privateEnqDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/private-enquiry-export-to-excel');?>';" >Export to Excel</button>
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
                        <th width="18%">Name</th>
                        <th width="10%">Mobile No.</th>
                        <th width="32%">Email Id</th>
                        <th width="5%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if (!empty($privateEnqDetails)) {
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                       foreach ($privateEnqDetails as $key => $value) { 

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
                        
                        <!-- <td><button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#prviatecoachenqModal"><i class="fa fa-eye"></i></button></td> -->
                         <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_dataView(<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>)"><i class="fa fa-eye"></i></button></td>
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

<!-- Modal -->
<div class="modal fade" id="prviatecoachenqModal" role="dialog" TABINDEX=-1>
   <div class="modal-dialog">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
            <h4 class="modal-title">Private Coaching Enquiry Details</h4>
         </div>
         <div class="modal-body">
          <!--   <div class="col-md-6">
                <label>Private Coaching Enquiry</label>
                <h2 class="view-cnt">Lorem ipsum</h2>
            </div> -->
            <div class="col-md-6">
                <label>Sport</label>
                <h2 class="view-cnt" id="sportname"></h2>
            </div>
            <div class="col-md-6">
                <label>Coach Level</label>
                <h2 class="view-cnt" id="coach-level"></h2>
            </div>
            <div class="col-md-6">
                <label>Number of Sessions Required</label>
                <h2 class="view-cnt" id="no_session"></h2>
            </div>
            <div class="col-md-12">
                <label>Description</label>
                <h2 class="view-cnt" id="description"></h2>
            </div>
            <div class="col-md-12">
                <label>Location</label>
                <h2 class="view-cnt" id="location"></h2>
            </div>
        </div>
      <div class="modal-footer">
      </div>
   </div>
</div>
</div>
<!----Modal End---->

<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
   $(".mainenqsLi").addClass("active");
   $(".prvcoenqLi").addClass("active");
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

      function get_dataView(id){
      // alert(id);
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";

              $.ajax({
                  type: "get",
                  data: {id: id},
                  url: base_url + "admin/enquiries/Cn_enquiries/view",
                  dataType: 'json',
                                       
                   success: function (data)
                   {
                    // alert(JSON.stringify(data));
                    
                        $("#prviatecoachenqModal").modal('show');
                
                         $("#sportname").text(data.sportname);
                         $("#coach-level").text(data.coach_level);
                         $("#no_session").text(data.no_session);
                         $("#location").text(data.location);
                         $("#description").text(data.comment);
                                                
                    }
               });
        } 
    }

</script>
</body>
</html>