<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->

<div class="content-wrapper">
   <section class="content-header">
       <div class="col-md-8 no-left-pad">
      <h1 style="margin:0px;">View User Invitation - <span class="invtn-usrnm"><?php  echo !empty($invitationViewList[0]['name']) ? ucfirst($invitationViewList[0]['name']) : '';?></span></h1>
      </div>      
    <div class="col-md-4 form-group no-right-pad">
        <div class="col-md-9">  
             <form name="frmSearch" id="frmSearch" action="<?php echo base_url(); ?>admin/sub-user" method="GET" autocomplete="off">
                        
                <input type="text" name="search_term" id="search_term" class="form-control" value="<?php echo!empty($this->input->get('search_term')) ? $this->input->get('search_term') : ''; ?>" placeholder="Search" style="width: 70%; display: inline-block;margin-left: 43px;">
                <input type="hidden" name="id" value="<?php  echo !empty($invitationViewList[0]['fk_uid']) ? ucfirst($invitationViewList[0]['fk_uid']) : '';?>">
               
                <button type="submit" class="btn btn-primary" title="Search" onclick="javascript: form.action='<?php echo base_url('admin/view-user-invitation');?>';" style="position: absolute;top: 0;right: 0;margin-right: 15px;height: 34px;"><i class="fa fa-search"></i></button>


            </form>
        </div>  
         <div class="col-md-3">
            <a href="<?php echo base_url(); ?>admin/user-invitation-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
         </div>
    </div>      
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">

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
       
                  <input type="hidden" name="id" value="<?php  echo !empty($invitationViewList[0]['fk_uid']) ? ucfirst($invitationViewList[0]['fk_uid']) : '';?>">
                  <div class="col-md-2 form-group">
                     <label>Status</label>
                     <select class="form-control" name="status">
                        <option value="">Select Status</option>
                        <option value="pending"<?php echo( (!empty($status) && $status=="pending")?'selected' : '') ?>>Pending</option>
                        <option value="registered"<?php echo( (!empty($status) && $status=='registered')?'selected' : '') ?>>Registered</option>
                     </select>
                  </div> 
                  <div class="col-md-2 form-group">
                     
                     <!-- <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/view-user-invitation');?>';"><i class="fa fa-filter"></i>Filter</button> -->
                     <button type="submit" class="btn btn-primary filter-btn"  onclick="javascript: form.action='<?php echo base_url('admin/view-user-invitation');?>';"><i class="fa fa-filter"></i>  Filter</button>
                  </div>
                 <!--  <div class="col-md-2 form-group">
                    <?php if(!empty($reviewDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/review-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div> -->
               </div>
            </div>
         </form>
         </div>
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="14%">Invitation Date</th>
                        <th width="56%">Invitee Name</th>
                        <th width="12%">Invitee Mobile No.</th>
                        <th width="10%">Status</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if(!empty($invitationViewList)){
                            $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                            foreach ($invitationViewList as $key => $value) {
                                                                       
                      ?>
                      <tr>
                          <td class="text-center"><?php echo $i; ?></td>
                          <td><?php  echo !empty($value['createdDate']) ?date('d-m-Y',strtotime($value['createdDate'])) : '-';?></td>
                          <td><?php  echo !empty($value['invitee_name']) ? ucfirst($value['invitee_name']) : '-';?></td>
                          <td><?php  echo !empty($value['mobile']) ? $value['mobile'] : '-';?></td>
                          <td><?php  echo !empty($value['reg_status']) ? ucfirst($value['reg_status']) : '';?></td>
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