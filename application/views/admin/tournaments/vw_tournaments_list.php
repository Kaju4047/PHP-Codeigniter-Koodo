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
(in_array('tornaments', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Tournaments List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
         <div class="box-body no-height" style="margin-bottom: 10px;">

           <form id="filter"  method='get'  enctype="multipart/form-data"> 
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
                  <label>Sport Type</label>
                  <select class="form-control" name="type">
                     <option value="">Select Type</option>
                     <?php if (!empty($sportDetails)) {
                        foreach ($sportDetails as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['sportname']) ? $value['sportname'] : '';?></option>
                     <?php }}?>
                  </select> 
               </div>
               <div class="col-md-2 form-group">

                  <label>Venue</label>
                    <input type="text" id="venue" name="venue" class="form-control" value="<?php echo !empty($venue) ? ($venue): '';?>" placeholder="" autocomplete="off">
                <!--   <select class="form-control" name="city">
                     <option value="">Select City</option>
                     <?php if (!empty($cityDetails)) {
                        foreach ($cityDetails as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($city) && $city==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['city_name']) ? $value['city_name'] : '';?></option>
                     <?php }}?>
                  </select> -->
               </div>

               <div class="col-md-2 form-group">
                  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filterTournament');?>';"><i class="fa fa-filter"></i> Filter</button>
               </div>
                <div class="col-md-2 form-group">
                    <?php if(!empty($tounamentsDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/tournaments-export-to-excel');?>';" >Export to Excel</button>
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
                        <th width="5%">Sr. No.</th>
                        <th width="10%">Tournament Name</th>
                        <th width="8%">Created By</th>
                        <th width="6%">Contact Number</th>
                        <th width="6%">Email ID</th>
                        <th width="6%">Website</th>
                        <th width="10%">Sport Type</th>
                        <th width="15%">Start/End Date</th>
                        <th width="8%">Time</th>
                        <th width="8%">Max. entries</th>
                        <th width="10%">Venue</th>                        
                        <th width="8%">Form</th>
                        <!-- <th width="7%">Tournament Draws</th> -->
                      <!--   <th width="12%">Entry Form</th>
                        <th width="12%">Entry Form</th>
                        <th width="12%">Entry Form</th> -->
                       <!--  <th width="12%">Entry Form</th> -->
                        <th width="1%">Status</th>
                        <th style="text-align: center !important;" width="1%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (!empty($tounamentsDetails)) {
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                             foreach ($tounamentsDetails as $key => $value) {
                               // print_r($value);
                               // die();
                               ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php  echo !empty($value['tornamentName']) ? ucfirst($value['tornamentName']) : '';?></td>
                        <td><?php  echo !empty($value['created_by']) ? ucwords($value['created_by']) : '';?></td>
                        <td><?php  echo !empty($value['mob']) ? $value['mob'] : '';?></td>
                        <td><?php  echo !empty($value['email']) ? $value['email'] : '';?></td>
                        <td><?php  echo !empty($value['website']) ? $value['website'] : '';?></td>
                        <td><?php  echo !empty($value['sportname']) ? ucfirst($value['sportname']) : '';?></td>
                        <!-- <td><?php  echo !empty($value['start_date']) ? date('d-m-Y',strtotime($value['start_date'])).' to '. date('d-m-Y',strtotime($value['end_date'])) : '';?></td> -->
                        <td><?php  echo !empty($value['start_date']) ? date('d-m-Y',strtotime($value['start_date'])) : '-';?> to <?php  echo !empty($value['end_date']) ? date('d-m-Y',strtotime($value['end_date'])) : '-';?></td>
                        <td><?php  echo !empty($value['time']) ? date('H:i A',strtotime($value['time'])) : '';?></td>
                        <td><?php  echo !empty($value['entery_number']) ? ucfirst($value['entery_number']) : '';?></td>
                       <td><?php  echo !empty($value['address']) ? ucfirst($value['address']) : '';?></td>
                        <td class="text-center">
                            <?php $imgdata = !empty($value['entry_form']) ?  base_url('uploads/tournaments/entryform/' . $value['entry_form']) : 'AdminMedia/images/default.png'; ?>
                            <a href="<?php echo $imgdata; ?>" download >

                           <button type="button" class="btn btn-primary btn-entry btn-xs" title="Download Entry Form">Entry <i class="fa fa-download"></i></button></a><br>
                            <?php $imgdata2 = !empty($value['draws_doc']) ?  base_url('uploads/tournaments/entryform/' . $value['draws_doc']) : 'AdminMedia/images/default.png'; ?>
                            <a href="<?php echo $imgdata2; ?>" download>
                           <button type="button" class="btn btn-primary btn-xs" title="Download Entry Form">Draws<i class="fa fa-download"></i></button></a>

                         </td>  
                     

                            <!-- <td class="text-center">
                            <?php $imgdata = !empty($value['draws_doc']) ?  base_url('uploads/tournaments/entryform/' . $value['draws_doc']) : 'AdminMedia/images/default.png'; ?>
                            <a href="<?php echo $imgdata; ?>" download>
                           <button type="button" class="btn btn-primary btn-xs" title="Download Entry Form"><i class="fa fa-download"></i></button></a></td> -->
                    
                  
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/tournament-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>/<?php echo (!empty($value['user_id']) ? $value['user_id'] : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                           <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_dataView(<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>)"><i class="fa fa-eye"></i></button>
                        </td> 
                        <!-- <td class="text-center">
                           <a href="<?php echo base_url(); ?>admin/view-tournaments/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>
                        </td> -->
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

<!-- Modal -->
  <div class="modal fade" id="viewprodModal" role="dialog" TABINDEX=-1>
    <div class="modal-dialog" style="min-width: 500px;">
    
      <!-- Payment Modal start-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Tournaments Details</h4>
        </div>
        <div class="modal-body">
         <div class="col-md-9 no-pad">
          
         <div class="col-md-4">
            <label>Entry Fees</label>
            <h2 class="view-cnt" id="entryfees"><i class="fa fa-rupee"></i></h2>
         </div>
         <div class="col-md-4">
            <label>Price Money</label>
            <h2 class="view-cnt" id="pricemoney"><i class="fa fa-rupee"></i></h2>
         </div>
          <div class="col-md-8">
            <label>Description</label>
            <h2 class="view-cnt" id="description"></h2>
         </div>
        </div>
         <div class="col-md-3 no-pad">
            <img src="<?php echo base_url(); ?>"  id="tournament_image" width="100%">
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
    $(".tournLi").addClass("active");
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


     function get_dataView(id){
      // alert(id);
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";

              $.ajax({
                  type: "get",
                  data: {id: id},
                  url: base_url + "admin/tournaments/Cn_tournaments/view",
                  dataType: 'json',
                                       
                   success: function (data)
                   {
                    // alert(JSON.stringify(data));
                    
                        $("#viewprodModal").modal('show');
                
                         $("#entryfees").text(data.entery_fees);
                         $("#pricemoney").text(data.price_money);
                         description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                         $("#description").text(description);
                         if (data.tornamentImage== '') {
                             $("#tournament_image").attr("src",'<?php echo base_url()?>AdminMedia/images/default.png');
                         }else{
                            $("#tournament_image").attr("src",'<?php echo base_url()?>uploads/tournaments/images/'+data.tornamentImage);
                         }
                                                
                    }
               });
        } 
    }
</script>
</body>
</html>