<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
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
(in_array('users', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<div class="content-wrapper">
   <section class="content-header">
      <h1>Users List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
          <div class="box-body no-height mg-bot-10">
             <form id="filter"  method='get'  enctype="multipart/form-data"> 
             <div class="row">
                 <div class="col-md-3 form-group">
                    <label>From Date</label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="fromdate" name="fromdate" class="form-control" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" placeholder="dd-mm-yyyy" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                 </div>

                <div class="col-md-3 form-group">
                    <label>To Date </label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="todate" name="todate" class="form-control" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" placeholder="dd-mm-yyyy" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label>User Type</label>
                    <select class="form-control" name="type">
                      <option  value="">Select Type</option>
                      <?php if(!empty($usertypeDetails)){
                        foreach ($usertypeDetails as $key => $value) {
                        ?>
                      <option value="<?php echo !empty($value['pk_id']) ?$value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php echo !empty($value['usertype']) ?$value['usertype'] : '';?></option>
                      <!-- <option value="2">Dealer</option>
                      <option value="3">Coach</option> -->
                    <?php }}?>
                   
                    </select>
                </div>
                <div class="col-md-3 form-group">
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
                </div>
               
            </div>
               <div class="row">
                   <div class="col-md-3 form-group">

                  <label>Sport Club</label>
                  <!-- <input type="text" name="sport_club" id="sport_club"> -->
                     <input type="text" id="sport_club" name="sport_club" class="form-control" value="<?php echo !empty($sport_club) ? ($sport_club): '';?>" placeholder="" autocomplete="off">
                 
               </div>
                <div class="col-md-3 form-group">
                    <label>Deleted User</label>
                    <select class="form-control" name="deleted">
                       <option value="">Select </option>                   
                      <option value="Yes"<?php echo ((!empty($deleted) && $deleted == 'Yes') ? 'selected' : ''); ?>>Yes</option>
                      <option value="No"<?php echo ((!empty($deleted) && $deleted == 'No') ? 'selected' : ''); ?>>No</option>                    
                    </select>
                </div>
              

                 <div class="col-md-3 form-group">
                 
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-user');?>';"><i class="fa fa-filter"></i>       Filter    </button>
                  </div>
                

                  <div class="col-md-3 form-group">

              <?php if(!empty($userDetails)){  

                                  ?>     
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/users-list-export-to-excel');?>';"><i class="fa fa-report" aria-hidden="true"></i> Export to Excel</button>
                      <?php  } ?>
                  </div> 
                </div>
          </form>
          </div>
       </div>
         <div class="box box-primary">
            <div class="box-body">
               <table  class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="4%">Sr. No.</th>
                        <th width="8%">Reg. Date</th>
                        <th width="9%">User Type</th>
                        <th width="10%">Name</th>
                        <th width="10%">Mobile No.</th>
                        <th width="15%">Email Id</th>
                        <th width="9%">City</th>
                        <th width="10%">Player Club</th>
                        <th width="10%">Coach club</th> 
                        <th width="3%">Status</th>
                        <th style="text-align: center !important;" width="2%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($userDetails)){
                            $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;                             
                            $i = ($page_no * 10) - 9;
                           foreach ($userDetails as $key => $value) {                             
                      ?>
                     <tr>
                        <td class="text-center"><?php echo $i;  ?></td>
                        <td><?php  echo !empty($value['createdDate']) ? date('d-m-Y',strtotime($value['createdDate'])) : '';?></td>
                        <td>
                        <?php if(!empty($value[0])){
                                foreach ($value[0] as $key => $val) {
                        ?>
                        <?php  echo !empty($val['usertype']) ? ucfirst($val['usertype']) : '-';
                           ?>

                      <?php }
                    }?>
                      </td>
                        <td><?php  echo !empty($value['name']) ? ucwords($value['name']) : '';?></td>
                        <td><?php  echo !empty($value['mob']) ? $value['mob'] : '';?></td>
                        <td> <?php  echo !empty($value['email']) ? $value['email'] : ''; 
                            if (!empty($value['email']) && !empty($value['verifyEmail']) ) {
                              if ($value['email'] == $value['verifyEmail']) {?>
                              <span  style="color: green"> &#10004;</span>
     
                            <?  }
  
                            }
                        ?> </td>
                        <td><?php  echo !empty($value['city_name']) ? ucfirst($value['city_name']) : '-';?>
                        </td>
                         <td>   <?php if(!empty($value[1])){
                                foreach ($value[1] as $key => $val) {
                        ?>
                        <?php  echo !empty($val['club_detail']) ? ucfirst($val['club_detail']):'';
                           ?>

                      <?php }
                    }else{echo "-";}?></td>
                          <td><?php if(!empty($value[2])){
                                foreach ($value[2] as $key => $val) {
                        ?>
                        <?php  echo !empty($val['club_detail']) ? ucfirst($val['club_detail']):'';
                           ?>

                      <?php }
                    }{echo "-";}?></td>
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
                                }elseif($value['status'] == "3"){
                                     $title = "Deleted";
                                }
                                if ($value['status']!='3') {
                            ?>
                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/user-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                          <?php }else{?>
                            <i class="" aria-hidden="true" title="<?php echo $title; ?>"> Deleted</i>
                          <?php }?>
                        </td>
                       
                        <td class="text-center">
                        <?php if(!empty($value[0][0]['usertype'])){
                         
                          ?>
                           <a href="<?php echo base_url(); ?>admin/user-view/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : '');?>/<?php echo(trim((!empty($value[0][0]['userid']))?trim($value[0][0]['userid']):''))?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>
                         <?php }?>
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
<script>
    $(".usersLi").addClass("active");
   
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
        startDate: nowDate
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#fromdate').datepicker('setEndDate', maxDate);
    });

</script>
</body>
</html>