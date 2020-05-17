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
(in_array('transaction', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Transaction History List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <form id="filter"  method='get'  enctype="multipart/form-data"> 
         <div class="box box-primary no-height">
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
                      <input type="text" id="todate"  name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label>User</label>

                    <select  class="form-input form-control select2" name="userName">
                      <option value="">Select User</option>
                    <?php if (!empty($userDetails)) {
                          foreach ($userDetails as $key => $value) {
                         
                    ?>
                      <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id']  : '';?>"<?php echo( (!empty($userName) && $userName==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['name']) ? $value['name']  : '';?></option>
                     
                    <?php }}?>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label>Subscription Plan</label>
                     <select class="form-control" id="select-plan" name="plan">
                     <option value="" >Select Plan</option>
                   
                      <option value="1"<?php echo( (!empty($plan) && $plan==1)?'selected' : '') ?>>Platinum</option>
                     <option value="2"<?php echo( (!empty($plan) && $plan==2)?'selected' : '') ?>>Gold</option>
                  </select>
                </div>
               
                 <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-transacation');?>';"><i class="fa fa-filter"></i> Filter</button>
                  </div>
                  <div class="col-md-2 form-group">
                    <?php if(!empty($transactionDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/transaction-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div>
              </div>
            </div>
          </div>
        </form>

         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="7%">Sr. No.</th>
                        <th width="15%">Date Time</th>
                        <th width="10%">Transaction Amount (Rs.)</th>
                        <th width="11%">Transaction Id</th>
                        <th width="15%">Subscription Plan</th>
                        <th width="17%">User Name</th>
                        <th width="13%">Subscription Plan category</th>
                        <th width="12%">Mobile No.</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($transactionDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                          foreach ($transactionDetails as $key => $value) {
                    ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php  echo !empty($value['createdDate']) ? date('d-m-Y H:i a',strtotime($value['createdDate']))  : '';?></td>
                        <td><?php  echo !empty($value['tran_amount']) ? $value['tran_amount']  : '';?></td>
                        <td><?php  echo !empty($value['tran_id']) ? $value['tran_id']  : '';?></td>
                           <td><?php if ($value['tran_plan']==1) {
                          echo "Platinum";
                        }elseif ($value['tran_plan']==2) {
                          echo "Gold";
                        } ?></td>
                     
                      
                        <td><?php  echo !empty($value['name']) ?ucwords($value['name'])   : '';?></td>
                        <td><?php  echo !empty($value['sub_category']) ? ucfirst($value['sub_category'])  : '';?></td>
                        <td><?php  echo !empty($value['mob']) ? $value['mob']  : '';?></td>
                      
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
   $(".transhisLi").addClass("active");
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