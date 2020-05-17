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
(in_array('buy_subscription', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Buy Subscription</h1>
   </section>
   <!-- Main content -->

   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">
          <div class="box-body no-height mg-bot-10">
             <form id="filter"  method='get'  enctype="multipart/form-data"> 
             <div class="row"> 
             <input type="hidden" name="planid" id="planid" value="<?php echo (!empty($plan) ? $plan: ''); ?>">   
                <div class="col-md-2 form-group no-right-pad">
                    <label>Subscription Plan</label>
                    <select class="form-control" id="filterplan" name="plan">
                      <option  value="">Select Subscription Plan</option>
                      <option value="contact-plan"<?php echo( (!empty($plan) && $plan=="contact-plan")?'selected' : '') ?>>Contact Detail Plan</option>
                      <option value="listing-plan"<?php echo( (!empty($plan) && $plan=="listing-plan")?'selected' : '') ?>>Listing Plan</option>
                    </select>
                </div>


                <div class="col-md-2 form-group no-right-pad">
                    <label>Category</label>
                    <select class="form-control" id="filtercategory" name="category" style="color:#555;">
                   <option value="">Select Category</option>
                      <option value="Platinum"<?php if(!empty($category) && $category=="Platinum"){ echo "selected";} ?>>Platinum</option>
                      <option value="Gold" <?php if(!empty($category) && $category=="Gold"){ echo "selected";} ?> >Gold</option>
                      <option value="Career"<?php if(!empty($category) && $category=="Career"){ echo "selected";} ?>>Career</option>
                      <option value="Coach"<?php if(!empty($category) && $category=="Coach"){ echo "selected";} ?>>Coach</option>
                    </select>
                </div>
                <div class="col-md-2 form-group no-right-pad">
                    <label>Listing Type</label>
                   
                    <select class="form-control" name="listtype">
                     <option value="">Select Listing Type</option>
                     <option value="Players"<?php echo ((!empty($listtype) && $listtype == 'Players') ? 'selected' : ''); ?>>Players</option>
                     <option value="Pro-players"<?php echo ((!empty($listtype) && $listtype == 'Pro-players') ? 'selected' : ''); ?>>Pro-players</option>
                     
                      <option value="Coach"<?php echo ((!empty($listtype) && $listtype == 'Coach') ? 'selected' : ''); ?>>Coach</option>
                     <option value="Career"<?php echo ((!empty($listtype) && $listtype == 'Career') ? 'selected' : ''); ?>>Career</option>

                     <option value="Tournaments"<?php echo ((!empty($listtype) && $listtype=='Tournaments') ? 'selected' : ''); ?>>Tournaments</option>
                     <option value="Physio Therapy"<?php echo ((!empty($listtype) && $listtype == 'Physio Therapy') ? 'selected' : ''); ?>>Physiotherapy</option>
                     <option value="Orthopedic"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Orthopedic') ? 'selected' : ''); ?>>Orthopedic</option>
                   <!--   <option value="Products"<?php echo ((!empty($listtype) && $listtype == 'Products') ? 'selected' : ''); ?>>Products</option> -->
                     <option value="Dietitian"<?php echo ((!empty($listtype) && $listtype == 'Dietitian') ? 'selected' : ''); ?>>Dietitian</option>
                     <option value="Dealers"<?php echo ((!empty($listtype) && $listtype == 'Dealers') ? 'selected' : ''); ?>>Sport Dealers</option>
                     <option value="Treatment"<?php echo ((!empty($listtype) && $listtype == 'Treatment') ? 'selected' : ''); ?>>Treatment and Spa</option>
                      <option value="Coaching Academy Listing"<?php echo ((!empty($listtype) && $listtype == 'Coaching Academy Listing') ? 'selected' : ''); ?>>Coaching Academy Listing</option>
                     <option value="By Sell Used"<?php echo ((!empty($listtype) && $listtype == 'By Sell Used') ? 'selected' : ''); ?>>Buy Sell Used</option>
                  </select>                
                </div>

                <div class="col-md-2 form-group no-right-pad">
                    <label>From Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo (!empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : ''); ?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-2 form-group no-right-pad">
                    <label>To Date </label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo (!empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : ''); ?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>
               

                 
            </div>
            <div class="row">
                <div class="col-md-2 form-group">

                  <label>City</label>
                  <select class="form-control" name="city">
                     <option value="">Select City</option>
                     <?php if (!empty($cityDetails)) {
                        foreach ($cityDetails as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($city) && $city==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['city_name']) ? $value['city_name'] : '';?></option>
                     <?php }}?>
                  </select>
               </div> 
                 <div class="col-md-2 form-group">
                  <label>Sport</label>
                  <select class="form-control" name="type">
                     <option value="">Select Sport</option>
                     <?php if (!empty($sportDetails)) {
                        foreach ($sportDetails as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['sportname']) ? $value['sportname'] : '';?></option>
                     <?php }}?>
                  </select>
               </div>
                
             <!--     <div class="col-md-2 form-group">

                  <label>Refered By</label>
                     <select class="form-control" name="refered_by">
                     <option value="">Select refered by</option>
                     <?php if (!empty($users)) {
                        foreach ($users as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($refered_by) && $refered_by==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['name']) ? $value['name'] : '';?></option>
                     <?php }}?>
                  </select>
                 
               </div>  -->
                <div class="col-md-2 form-group">
                       <!-- <button type="submit" class="btn btn-primary filter-btn fa fa-filter" onclick="javascript: form.action='<?php echo base_url('admin/filter-buysub');?>';">   Filter       </button> -->
                       <button type="submit" class="btn btn-primary filter-btn " onclick="javascript: form.action='<?php echo base_url('admin/filter-buysub');?>';"><i class="fa fa-filter"></i>   Filter       </button>
                </div>
                  <div class="col-md-2 form-group">
                   
              <?php if(!empty($buySubDetails)){  ?> 
                      <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/buy-sub-export-to-excel');?>';">Export to Excel</button>
                                    
                  <?php }?>
                   
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
                        <th width="5%">Sub. Id</th>
                        <th width="10%">Subscription Plan</th>
                        <th width="12%">Category</th>
                        <th width="10%">Listing Type</th>
                        <th width="10%">Cost(Rs.)</th>
                        <th width="8%">Start Date</th>
                        <th width="10%">Users Type</th>

                        <th width="10%">City</th>
                        <th width="10%">Sport</th>
                       <!--  <th width="10%">Refered By</th> -->
                        <th style="text-align: center !important;" width="2%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php 
                      if (!empty($buySubDetails)) {
                        foreach ($buySubDetails as $key => $value) {
                         
                      ?>
                     <tr>
                        <td class="text-center"><?php echo $key+1; ?></td>
                        <td><?= !empty($value['sub_id']) ? $value['sub_id'] : '' ?></td>
                        <td><?= !empty($value['plan']) ? $value['plan'] : '' ?></td>
               <!--          <td><?php if ($value['plan']=='listing-plan') {
                          echo "Listing Plan";
                        }elseif ($value['plan']=='contact-plan') {
                          echo "Contact Detail Plan";
                        } ?></td> -->
                        <td><?= !empty($value['category']) ? $value['category'] : '' ?></td>
                        <td><?= !empty($value['listtype']) ? $value['listtype'] : '-' ?></td>
                        <td><?= !empty($value['cost']) ? $value['cost'] : '' ?></td>
                      
                        <td><?= !empty($value['createdDate']) ? date('d-m-Y',strtotime($value['createdDate']))  : '' ?></td>
                        <td>
                        <?php if(!empty($value[0])){
                                foreach ($value[0] as $key => $val) {
                        ?>
                        <?php  echo !empty($val['usertype']) ? ucfirst($val['usertype']) : '';
                           ?>

                      <?php }
                    }?>
                      </td>
                       <!--  <td><?= !empty($value['usertype']) ? $value['usertype'] : '' ?></td> -->
                       <td><?= !empty($value['city_name']) ? $value['city_name']  : '' ?></td>
                        
                          <td><?= !empty($value['sportname']) ? $value['sportname']  : '' ?></td>
                     
                        <!--   <td><?= !empty($value['name']) ? ucfirst( $value['name']) : '-' ?></td> -->
                        
                        <td class="text-center">
                             <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_viewData(<?php echo $value['pk_id']; ?>);"><i class="fa fa-eye"></i></button>
                        </td>
                     </tr>
                   <?php }}?>
             
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

<!-- Modal -->
<div class="modal fade" id="viewreveiwsModal" role="dialog">
   <div class="modal-dialog" style="min-width: 650px;">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
          <div class="row">
            <div class="col-md-8">
            <div class="row">
              <div class="col-xs-3 no-pad text-center">

                <img src="<?php echo base_url()?>AdminMedia/images/avatar5.png" id="user_image" width="70%" style="border-radius: 100%;">

              </div>
              <div class="col-xs-9 no-right-pad"><h4 class="modal-title" id="username"></h4>
                <div><span id="useremail"></span></div>
                <div><span id="usermob"></span></div>
            </div> </div>
          </div>
            <div class="col-md-4">
              <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
              <h4 class="modal-title">Subscription Date</h4>
              <span id="subdate"></span>

            </div>
          </div>           
              </div>
         <div class="modal-body">
          <div class="col-md-4 no-pad">           
               <label>Subscription ID</label>
               <h2 class="view-cnt" id="sub_id"></h2>
            </div>
            <div class="col-md-4 no-pad">
               <label>Catagory</label>
               <h2 class="view-cnt" id="category"></h2>
            </div>
    
            <div class="col-md-4 no-pad">
               <label>Listing Plan</label>
               <h2 class="view-cnt" id="listPlan"></h2>
            </div>

            <div class="col-md-4 no-pad">
               <label>Date</label>
               <h2 class="view-cnt" id="date"></h2>
            </div>

            <div class="col-md-4 no-pad">
               <label>Expiry Date</label>
               <h2 class="view-cnt" id="expdate"></h2>
            </div>

            <div class="col-md-4 no-pad">
               <label>Price (INR)</label>
               <h2 class="view-cnt" id="price"><i class="fa fa-inr"></i></h2>
            </div>

            <div class="col-md-12 no-pad">
               <label>Description</label>
               <h2 class="view-cnt" id="desc"></h2>
            </div>
         </div>
         <div class="modal-footer">
         </div>
      </div>
   </div>
</div>
<!----Modal End---->

<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
    $(".buysubscLi").addClass("active");
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

    function get_viewData(id) {
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";
                $.ajax({
                    type: "get",
                    data: {id: id},
                    url: base_url + "admin/subscription/Cn_subscription/viewBuy",
                    dataType: 'json',
                                       
                    success: function (data)
                    {
                        var pobill_date = moment(data.createdDate, "YYYY-MM-DD").format("DD-MM-YYYY");              // alert(data.plan);
                        var expdate = moment(data.expDate, "YYYY-MM-DD").format("DD-MM-YYYY"); 

                        $("#viewreveiwsModal").modal('show');
                        $("#username").text(data.name);
                        $("#useremail").text(data.email);
                        $("#usermob").text(data.mob);
                        $("#subdate").text(pobill_date);
                        $("#date").text(pobill_date);
                        $("#sub_id").text(data.sub_id);
                        $("#category").text(data.category);
                      
                        if (data.listtype=="") {
                            $("#listPlan").text("-");
                        }else{
                            $("#listPlan").text(data.listtype);
                        }
                        $("#expdate").text(expdate);
                        $("#price").text(data.cost);
                        d=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                        $("#desc").text(d);
                        if (data.img==NULL) {
                           $("#user_image").attr("src",'<?php echo base_url()?>uploads/users/'+data.img);
                        }else{
                           $("#user_image").attr("src",'<?php echo base_url()?>AdminMedia/images/avatar5.png');
                        }
                    }
                });
        } 
    }
    // style="background-image: url('http://m-staging.in/koodo/AdminMedia/images/avatar5.png')"
      
    $("#filterplan").change(function (){
        var id = $("#filterplan option:selected").val();
        if (id=='listing-plan') {
            var content = '';
                content +='<option value="">Select Category</option>';
                content += '<option value="Platinum">Platinum</option>';
                content += '<option value="Gold">Gold</option>';
             
        $("#filtercategory").html(content);

         }else if(id=='contact-plan'){    
         var content1 = '';   
             content1 += '<option value="">Select Category</option>';
             content1 += '<option value="Coach">Coach</option>';
             content1 += '<option value="Career">Career</option>';
              $( "#filtercategory" ).html(content1);
         }
    });
   

    var id1 = $("#planid").val();
    if (id1=='listing-plan') {
        var content = '';
            content +='<option value="">Select Category</option>';
            content += '<option value="Platinum">Platinum</option>';
            content += '<option value="Gold">Gold</option>';
             
            $("#filtercategory").html(content);

    }else if(id1=='contact-plan'){    
        var content1 = '';   
            content1 += '<option value="">Select Category</option>';
            content1 += '<option value="Coach">Coach</option>';
            content1 += '<option value="Career">Career</option>';
            $( "#filtercategory" ).html(content1);
    }
    var catid = "<?php echo  !empty($category) ? $category : ''; ?>";
    if(catid!=""){
        $("#filtercategory option[value="+catid+"]").attr('selected', 'selected');
    }    
</script>
</body>
</html>