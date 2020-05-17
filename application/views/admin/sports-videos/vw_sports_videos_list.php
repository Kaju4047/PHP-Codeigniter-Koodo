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
(in_array('sport_video', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Sports Videos List 
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/add-sports-videos"><button type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Video</button></a>
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
                    <label>From Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>
                 <div class="col-md-2 form-group">
                    <label>To Date</label>
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
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($sport) && $sport==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['sportname']) ? $value['sportname'] : '';?></option>
                     <?php }}?>
                  </select>
               </div>

              

                 <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/sports-videos-list');?>';"><i class="fa fa-filter" ></i> Filter</button>
                  </div>

                  <div class="col-md-2 form-group">
                     <?php if(!empty($sportList)){  

                                  ?> 
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/sport-video-export-to-excel');?>';">
                     </i>Export to Excel</button>
                   <?php }?>
                  </div>
                </form>
            </div>
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="15%">Date Time</th>
                        <th width="10%">Sport Type</th>
                        <th width="10%">Skill Level</th>
                        <th width="45%">Video Heading</th>
                        <th width="2%">Status</th>
                        <th width="10%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php  if (!empty($sportList)) {
                    $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;

                        foreach ($sportList as $key => $value) {
                           $newDate = date("d-m-Y g:i a", strtotime($value['createdDate']));        
                  ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                       
                        <!-- <td>18-6-2019 9:30 AM</td> -->
                         <td><?php echo $newDate?></td>
                        <td><?php echo ucfirst($value['sportname'])?></td>
                        <td><?php echo ucfirst($value['skill_level'])?></td>
                        <td><?php echo ucfirst($value['heading'])?></td>
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
                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/sports-videos-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">

                        <button type="button" class="btn btn-primary btn-xs" title="View"   onclick="get_viewData(<?php echo $value['pk_id']; ?>);"><i class="fa fa-eye"></i></button>

                        <!-- <button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal"  data-target="#viewvideosModal" onclick="get_viewData(<?php echo $value['pk_id']; ?>);"><i class="fa fa-eye"></i></button> -->

                          <!--  <a href="<?php echo base_url('admin/view-sport-videos/'.$value['pk_id'])?>"><button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#viewvideosModal"><i class="fa fa-eye"></i></button></a> --> 

                        
                          <a href="<?php echo base_url(); ?>admin/add-sports-videos/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>

                          <a href="<?php echo base_url(); ?>admin/delete-sports-videos/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>" onClick="return confirm('Are you sure you want to delete record?')"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>

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
<!-- Modal -->

<script>
// $(".usersLi").addClass("active"); 
   
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
            <h4 class="modal-title">Sport Video Details</h4>
         </div>
         <div class="modal-body">
           
               <div class="col-md-3">
                  <label>Type</label>
                  <h2 class="view-cnt" id="typeView"></h2>
               </div>
               <div class="col-md-9">
                  <label>Video Heading</label>
                  <h2 class="view-cnt" id="headingView"></h2>
               </div>
            
            <div class="col-md-12">
               <label>Description</label>
               <h2 class="view-cnt" id="descriptionView">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur</h2>
            </div>
              <div class="col-md-9">
                  <label>Youtube Video</label>
                  <a href="" id="urlView" target="_blank" class="view-cnt" ></a>
               </div>
      </div>
      <div class="modal-footer">
      </div>
   </div>
</div>
</div>
<!--Modal End-->
<script>
    $(".sportvidLi").addClass("active");
    function get_viewData(id) {
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";
            $.ajax({
                type: "get",
                data: {id: id},
                url: base_url + "admin/sports-videos/Cn_sports_videos/view",
                dataType: 'json',
                success: function (data){
                    $("#viewvideosModal").modal('show');
                    heading=  (data.heading).substr(0,1).toUpperCase()+(data.heading).substr(1);
                    $("#headingView").html(heading);
                    description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                    $("#descriptionView").html(description);
                    $("#urlView").html(data.url);
                    $("#urlView").attr('href',data.url);
                    $("#typeView").html(data.sportname);
                }
             });
        } else {
            $("#headingView").html("");
            $("#descriptionView").html("");
            $("#urlView").html("");
            $("#typeView").html("");
        }
    }
</script>
</body>
</html>