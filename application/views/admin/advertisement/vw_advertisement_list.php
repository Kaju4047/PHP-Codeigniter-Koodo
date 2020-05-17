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
(in_array('advertisement', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Advertisement List 
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/add-advertisement"><button type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Advertisement</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
        <form id="filter"  method='get'   enctype="multipart/form-data"> 
         <div class="box box-primary no-height">
          <div class="box-body no-height" style="margin-bottom: 10px;">
             <div class="row">
                <div class="col-md-2 form-group">
                    <label>From Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="fromdatefilter" name="fromdatefilter" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label>To Date </label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="todatefilter" name="todatefilter" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label>Advertisement Place</label>
                    <select class="form-control" name="placefilter">
                      <option value=''>Select Advertisement</option>
                      <option value="1"<?php echo( (!empty($placefilter) && $placefilter=='1')?'selected' : '') ?>>Section 1</option>
                      <option value="2"<?php echo ((!empty($placefilter)&& $placefilter== '2')?'selected':'');?>>Section 2</option>  
                      <option value="3"<?php echo ((!empty($placefilter)&& $placefilter== '3')?'selected':'');?>>Section 3</option> 
                      <option value="4"<?php echo ((!empty($placefilter)&& $placefilter== '4')?'selected':'');?>>Sport Book</option>   
                      <option value="5"<?php echo ((!empty($placefilter)&& $placefilter== '5')?'selected':'');?>>Listing</option>   
                     
                    </select>
                   
                </div>
                <div class="col-md-2 form-group">
                    <label>City</label>
                    <select class="form-control" name="cityfilter">
                      <option value=''>Select City</option>
                      <?php if(!empty($cityDetails)){
                              foreach ($cityDetails as $key => $value) {
                      ?>
                      <option value="<?php echo $value['pk_id'];?>"<?php echo !empty($cityfilter) && $cityfilter == $value['pk_id'] ?'selected':''; ?>>
                        <?php echo !empty($value['city_name'])? $value['city_name']:'selected'  ?>
                      
                          
                        </option>
                     <?php }}?>
                    </select>
                </div>
               
                 <div class="col-md-2 form-group">
                     <button type="submit" class="btn btn-primary filter-btn"  onclick="javascript: form.action='<?php echo base_url('admin/filter-adv');?>';"><i class="fa fa-filter"></i>  Filter</button>
                   
                  </div>
                   <div class="col-md-2 form-group">
                    <?php if(!empty($advDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/advertisement-export-to-excel');?>';" >Export to Excel</button>
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
                        <th width="8%">Sr. No.</th>
                        <th width="20%">Advertisement Name</th>
                        <th width="10%">Advertisement Place</th>
                        <th width="9%">From</th>
                        <th width="9%">To</th>
                        <th width="25%">City</th>
                        <th width="11%">Price (Rs.)</th>
                        <th width="2%">Status</th>
                        <th width="6%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  
                    <?php if(!empty($advDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                        foreach ($advDetails as $key => $value) {
                    ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php echo ucfirst($value['advname'])?></td>
                        <td><?php if($value['place'] == 1 ){echo "Section 1";}elseif($value['place'] == 2 ){echo "Section 2";}elseif($value['place'] == 3 ){echo "Section 3";}elseif($value['place'] == 4 ){echo "Sport Book";}elseif($value['place'] == 5 ){echo "Listing";} ?></td>
                        <td><?php echo date("d-m-Y" ,strtotime($value['fromdate']));?></td>
                        <td><?php echo date("d-m-Y" ,strtotime($value['todate']));?></td>
                        <td><?php echo ucfirst($value['city_name'])?></td>
                        <td><?php echo $value['price']?></td>
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/adv-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                          <!--  <a href="#"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a> -->

                              <a href="<?php echo base_url(); ?>admin/add-advertisement/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>

                          <a href="<?php echo base_url(); ?>admin/delete-adv/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>" onClick="return confirm('Are you sure you want to delete record?')"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                        </td>
                     </tr>
                   <?php $i++; }} ?>
                  
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
    $(".advLi").addClass("active");
   // $("#example").DataTable();

    $('#fromdatefilter').datepicker(
    { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
    });

    $('#todatefilter').datepicker(
    { 
        format: "dd-mm-yyyy",   
        autoclose:true,     
        todayHighlight: true  
    });
    var nowDate = new Date(); // alert(nowDate);

    $('#fromdatefilter').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: nowDate
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#todatefilter').datepicker('setStartDate', minDate);
    });


      $('#todatefilter').datepicker({
          format: "dd-mm-yyyy",
          autoclose: true,
          startDate: nowDate}).on('changeDate', function (selected) {
          var maxDate = new Date(selected.date.valueOf());
          $('#fromdatefilter').datepicker('setEndDate', maxDate);
      });
</script>
</body>
</html>