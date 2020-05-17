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
(in_array('career', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Get Hired List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
          <div class="box-body no-height mg-bot-10">
             <div class="row">
              <form id="filter"  method='get'  enctype="multipart/form-data"> 
                 <div class="col-md-3 form-group">
                    <label>Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="fromdate" name="date" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($datefilter) ? date('d-m-Y',strtotime($datefilter)) : '';?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>
                <!-- <div class="col-md-2 form-group">
                    <label>Profile</label>
                    <select class="form-control" name="type">
                      <option  value="" >Select Type</option>
                      <?php if(!empty($usertypeDetails)){
                        foreach ($usertypeDetails as $key => $value) {
                        ?>
                      <option value="<?php echo !empty($value['pk_id']) ?$value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php echo !empty($value['usertype']) ?$value['usertype'] : '';?></option>
                    <?php }}?>
                     
                    </select>
                </div> -->
                <div class="col-md-3 form-group">

                  <label>Work Title</label>
                  <!-- <input type="text" name="sport_club" id="sport_club"> -->
                     <input type="text" id="profile" name="profile" class="form-control" value="<?php echo !empty($profile) ? ($profile): '';?>" placeholder="" autocomplete="off">
                 
               </div>
                
              
                 <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-career');?>';"><i class="fa fa-filter" ></i> Filter</button>
                  </div>
                  <div class="col-md-2 form-group">
                    <?php if(!empty($careerDetails)){?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/career-export-to-excel');?>';">Export to Excel</button>
                   <?php }?>
                  </div>
                </form>
            </div>
          </div>
       </div>
         <div class="box box-primary">
            <div class="box-body">
               <table  class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="10%" >Sr. No.</th>
                        <th width="13%">Date</th>                       
                        <th width="22%">Name</th>
                        <th width="10%">Mobile Number</th>
                        <th width="15%">Email ID</th>
                        <th width="12%">Work Title</th>
                        <th width="10%">Education</th>
                        <th width="15%"> Monthly Remuneration (INR)</th>
                        <th width="25%">Download CV</th>
                        <th width="3%">Status</th>
                        <th style="text-align: center !important;" width="2%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(!empty($careerDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                             foreach ($careerDetails as $key => $value) {
                                 // echo($value);
                              // echo !empty($value[0][0]['userid']) ? $value[0][0]['userid'] : '';
                              // die();
                             
                      ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td><?php  echo !empty($value['createdDate']) ? date('d-m-Y',strtotime($value['createdDate'])) : '';?></td>
                      
                        <td><?php  echo !empty($value['name']) ? ucwords($value['name']) : '';?></td>
                        <td><?php  echo !empty($value['mob']) ? $value['mob'] : '';?></td>
                        <td><?php  echo !empty($value['email']) ? $value['email'] : '';?></td>
                      <!--   <td>
                        <?php if(!empty($value[0])){
                               
                                foreach ($value[0] as $key => $val) {
                                // print_r($val['userid'][0]); 
                              // $value[0]['userid'][0]
                        ?>
                        <?php  echo !empty($val['usertype']) ? ucfirst($val['usertype']) : '';
                         // echo !empty($val['userid']) ? ucfirst($val['userid']) : '';

                           ?>
                      <?php }}?>
                      </td> -->
                        <td><?php  echo !empty($value['profile']) ? $value['profile'] : '';?></td>
                        <td><?php  echo !empty($value['qualification']) ? $value['qualification'] : '';?></td>
                        <td> <?php  echo !empty($value['expected_salary']) ? $value['expected_salary'] : '';?></td>
                         
                     

                        <td class="center">

                          <?php $imgdata = !empty($value['cv']) ?  base_url('uploads/career/' . $value['cv']) : 'AdminMedia/images/default.png'; ?>
                          <a href="<?php echo $imgdata; ?>" download>
                          <button type="button" class="btn btn-success btn-xs"><i class="fa fa-download"></i> CV</button>
                          </a>
                        </td>

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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/career-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">

                           <a href="<?php echo base_url(); ?>admin/user-view/<?php echo (!empty($value['user_id']) ? $value['user_id'] : ''); ?>/<?php echo(!empty($value[0][0]['userid'])?$value[0][0]['userid']:'')?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>
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
    $(".careerLi").addClass("active");
    $("#example").DataTable();
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

    
     var nowDate = new Date(); // alert(nowDate);

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