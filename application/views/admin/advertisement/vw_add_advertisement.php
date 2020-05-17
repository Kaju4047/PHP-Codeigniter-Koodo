<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Add Advertisement
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/advertisement-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary">
            <div class="box-body">
              <form id="add_adv" action="<?php echo base_url(); ?>admin/advertisement-action" method='post'   enctype="multipart/form-data"> 
              <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
               <div class="col-md-12 no-pad">
                  <div class="col-md-3 form-group">
                     <label>Advertisement Place <span style="color: red">*</span></label>
                     <select class="form-control" name="place" style="color: #555">
                        <option disabled selected>Select Ads. Place</option>

                        <option value="1"<?php echo ((!empty($edit['place']) && $edit['place'] == '1') ? 'selected' : ''); ?>>Section 1</option>
                         <option value="2"<?php echo ((!empty($edit['place']) && $edit['place'] == '2') ? 'selected' : ''); ?>>Section 2</option>
                          <option value="3"<?php echo ((!empty($edit['place']) && $edit['place'] == '3') ? 'selected' : ''); ?>>Section 3</option>
                          <option value="4"<?php echo ((!empty($edit['place']) && $edit['place'] == '4') ? 'selected' : ''); ?>>Sport Book</option>
                        
                           <option value="5"<?php echo ((!empty($edit['place']) && $edit['place'] == '5') ? 'selected' : ''); ?>>Listing</option>

                     </select>
                  </div>
                  <div class="col-md-6 form-group">
                     <label>Advertisement Name  <span style="color: red">*</span></label>
                     <input type="text" name="advname" class="form-control" maxlength="50"  value="<?php echo (!empty($edit['advname']) ? $edit['advname'] : ''); ?>" autocomplete="off">
                  </div>
                  <div class="col-md-3 form-group">
                     <label>Mobile No.  <span style="color: red">*</span></label>
                     <input type="text" name="mob" minlength="10" maxlength="13" class="form-control" value="<?php echo (!empty($edit['mob']) ? $edit['mob'] : ''); ?>" autocomplete="off">
                  </div>
                  <div class="col-md-3 form-group">
                     <label>State  <span style="color: red">*</span></label>
                     <select class="form-control" name="state" id="state" onchange="getCity(this);" style="color: #555">
                        <option selected disabled>Select State</option>
                        <?php if(!empty($stateDetails)){
                          foreach ($stateDetails as $key => $value) {
                          ?>
                          <option value="<?= $value['pk_id'];?>"<?php echo ((!empty($edit['state']) && $edit['state'] == $value['pk_id']) ? 'selected' : ''); ?>><?= $value['state_name'];?></option>

                      
                        <?php }}?>
                     </select>
                  </div>
                  <div class="col-md-3 form-group">
                     <label>City  <span style="color: red">*</span></label>
                     <select class="form-input form-control select2" name="city" id="city" style="color: #555">
                        <option disabled selected>Select City</option>
                        <?php if(!empty($city)){
                          foreach ($city as $key => $value) {
                          ?>
                          <option value="<?= $value['pk_id'];?>"<?php echo ((!empty($edit['city']) && $edit['city'] == $value['pk_id']) ? 'selected' : ''); ?>><?= $value['city_name'];?></option>

                      
                        <?php }}?>
                        
                     </select>
                     <div for="city" generated="true" class="error"></div>
                  </div>
                 
                  <div class="col-md-3 form-group">
                     <label>From  <span style="color: red">*</span></label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="text" id="fromdate" class="form-control" name="fromdate" placeholder="dd-mm-yyyy" value="<?php echo (!empty($edit['fromdate']) ? date('d-m-Y',strtotime($edit['fromdate'])) : ''); ?>" autocomplete="off">
                        <div class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                      <div for="fromdate" generated="true" class="error"></div>
                  </div>
                   <!-- <div class="clearfix"></div> -->
                  <div class="col-md-3 form-group">
                     <label>To  <span style="color: red">*</span></label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="text" id="todate" class="form-control" name="todate" placeholder="dd-mm-yyyy" value="<?php echo (!empty($edit['todate']) ?date('d-m-Y',strtotime($edit['todate'])) : ''); ?>" autocomplete="off">
                        <div class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                      <div for="todate" generated="true" class="error"></div>
                  </div>
                   <div class="col-md-3 form-group">
                     <label>Price  <span style="color: red">*</span></label>
                     <input type="text" name="price" class="form-control"  maxlength="10"  value="<?php echo (!empty($edit['price']) ? $edit['price'] : ''); ?>" autocomplete="off">
                  </div>
                  <div class="col-md-6 form-group">
                     <label>URL  <span style="color: red">*</span></label>
                     <input type="text" name="url" class="form-control" value="<?php echo (!empty($edit['url']) ? $edit['url'] : ''); ?>" autocomplete="off">
                  </div>
                  <div class="col-md-3 form-group">
                     <label>Upload Ads. <span style="color: red">*</span></label>
                      <input type="file"  name="advimg" id="advimg" class="form-control upld-file">
                   <span style="color: green">Note: Upload image less than 500KB</span>
                  <?php $imgdata = !empty($edit['advimg']) ? 'uploads/master/advimg/' . $edit['advimg'] : 'AdminMedia/images/default.png'; 
                  ?>
                    <input type="hidden" name="fileold" id="fileold" class="form-control" value="<?php echo (!empty($edit['advimg']) ? $edit['advimg'] : ''); ?>">
                  <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="50%"> 

                  </div>
                  
                  <div class="clearfix"></div>
                  <div class="col-md-12 form-group">
                     <button type="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                     <input type="hidden" name="base_url" id="base_url" value="<?php 'http://localhost/Koodo/' ?>">
                     <!-- <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
                  </div>
               </div>
             </form>
            </div>
         </div>
      </div>
      <div class="clearfix"></div>
   </section>
   <!-- End .content -->
</div>
<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
 <script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_advertisement/advertisement.js'); ?>"></script> 

<script type="text/javascript">

   $(".advLi").addClass("active");
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

   
   
    $(".upld-file").change(function (event) 
    {
        var ev = event.target;
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = imageIsLoaded;
          reader.readAsDataURL(this.files[0]);
        }
        function imageIsLoaded(e) {
            $(ev).siblings('.img-upload').attr("src", e.target.result);
            /*  $(ev).parent().siblings.('.img-upload').css('background', 'url("' + event.target.result + '")');*/
        };
    }); 

    function getCity(Id) {
     // alert($(this.val(Id));
        $("#city").val('');
        var state = $("#state option:selected").val();
         
        var base_url = "<?php echo base_url(); ?>";
        $.ajax({
            type: "POST",
            data: {Id: state},
            url: base_url + "admin/advertisement/Cn_advertisement/getCityById",
            dataType: 'json',
 
            success: function (data){
               // alert(JSON.stringify(data));
                var html='';
                html +=('<option value="">Select</option>');
                if(data!=""){
                $.each( data, function( key, value ){
                    html +=('<option value="'+value.pk_id+'">'+value.city_name.charAt(0).toUpperCase()+value.city_name.slice(1)+'</option>');
                });
                }
                $('#city').html(html); 
                $('#city').css('textTransform', 'capitalize');
            }
        });
                                      
    } 
</script>
</body>
</html>