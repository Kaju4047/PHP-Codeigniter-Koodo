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
(in_array('sport_facility', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Sports Facility List 
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/add-sports-clubs"><button type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Sport Facility</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->

   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary">
            <div class="box-body">
              <div class="row">
              <form id="filter"  method='get'  enctype="multipart/form-data"> 
                  <div class="col-md-2 form-group">
                  <label>Sport Facility Name<span style="color:red">*</span></label>
                  <input type="text" name="filtername" class="form-control" value="<?php echo (!empty($filtername) ? $filtername : ''); ?>" autocomplete="off">
               </div>
                 <div class="col-md-2 form-group">
                  <label>Sport<span style="color:red">*</span></label>
                  <input type="text" name="filtersport" class="form-control" value="<?php echo (!empty($filtersport) ? $filtersport : ''); ?>" autocomplete="off">
               </div>
                <div class="col-md-2 form-group">
                  <label>Address<span style="color:red">*</span></label>
                  <input type="text" name="filteraddress" class="form-control"  value="<?php echo (!empty($filteraddress) ? $filteraddress : ''); ?>" autocomplete="off">
               </div>

                 <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/sports-clubs-list');?>';"><i class="fa fa-filter" ></i> Filter</button>
                  </div>

                  <div class="col-md-2 form-group">
                     <?php if(!empty($sportClubList)){  ?> 
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/sport-clubs-export-to-excel');?>';">
                     </i>Export to Excel</button>
                   <?php }?>
                  </div>
                </form>
            </div>
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="10%">Sport Facility Name</th>
                        <th width="10%">Address</th>
                        <th width="10%">Email</th>
                        <th width="10%">Mobile</th>
                        <th width="10%">Website</th>
                        <th width="10%">Sport</th>
                        <!-- <th width="10%">City</th> -->
                        <th width="2%">Status</th>
                        <th width="9%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php  if (!empty($sportClubList)) {
                    $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;                             
                            $i = ($page_no * 10) - 9;
                        foreach ($sportClubList as $key => $value) {
                   ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                       
                        <!-- <td>18-6-2019 9:30 AM</td> -->
                   <!--       <td><?php echo $newDate?></td> -->
                       
                        <td><?php echo !empty($value['name'])? ucfirst($value['name']):''?></td>
                        <td><?php echo !empty($value['address'])? ucfirst($value['address']):''?></td>
                        <td><?php echo !empty($value['email'])? ucfirst($value['email']):''?></td>
                        <td><?php echo !empty($value['mobile'])? ucfirst($value['mobile']):''?></td>
                        <td><?php echo !empty($value['website'])? ucfirst($value['website']):'-'?></td>
                        <td><?php echo !empty($value['sportname'])? ucfirst($value['sportname']):''?></td>
           <!--              <td><?php echo !empty($value['city_name'])? ucfirst($value['city_name']):''?></td> -->
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/sports-club-status-change/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">

                        <button type="button" class="btn btn-primary btn-xs" title="View"   onclick="get_viewData(<?php echo $value['pk_id']; ?>);"><i class="fa fa-eye"></i></button>
                        
                          <a href="<?php echo base_url(); ?>admin/add-sports-clubs/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>

                          <a href="<?php echo base_url(); ?>admin/delete-sport-club/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>" onClick="return confirm('Are you sure you want to delete record?')"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>

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
<!-- Modal -->

<script>
    $(".sportclubLi").addClass("active");
   
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
        startDate: nowDate}).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#fromdate').datepicker('setEndDate', maxDate);
    });
</script>
<div class="modal fade" id="viewvideosModal" role="dialog" TABINDEX=-1>
   <div class="modal-dialog">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
            <h4 class="modal-title">Sport Clubs Details</h4>
         </div>
         <div class="modal-body">
          
            <div class="col-md-12"> 
            <div class="col-md-3 no-pad">
                 <img src="<?php echo base_url(); ?>"  id="club_image" width="100%">
            </div>
            <div class="col-md-9"> 
               <label>Description</label>
               <h2 class="view-cnt" id="descriptionView">
               </h2>
          
          </div>
          <div class="col-md-9"> 
               <label>Sport</label>
               <h2 class="view-cnt" id="view_sports">
               </h2>
          
          </div>
          </div>

      </div>
      <div class="modal-footer">
      </div>
   </div>
</div>
</div>
<!--Modal End-->
<script>
    // $(".sportvidLi").addClass("active");
    function get_viewData(id) {
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";
            $.ajax({
                type: "get",
                data: {id: id},
                url: base_url + "admin/sports-clubs/Cn_sports_clubs/viewClubs",
                dataType: 'json',
                success: function (data){
                    $("#viewvideosModal").modal('show');
                    description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                    $("#descriptionView").html(description);
                    sports=  (data.sport).substr(0,1).toUpperCase()+(data.sport).substr(1);
                    $("#view_sports").html(sports);
                    if (data.image== '') {
                        $("#club_image").attr("src",'<?php echo base_url()?>AdminMedia/images/default.png');
                    }else{
                        $("#club_image").attr("src",'<?php echo base_url()?>uploads/clubs/'+data.image);
                    }
                }
             });
        } else {
            $("#descriptionView").html("");

        }
    }
</script>
</body>
</html>