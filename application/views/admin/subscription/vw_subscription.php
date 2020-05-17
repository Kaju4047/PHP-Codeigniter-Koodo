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
// print_r($privilige);exit();
?>
<?php
// print_r($privilige);die();
(in_array('subscription', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="col-md-4 no-pad">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Add Subscription </h1>
         <div class="box box-primary no-height">
            <div class="box-body no-height">
            <!-- <form id="add_subscription" action="" method='post'   enctype="multipart/form-data"> -->
            <form id="add_subscription" action="<?php echo base_url(); ?>admin/subscription-action" method='post'   enctype="multipart/form-data">
            <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
            <input type="hidden" name="pk_id" id="pk_id" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
            <input type="hidden" name="check_exist" id="check_exist" value="" class="form-control">
             
               <div class="row">
                <div class="col-md-12 form-group">
                  <label>Subscription Plan <span style="color: red">*</span> </label>

                   <input type="hidden" name="edit-plan" id="edit-plan" value="<?php echo (!empty($edit['plan']) ? $edit['plan'] : ''); ?>" class="form-control">

                  <select class="form-control" id="select-plan" name="plan" style="color: #555">
                     <option value="" >Select Subscription Plan </option>
                    <!--  <option value="listing-plan">Listing Plan</option>
                     <option value="contact-plan">Contact Detail Plan</option> -->
                      <option value="listing-plan"<?php echo ((!empty($edit['plan']) && $edit['plan'] == 'listing-plan') ? 'selected' : ''); ?>>Listing Plan</option>
                     <option value="contact-plan"<?php echo ((!empty($edit['plan']) && $edit['plan'] == 'contact-plan') ? 'selected' : ''); ?>>Contact Detail Plan</option>
                  </select>
               </div>
               <div class="col-md-6 form-group" id="catagory-listing">
                  <label>Catagory<span style="color: red">*</span> </label>
                  <select class="form-control" name="catlist" id="catlist" style="color: #555">
                     <option value="">Select Catagory</option>
                     <option value="Platinum"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Platinum') ? 'selected' : ''); ?>>Platinum</option>
                     <option value="Gold"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Gold') ? 'selected' : ''); ?>>Gold</option>
                  </select>
               </div>
               <div class="col-md-6 form-group" id="catagory-contact">
                  <label>Catagory<span style="color: red">*</span> </label>
                  <select class="form-control"  name="category" id="catagorycon" style="color: #555">
                     <option  value="">Select Catagory</option>
                     <option value="Coach"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Coach') ? 'selected' : ''); ?>>Coach</option>
                     <option value="Career"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Career') ? 'selected' : ''); ?>>Career</option>
                  </select>
               </div>
               <div class="col-md-6 form-group" id="list-type">
                  <label>Listing Type<span style="color: red">*</span> </label>
                  <select class="form-control" name="listtype" id="listtype" style="color: #555"  onchange="getListtype(this)";>
                     <option value="">Select Type</option>
                     <option value="Players"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Players') ? 'selected' : ''); ?>>Players</option>
                     <!-- <option value="Pro-players"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Pro-players') ? 'selected' : ''); ?>>Pro-players</option> -->
                     <option value="Coach"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Coach') ? 'selected' : ''); ?>>Coach</option>
                     <option value="Career"<?php echo ((!empty($edit['category']) && $edit['category'] == 'Career') ? 'selected' : ''); ?>>Career</option>
                     <option value="Tournaments"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Tournaments') ? 'selected' : ''); ?>>Tournaments</option>
                     <option value="Physio Therapy"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Physio Therapy') ? 'selected' : ''); ?>>Physiotherapy</option>
                     <option value="Orthopedic"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Orthopedic') ? 'selected' : ''); ?>>Orthopedic</option>
                     <option value="Dietitian"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Dietitian') ? 'selected' : ''); ?>>Dietitian</option>
                     <option value="Sport Dealers"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Sport Dealers') ? 'selected' : ''); ?>>Sport Dealers</option>
                     <option value="Treatment And Spa"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Treatment And Spa') ? 'selected' : ''); ?>>Treatment And Spa</option>
                    <!--  <option value="Coaches"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Coaches') ? 'selected' : ''); ?>>Coaches</option>
                     <option value="Career"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Career') ? 'selected' : ''); ?>>Career</option> -->
                     <option value="Coaching Academy Listing"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'Coaching Academy Listing') ? 'selected' : ''); ?>>Coaching Academy Listing</option>
                     <option value="By Sell Used"<?php echo ((!empty($edit['listtype']) && $edit['listtype'] == 'By Sell Used') ? 'selected' : ''); ?>>Buy Sell Used</option>
                  </select>
               </div>
               <div class="col-md-12 form-group" id="sport">                 
                  <label>Sport<span style="color: red">*</span> </label>

                  <select class="form-control" name="sport" id="sport_id" style="color: #555">
                     <option value="" >Select Sport</option>
                     <?php if(!empty($sportDetails)){
                         foreach ($sportDetails as $key => $value) {
                     ?>
                     <option value="<?= $value['pk_id'];?>"<?php echo ((!empty($edit['sport']) && $edit['sport'] == $value['pk_id']) ? 'selected' : ''); ?>><?= $value['sportname'];?></option>
                  
                   <?php }}?>
                  </select>              
               </div>
               <div class="col-md-12 form-group" id="city">                 
                  <label>City<span style="color: red">*</span> </label>
                  <select class="form-control" name="city" id="city_id" style="color: #555">
                     <option value="">Select City</option>
                     <?php if(!empty($cityDetails)){
                         foreach ($cityDetails as $key => $value) {
                     ?>
                     <option value="<?= $value['pk_id'];?>"<?php echo ((!empty($edit['city']) && $edit['city'] == $value['pk_id']) ? 'selected' : ''); ?>><?= $value['city_name'];?></option>
                  
                   <?php }}?>
                  </select>              
               </div> 
               <div class="col-md-12 form-group" id="duration">                 
                  <label>Duration<span style="color: red">*</span> </label>
                  <select class="form-control" name="duration" id="planmonths" style="color: #555">
                     <option value="">Select Duration</option>
                     <option value="1 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '1 Months') ? 'selected' : ''); ?>>1 Month</option>
                     <option value="2 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '2 Months') ? 'selected' : ''); ?>>2 Months</option>
                     <option value="3 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '3 Months') ? 'selected' : ''); ?>>3 Months</option>
                     <option value="4 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '4 Months') ? 'selected' : ''); ?>>4 Months</option>
                     <option value="5 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '5 Months') ? 'selected' : ''); ?>>5 Months</option>
                     <option value="6 Months"<?php echo ((!empty($edit['duration']) && $edit['duration'] == '6 Months') ? 'selected' : ''); ?>>6 Months</option>
                  </select>              
               </div>  
                <div class="col-md-6 form-group" id="price1">                 
                  <label>MRP Price <span style="color: red">*</span> </label>
                  <input type="text" name="mrp" id="mrp" class="form-control" value="<?php echo (!empty($edit['mrp']) ? $edit['mrp'] : ''); ?>" autocomplete="off" >  
               </div>  

               <div class="col-md-6 form-group" id="price2">                 
                  <label> Offer Price<span style="color: red">*</span> </label>
                  <input type="text" name="offer" id="offer" class="form-control" value="<?php echo (!empty($edit['offer']) ? $edit['offer'] : ''); ?>" autocomplete="off" >   
               </div>  
               <div class="col-md-12 form-group" id="desc">
                  <label>Description<span style="color: red">*</span> </label>
                  <textarea rows="3" name="desc" class="form-control" style="resize: none;" ><?php echo (!empty($edit['desc']) ? $edit['desc'] : ''); ?></textarea>


               </div> 
                  <div class="col-md-12 form-group" id="checkbox-view">
                  <label>View On Android </label> 
                  <input type="checkbox" name="view_on_android" id="view_on_android"  <?php echo ((!empty($edit['view_on_android']) && $edit['view_on_android'] == 'Yes') ? 'checked' : 'unchecked'); ?>   <?php echo ((!empty($edit['view_on_android']) && $edit['view_on_android'] == 'Yes') ? 'disabled' : ''); ?> >

               </div>
                                                            <!-- End form-group -->           
               <div class="clearfix"></div>
               <div class="col-md-12 form-group" id="subt">
                  <button type="submit" id="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                <!--   <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
               </div>
            </div>
               <!-- End form-group -->
            </form>  
            </div>
            <!-- End box-body -->
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="col-md-8 no-pad-right">
         <h1 style="font-size: 24px; margin: 0 0 15px 0;">Subscription List</h1>
         <div class="box box-primary" >
            <div class="box-body">
            <form id="filter"  method='get'   enctype="multipart/form-data"> 
             <div class="row">
                <div class="col-md-3 form-group">

                  <label>Plan</label>
                  <!-- <input type="text" name="sport_club" id="sport_club"> -->
                     <input type="text" id="plan" name="plan" class="form-control" value="<?php echo !empty($plan) ? ($plan): '';?>" placeholder="" autocomplete="off">
                 
               </div> 
                <div class="col-md-3 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/subscription');?>';" >Search</button>
                  </div>
                  <div class="col-md-3 form-group">
                    <?php if(!empty($subscriptionDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/subscription-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div>
              </div>
            </form>
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="10%">Sr. No.</th>
                        <th width="22%">Subscription Plan</th>
                        <th width="10%">Catagory</th>
                        <th width="15%">Duration</th>
                        <th width="10%">MRP</th>
                        <th width="10%">Offer</th>
                        <th width="5%">View on Android</th>
                        <th width="3%">Status</th>
                        <th width="25%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($subscriptionDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                           foreach ($subscriptionDetails as $key => $value) {
                            
                          ?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php if ($value['plan']=='listing-plan'){
                          echo "Listing Plan";
                        }elseif ($value['plan']=='contact-plan') {
                          echo "Contact Detail Plan";
                        } ?></td>
                        <td><?= !empty($value['category']) ? ucfirst($value['category']) :'' ?></td>
                        <td><?= !empty($value['duration']) ? $value['duration'] : '' ?></td>
                        <td><?= !empty($value['mrp']) ? $value['mrp'] : '' ?></td>
                        <td><?= !empty($value['offer']) ? $value['offer'] : '' ?></td>
                        <td><?= !empty($value['view_on_android']) ? ucfirst($value['view_on_android']) :'' ?></td>
                        <td class="text-center">
                              <?php
                              $status = ""; 
                              if ($value['status'] == "1") {
                                  $status = "2";
                                  $class = "fa fa-toggle-on tgle-on";
                                  $title = "Active";
                              } else if ($value['status'] == "2"){
                                  $status = "1";
                                  $class = "fa fa-toggle-on fa-rotate-180 tgle-off";
                                  $title = "Inactive";
                              }
                              ?>
                              <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/subscrip-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_viewData(<?php echo $value['pk_id']; ?>);"><i class="fa fa-eye"></i></button>

                            <a href="<?php echo base_url(); ?>admin/subscription/1?edit=<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>" ><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>
                       
                            <?php if ($value['view_on_android']=='No') {?>

                            <a onclick="return confirm('Do you really want to Delete this user ?')" href="<?php echo base_url(); ?>admin/delete-subscription/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                            <?php }elseif ($value['view_on_android']=='Yes') {?>

                               <a onclick="return confirm('This subscription plan showing on App. Do you really want to Delete this user ?')" href="<?php echo base_url(); ?>admin/delete-subscription/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
 
                           <? } ?>
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

<!-- Modal -->
  <div class="modal fade" id="contact-plan" role="dialog" TABINDEX=-1>
    <div class="modal-dialog" style="min-width: 600px">
    
      <!-- Payment Modal start-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Subscription Details</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-12 no-pad">
            <div class="col-md-4 no-pad">
            <label>Subscription Plan</label>
            <h2 class="view-cnt" id="contactPlan"></h2>
         </div>     
         <div class="col-md-4 no-pad">
            <label>Catagory</label>
            <h2 class="view-cnt" id="contactCategory"></h2>
         </div>
         <div class="col-md-4 no-pad">
            <label>Duration</label>
            <h2 class="view-cnt" id="contactDuration"></h2>
        </div>      
        </div>
         <div class="col-md-12 no-pad">

         <div class="col-md-4 no-pad">
            <label>MRP Price</label>
            <h2 class="view-cnt" id="contactMrp"><i class="fa fa-inr"></i> </h2>
         </div>       
         <div class="col-md-4 no-pad">
            <label>Offer Price</label>
            <h2 class="view-cnt" id="contactPrice"><i class="fa fa-inr"></i></h2>
         </div>
         <div class="col-md-12 no-pad">
            <label>Description</label>
            <h2 class="view-cnt" id="contactDesc"></h2>            
         </div>
        </div>
     </div>
        <div class="modal-footer">
         </div>
      </div>
      
    </div>
  </div>
<!----Modal End---->

<div class="modal fade" id="listing-plan" role="dialog" TABINDEX=-1>
    <div class="modal-dialog" style="min-width: 600px">
    
      <!-- Payment Modal start-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Subscription Details</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-12 no-pad">
            <div class="col-md-4 no-pad">
            <label>Subscription Plan</label>
            <h2 class="view-cnt" id="listingPlan"></h2>
         </div>     
         <div class="col-md-4 no-pad">
            <label>Catagory</label>
            <h2 class="view-cnt" id="listingCat"></h2>
         </div>
         <div class="col-md-4 no-pad">
            <label>Listing Type</label>
            <h2 class="view-cnt" id="listingType" ></h2>
         </div>
         <div class="col-md-4 no-pad">
            <label>Sport</label>
            <h2 class="view-cnt" id="listingSport"></h2>
         </div>
         <div class="col-md-4 no-pad">
            <label>City</label>
            <h2 class="view-cnt" id="listingCity"></h2>
         </div>
         <div class="col-md-4 no-pad">
            <label>Duration</label>
            <h2 class="view-cnt" id="listingDur"></h2>
         </div>     
        </div>
         <div class="col-md-12 no-pad">
         <div class="col-md-4 no-pad">
            <label>MRP Price</label>
            <h2 class="view-cnt" id="listingMrp"><i class="fa fa-inr"></i> </h2>
         </div>       
         <div class="col-md-4 no-pad">
            <label>Offer Price</label>
            <h2 class="view-cnt" id="listingOffer"><i class="fa fa-inr"></i> </h2>
         </div>
         <div class="col-md-12 no-pad">
            <label>Description</label>
            <h2 class="view-cnt" id="listingDes"></h2>
         </div>
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
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_subscription/subscription.js'); ?>"></script>
<script>
   $(".subscLi").addClass("active");

    // $("#example").DataTable();
</script>
<script type="">
  
    
 $('#offer').blur(function(){
        var offer = $('#offer').val();
        var mrp = $('#mrp').val();
        if (parseFloat(offer) > parseFloat(mrp)) {
          showErroMsg("Offer amount should not be more than MRP amount");
              $('#submit').prop('disabled',true);
        }else{
              $('#submit').prop('disabled',false);
              $('#add_subscription').submit();
        }
  });
    $(document).ready(function()
    {   
        $('#select-plan').show();
        $('#list-type').hide();
        $('#city').hide();       
        $('#duration').hide();
        $('#desc').hide();
        $('#checkbox-view').hide();
        $('#price-l').hide();
        $('#price1').hide();
        $('#price2').hide();
        $('#subt').hide();
        $('#sport').hide();
        $('#catagory-listing').hide();
        $('#catagory-contact').hide();
        
        $("#select-plan").change(function(){

            if($(this).val() == "listing-plan"){
                $('#list-type').show();
                $('#city').show();
                $('#price1').show();
                $('#price2').show();
                $('#duration').show();
                $('#sport').hide();
                $('#desc').show();
                $('#checkbox-view').show();
                $('#catagory-listing').show();
                $('#catagory-contact').hide();
                $('#price2').show();
                $('#subt').show();
            }
            if($(this).val() == "contact-plan"){
                $('#select-plan').show();
                $('#catagory-listing').hide();
                $('#catagory-contact').show();
                $('#list-type').hide();
                $('#city').hide();       
                $('#duration').show();
                $('#desc').show();
                $('#checkbox-view').show();
                $('#price1').show();
                $('#price2').show();
                $('#subt').show();
                $('#sport').hide();
            }
        });
    });

    $(document).ready(function()
    {  
        $('#select-plan').show();
        $('#list-type').hide();
        $('#city').hide();       
        $('#duration').hide();
        $('#desc').hide();
        $('#checkbox-view').hide();
        $('#price-l').hide();
        $('#price1').hide();
        $('#price2').hide();
        $('#subt').hide();
        $('#sport').hide();
        $('#catagory-listing').hide();
        $('#catagory-contact').hide();

        if ($('input[name=edit-plan]').val()=="listing-plan"){
            $('#list-type').show();
            $('#city').show();
            $('#price1').show();
            $('#price2').show();
            $('#duration').show();
            $('#sport').hide();
            $('#desc').show();
            $('#checkbox-view').show();
            $('#catagory-listing').show();
            $('#catagory-contact').hide();
            $('#price2').show();
            $('#subt').show();
        }
        if ($('input[name=edit-plan]').val()=="contact-plan"){
            $('#select-plan').show();
            $('#catagory-listing').hide();
            $('#catagory-contact').show();
            $('#list-type').hide();
            $('#city').hide();       
            $('#duration').show();
            $('#desc').show();
            $('#checkbox-view').show();
            $('#price1').show();
            $('#price2').show();
            $('#subt').show();
            $('#sport').hide();           
        }
    });  
    function getListtype(ID){
       var listtype = $("#listtype option:selected").val();
       if (listtype == 'Pro-players' || listtype == 'Players' || listtype =='Coaches') {
          $('#sport').show();
       }else{
        $('#sport').hide();
       }
    }

//To check Plan is already Exist or not
    // $("#submit").click(function () {
    //     var base_url = "<?php echo base_url(); ?>";
    //     $('#planmonths').rules('add', {
    //         required: true,
    //         remote: {
    //             url: base_url+"admin/subscription/Cn_subscription/checkExistPlan",
    //             type: "post",
    //             data: {
    //                     catlist: function(){ return $("#catlist").val(); },
    //                     city: function(){ return $("#city_id").val(); },
    //                     sport: function(){ return $("#sport_id").val(); },
    //                     plan: function(){ return $("#select-plan").val(); },
    //                     listtype: function(){ return $("#listtype").val(); },
    //                     catagorycon: function(){ return $("#catagorycon").val(); },
    //                     planmonths: function(){ return $("#planmonths").val(); },
    //                     pk_id: function(){ return $("#pk_id").val(); },
                       
    //             }
    //         },           
    //         messages: {
    //             required: "* Please select duration.",
    //             remote: "* Plan already exist.",
    //         }
    //     });
    // });

    //  $('#submit').click(function () {
    //     //check if checkbox is checked
    //     $value = $(this).val();

    //     if ($(this).is(':checked')) {
    //   alert("XDf");

    //         $('#view_on_android').attr('disabled', true); //disable input
    //     } else {
    //         $('#view_on_android').removeAttr('disabled'); //enable input
    //     }
    // });

    function get_viewData(id) {
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";
            $.ajax({
                type: "get",
                data: {id: id},
                url: base_url + "admin/subscription/Cn_subscription/view",
                dataType: 'json',
                success: function (data){
                    if (data.plan=="contact-plan"){
                        $("#contact-plan").modal('show');
                        $("#contactPlan").text("Contact Detail Plan");
                        $("#contactCategory").html(data.category);
                        $("#contactDuration").html(data.duration);
                        $("#contactMrp").html(data.mrp);
                        $("#contactPrice").html(data.offer);
                            d=  (data.desc).substr(0,1).toUpperCase()+(data.desc).substr(1);
                        $("#contactDesc").html(d);
                    }
                    if (data.plan=="listing-plan") {
                        $("#listing-plan").modal('show');
                        $("#listingPlan").text("Listing Plan");
                        $("#listingCat").html(data.category);
                        $("#listingType").html(data.listtype);
                        $("#listingSport").html(data.sportname);
                        $("#listingCity").html(data.city_name);
                        $("#listingDur").html(data.duration);
                        $("#listingMrp").html(data.mrp);
                        $("#listingOffer").html(data.offer);
                            d=  (data.desc).substr(0,1).toUpperCase()+(data.desc).substr(1);
                        $("#listingDes").html(d);                            
                    }
                }
            });
        } else {
            $("#headingView").html("");
            $("#descriptionView").html(data.heading);
            $("#urlView").html("");
            $("#typeView").html("");
        }
    }

 

   //    $(document).ready(function()
   // {
   //   $('#submit').dblclick(function()
   //   {
   //      $(this).attr('disabled',true);
   //      return false;
   //   });
   //  });


    // $("#submit").click(function () {
    //    var checked = $("#view_on_android").is(':checked');
    //    $("#view_on_android").prop("checked", true);
    //  //   var catlist = $('#catlist').val();
    //  //   var catagorycon = $('#catagorycon').val();
    //  //   var base_url = "<?php echo base_url(); ?>";
    //  //   if(checked){         
    //  //    var view_on_android = 'Yes';
    //  //   }else{
    //  //    var view_on_android = 'No';
    //  //   }if(catlist != ""){         
    //  //    var category = catlist;
    //  //   }
    //  //   if(catagorycon != ""){
    //  //    var category = catagorycon;
    //  //   }
    //  //   // alert(category); 
    //  //   $.ajax({
    //  //    type:"get",
    //  //    dataType:'json',
    //  //    url: base_url +"admin/subscription-action",
    //  //    data:{view_on_android:view_on_android,category:category},
    //  //    success: function(data){
    //  //    }
    //  // });
 
    // });



 </script>
</body>
</html>