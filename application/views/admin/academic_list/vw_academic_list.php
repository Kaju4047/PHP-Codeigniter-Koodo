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
(in_array('academy_list', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Coach Academy List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
         <div class="box-body no-height" style="margin-bottom: 10px;">
           <form id="filter"  method='get'  enctype="multipart/form-data"> 
            <div class="row">
               <div class="col-md-4 form-group">
                  <label>From Date</label>
                  <div class="input-group date" data-date-format="dd.mm.yyyy">
                     <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" autocomplete="off">
                     <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 form-group">
                  <label>To Date </label>
                  <div class="input-group date" data-date-format="dd.mm.yyyy">
                     <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" autocomplete="off">
                     <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 form-group">
                  <label>Sport Type</label>
                  <select class="form-control" name="type">
                     <option value="">Select Type</option>
                     <?php if (!empty($sportDetails)) {
                            foreach ($sportDetails as $key => $value) {
                                  
                      ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['sportname']) ? $value['sportname'] : '';?></option>
                   <?php }} ?>
                    
                  </select>
               </div>
               <div class="col-md-4 form-group">
                  <label>Coaching Name</label>
                  <select class="form-control" name="coach">
                     <option value="">Select Coaching Name</option>
                     <?php if (!empty($academyName)) {
                            foreach ($academyName as $key => $value) {
                      ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($coach) && $coach==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['coach_name']) ? $value['coach_name'] : '';?></option>
                    <?php }}?>
                  </select>
               </div>
                 <div class="col-md-3 form-group">
                  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-academy');?>';"><i class="fa fa-filter"></i> Filter</button>
               </div>

                  <div class="col-md-3 form-group">

              <?php if(!empty($academyDetails)){  

                                  ?>     
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/export-to-excel');?>';"><i class="fa fa-report" aria-hidden="true"></i> Export to Excel</button>
                      <?php  } ?>
                  </div> 
             </div>
               <!-- <div class="row"> -->
          <!--    <div class="col-md-3 form-group">

                  <label>City</label>
                  <select class="form-control" name="city">
                     <option value="">Select City</option>
                     <?php if(!empty($cityDetails)) {
                        foreach ($cityDetails as $key => $value) {
                          // print_r($value);
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($city) && $city==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['city_name']) ? $value['city_name'] : '';?></option>
                     <?php }}?>
                  </select>
               </div> -->
             

              <!--  <div class="col-md-2 form-group">              
                     <button type="submit" class="btn btn-primary filter-btn" onclick=""><i class="fa fa-report" aria-hidden="true"></i> Export to Excel</button>                 
              </div> -->
            

                  <!-- PRINT <div class="col-md-3 form-group">

                     <?php if(!empty($academyDetails)){  

                                  ?>     
                     <button type="submit" class="btn btn-primary filter-btn" onclick= "myPrintFunction(this.value);"><i class="fa fa-report" aria-hidden="true"></i>Print</button>
                      <?php  } ?>
                  </div> -->


                    <!-- PDF <div class="col-md-3 form-group">

              <?php if(!empty($academyDetails)){  

                                  ?>     
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/pdf');?>';"><i class="fa fa-report" aria-hidden="true"></i>PDF</button>
                      <?php  } ?>
                  </div>  --> 
            <!-- </div> -->




          </form>
         </div>
      </div>
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="6%">Sr. No.</th>
                        <th width="13%">Academy Name</th>
                        <th width="12%">User Name</th>
                        <th width="9%">Sport Type</th>
                        <th width="7%">Contact Number</th>
                        <th width="7%">Email ID</th>
                        <th width="7%">Website</th>
                        <th width="11%">Start/End Date</th>
                      <!--   <th width="8%">End Date</th> -->
                        <th width="8%">Time</th>
                        <th width="7%">Number of students/batch</th>
                        <th width="1%">Status</th>
                        <th style="text-align: center !important;" width="1%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(!empty($academyDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                             foreach ($academyDetails as $key => $value) {
                             
                        ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td><?php  echo !empty($value['coach_name']) ? $value['coach_name'] : '-';?></td>
                        <td><?php  echo !empty($value['name']) ? $value['name'] : '-';?></td>
                        <td><?php  echo !empty($value['sportname']) ? $value['sportname'] : '-';?></td>
                        <td><?php  echo !empty($value['primary_mobile_no']) ? $value['primary_mobile_no'] : '-';?><?php  echo !empty($value['secondary_mobile_no']) ?','.$value['secondary_mobile_no'] : '';?></td>
                        <td><?php  echo !empty($value['email']) ? $value['email'] : '-';?></td>
                        <td><?php  echo !empty($value['website']) ? $value['website'] : '-';?></td>
                      
                        <td><?php  echo !empty($value['start_date']) ? date('d-m-Y',strtotime($value['start_date'])) : '-';?> to <?php  echo !empty($value['end_date']) ? date('d-m-Y',strtotime($value['end_date'])) : '-';?></td>
                     
                        <td><?php  echo !empty($value['academy_time']) ? date('h:i a',strtotime($value['academy_time'])) : '-';?></td>
                        <td><?php  echo !empty($value['student_number']) ? $value['student_number'] : '-';?></td>                      
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
                                           
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/academy-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>/<?php echo (!empty($value['user_id']) ? $value['user_id'] : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                           <a href="<?php echo base_url(); ?>admin/view-academic-list/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>
                        </td>
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
    $(".academicLi").addClass("active");
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



    var nowDate = new Date();

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
<!-- <script>
function myPrintFunction() {

  var css = '@page { size: landscape; margin: 0mm;}',
    head = document.head || document.getElementsByTagName('print_page')[0],
    style = document.createElement('style');

style.type = 'text/css';
style.media = 'print';

if (style.styleSheet){
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));
}

head.appendChild(style);


  window.print();
}
</script> -->
</body>
</html>