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
(in_array('dealer_product', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Dealer Products List </h1>
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
                      <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo (!empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : ''); ?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label>To Date</label>
                      <div class="input-group date" data-date-format="dd.mm.yyyy">
                      <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo (!empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : ''); ?>" autocomplete="off">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label>Dealer</label>
                    <select class="form-input form-control select2" name="dealer">
                      <option  value="">Select Dealer</option>
                      <?php if(!empty($dealerNameDetails)) {
                       
                        foreach ($dealerNameDetails as $key => $value) {
                          ?>
                     <option value="<?php echo !empty($value['pk_id'])?$value['pk_id']:''?>"<?php echo ((!empty($dealer) && $dealer == $value['pk_id']) ? 'selected' : ''); ?>><?php echo !empty($value['name'])?$value['name']:''?></option>
                   <?php }}?>
                      
                    </select>
                </div>

                 <div class="col-md-3 form-group">
                    <label>Sport Type</label>
                    <select class="form-control" name="category">
                      <option value="">Select sport type</option>
                       <?php if(!empty($categoryDetails)) {
                       
                        foreach ($categoryDetails as $key => $value) {
                          ?>
                     <option value="<?php echo !empty($value['pk_id'])?$value['pk_id']:''?>"<?php echo ((!empty($category) && $category == $value['pk_id']) ? 'selected' : ''); ?>><?php echo !empty($value['sportname'])?$value['sportname']:''?></option>
                   <?php }}?>
                    </select>
                </div>

              
                 


              </div>
              <!-- <?php echo !empty($cityDetails)?$cityDetails:'123'?> -->
              <div class="row">
                  <div class="col-md-3 form-group">
                    <label>Brand Name</label>
                    <select class="form-control" name="brand">
                      <option  value="">Select Brand Name</option>
                       <?php if(!empty($brandDetails)) {
                       
                        foreach ($brandDetails as $key => $value) {
                          ?>
                     <option value="<?php echo !empty($value['brand_name'])?$value['brand_name']:''?>"<?php echo ((!empty($brand) && $brand == $value['brand_name']) ? 'selected' : ''); ?>><?php echo !empty($value['brand_name'])?$value['brand_name']:''?></option>
                   <?php }}?>
                    </select>
                </div>
                <!--  <div class="col-md-3 form-group">

                  <label>City</label>
                  <select class="form-control" name="city">
                     <option value="">Select City</option>
                     <?php if (!empty($cityDetails)) {
                        foreach ($cityDetails as $key => $value) {
                           ?>
                     <option value="<?php  echo !empty($value['pk_id']) ? $value['pk_id'] : '';?>"<?php echo( (!empty($city) && $city==$value['pk_id'])?'selected' : '') ?>><?php  echo !empty($value['city_name']) ? $value['city_name'] : '';?></option>
                     <?php }}?>
                  </select>
               </div>  -->
                
               <div class="col-md-3 form-group">
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-dealerProduct');?>';"><i class="fa fa-filter"></i> Filter</button>
                  </div>
                <div class="col-md-3 form-group">
                        <?php if(!empty($dealarProductDetails)){  

                                  ?> 
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/product-export-to-excel/1');?>';"><i class=""></i> Export to Excel</button>
                   <?php } ?>
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
                        <th width="8%">Date</th>
                        
                        <th width="7%">Image</th>
                        <th width="12%">Product Name</th>
                        <th width="8%">Brand Name</th>
                        <th width="8%">Sport Type</th>
                       <!--  <th width="6%">City</th> -->
                        <th width="8%">MRP</th>
                        <th width="8%">Cost (Rs.)</th>
                        <th width="26%">Dealer Details</th>
                        <th width="1%">Status</th>
                        <th style="text-align: center !important;" >Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($dealarProductDetails)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                           foreach ($dealarProductDetails as $key => $value) {
                            
                          ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td><?php echo !empty($value['createdDate'])?date('d-m-Y',strtotime($value['createdDate'])):''?></td>
                        <td class="text-center">
                          <?php $imgdata = !empty($value['img']) ? 'uploads/products/img/'.$value['img'] : 'AdminMedia/images/default.png'; 
                           // print_r($imgdata);
                          ?>
                          <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="100%">
                        </td>
                        <td><?= !empty($value['product_name']) ? ucfirst($value['product_name']) : '-' ?></td>
                        <td><?php echo !empty($value['brand_name'])?ucfirst($value['brand_name']):'-'?></td>
                        <td><?php echo !empty($value['sportname'])?ucfirst($value['sportname']):'-'?></td>
                       <!--  <td><?php echo !empty($value['city_name'])?ucfirst($value['city_name']):''?></td> -->
                        <td><?php echo !empty($value['mrp'])?$value['mrp']:'-'?></td>
                        <td><?php echo !empty($value['cost'])?$value['cost']:'-'?></td>
                        <td><?php echo !empty($value['name'])?ucwords($value['name']):'-'?>,
                           <?php echo !empty($value['mob'])?$value['mob']:'-'?><br>,
                           <?php echo !empty($value['email'])?$value['email']:'-'?>,
                           <?php echo !empty($value['address'])?ucfirst($value['address']):'-'?>
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/dealer-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                          <!--  <button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#viewprodModal"><i class="fa fa-eye"></i></button> -->
                           <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_dataView(<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>)"><i class="fa fa-eye"></i></button>

                           <a href="<?php echo base_url(); ?>admin/delete-dealer/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                        </td>
                     </tr>
                   <?php $i++; }}
                   ?>
                   
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
    <div class="modal-dialog" style="width: 700px;">
    
      <!-- Payment Modal start-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Dealer Product Details</h4>
        </div>
        <div class="modal-body">
         <div class="col-md-9 no-pad">
          
         <div class="col-md-4">
            <label>Dealer</label>
            <h2 class="view-cnt" id="dealername"></h2>
         </div>
         
         <div class="col-md-3">
            <label>Mobile No.</label>
            <h2 class="view-cnt" id="dealermob"></h2>
         </div>
         <div class="col-md-5">
            <label>Email Id</label>
            <h2 class="view-cnt" id="dealeremail"></h2>
         </div>
         <div class="col-md-12">
            <label>Address</label>
            <h2 class="view-cnt" id="dealeraddress"></h2>
         </div>
         <div class="col-md-4">
            <label>Product Name</label>
            <h2 class="view-cnt" id="productName"></h2>
         </div>
         <div class="col-md-4">
            <label>Brand Name</label>
            <h2 class="view-cnt" id="brandName"></h2>
         </div>
         <div class="col-md-4">
            <label>Sport Type</label>
            <h2 class="view-cnt" id="category"></h2>
         </div>
         <div class="col-md-4">
            <label>MRP</label>
            <h2 class="view-cnt" id="mrp"><i class="fa fa-rupee"></i></h2>
         </div>
         <div class="col-md-4">
            <label>Cost</label>
            <h2 class="view-cnt" id="cost"><i class="fa fa-rupee"></i></h2>
         </div>
          <div class="col-md-12">
            <label>Description</label>
            <h2 class="view-cnt" id="description"></h2>
         </div>
        </div>
         <div class="col-md-3 no-pad">
            <img src="<?php echo base_url(); ?>"  id="product_image" width="100%">
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
   $(".prodsLi").addClass("active");
   $(".dealprodsLi").addClass("active");
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

// aler(fromstart);

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
                  url: base_url + "admin/products/Cn_products/view",
                  dataType: 'json',
                                       
                   success: function (data)
                   {
                    // alert(JSON.stringify(data));
                    
                        $("#viewprodModal").modal('show');
                         name=  (data.name).substr(0,1).toUpperCase()+(data.name).substr(1);
                        $("#dealername").text(name);
                        $("#dealermob").text(data.mob);
                        $("#dealeremail").text(data.email);
                         address=  (data.address).substr(0,1).toUpperCase()+(data.address).substr(1);
                         $("#dealeraddress").text(address);
                         product_name=  (data.product_name).substr(0,1).toUpperCase()+(data.product_name).substr(1);
                         $("#productName").text(product_name);
                         brand_name=  (data.brand_name).substr(0,1).toUpperCase()+(data.brand_name).substr(1);
                         $("#brandName").text(brand_name);
                         category=  (data.sportname).substr(0,1).toUpperCase()+(data.sportname).substr(1);
                         $("#category").text(category);
                         $("#mrp").text(data.mrp);
                         $("#cost").text(data.cost);
                         description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                         $("#description").text(description);
                         if (data.img== '') {
                             $("#product_image").attr("src",'<?php echo base_url()?>AdminMedia/images/default.png');
                         }else{
                            $("#product_image").attr("src",'<?php echo base_url()?>uploads/products/img/'+data.img);
                         }
                                                
                    }
               });
        } 
    }

</script>
</body>
</html>