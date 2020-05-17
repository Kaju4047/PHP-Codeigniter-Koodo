<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
   <section class="content-header">
    
      <h1>Add Sport Facility
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/sports-clubs-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
          </div>
      </h1>     
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
      <form id="add_clubs" action="<?php echo base_url(); ?>admin/sports-club-action" method='post'   enctype="multipart/form-data"> 
      <input type="hidden" name="txtid" id="txtid" value="<?php echo (!empty($edit['pk_id']) ? $edit['pk_id'] : ''); ?>" class="form-control">
         <div class="box box-primary">
            <div class="box-body">
               <div class="col-md-12 no-pad">
               
                <div class="col-md-4 form-group">
                  <label>Name<span style="color:red">*</span></label>
                  <input type="text" name="name" class="form-control" maxlength="50" value="<?php echo (!empty($edit['name']) ? $edit['name'] : ''); ?>" autocomplete="off">
               </div>
              <!--   <div class="col-md-4 form-group">
                  <label>Address<span style="color:red">*</span></label>
                  <input type="text" name="address" class="form-control" maxlength="50" value="<?php echo (!empty($edit['address']) ? $edit['address'] : ''); ?>" autocomplete="off">
               </div> -->
               <div class="col-md-4 form-group location-icon">
                  <label>Address<span style="color:red">*</span></label>
                  <img onclick="detectLocation()" src="<?php echo base_url();?>AdminMedia/images/maps-and-flags.png" title="click Me" width="10%">
                  <!-- <textarea rows="3" name="event_location" id="event_location" class="form-control" style="resize: none;"><?php echo !empty($event_data['event_location']) ? ucfirst($event_data['event_location']) :''; ?></textarea> -->
                  <input type="text" class="form-control" onFocus="fillInAddress()" name="event_location" id="event_location" placeholder="Address" value="<?php echo (!empty($edit['address']) ? $edit['address'] : ''); ?>">
              </div>

                <div class="col-md-4 form-group">
                  <label>Email<span style="color:red">*</span></label>
                  <input type="text" name="email" class="form-control" maxlength="50" value="<?php echo (!empty($edit['email']) ? $edit['email'] : ''); ?>" autocomplete="off">
               </div>
             </div>
             <div class="col-md-8 no-pad">
                <div class="col-md-6 form-group">
                  <label>mobile<span style="color:red">*</span></label>
                  <input type="text" name="mobile" class="form-control" maxlength="50" value="<?php echo (!empty($edit['mobile']) ? $edit['mobile'] : ''); ?>" autocomplete="off">
               </div>
                <div class="col-md-6 form-group">
                  <label>Sport<span style="color:red">*</span></label>
                  <input type="text" name="sport" class="form-control" value="<?php echo (!empty($edit['sport']) ? $edit['sport'] : ''); ?>" autocomplete="off">
               </div>
                

              <!--  <div class="col-md-4 form-group">
                     <label>Sport<span style="color:red">*</span></label>
                     <div class="clearfix"></div>
                     <select  name="sport[]" id="sport" multiple="multiple"  onchange="console.log($(this).children(':selected').length)" class="testSelAll2 form-control sumoselect" style="color: #555;">

                                    <?php
                                    if (!empty($sportDetails)) {
                                        foreach ($sportDetails as $key => $value) {
                                        
                                          $selected = (in_array($value['pk_id'], $sportDetails)) ? "selected=''" : "";
                                         
                                            ?>

                                            <option value="<?php echo!empty($value['pk_id']) ? $value['pk_id'] : ''; ?>" id="type_option" <?php echo $selected; ?>><?php echo!empty($value['sportname']) ? ucfirst($value['sportname']) : ''; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                
                  </div>
                       <div for="sport[]" generated="true" class="error"></div> -->
                  
               <div class="clearfix"></div>
                <div class="col-md-12 form-group">
                  <label>Description<span style="color:red">*</span></label>
                 <textarea class="form-control" id="txteditor" style="resize: none;height: 110px" name="txteditor" placeholder=""   ><?php echo (!empty($edit['description']) ? $edit['description'] : ''); ?></textarea>
               </div>
               <div class="clearfix"></div>               
               <div class="col-md-12 form-group">
                 <label class="rad-mgbot-weit">
                   Website
                  </label>
                  <div class="clearfix"></div>
                 <input type="text" class="form-control" name="website" placeholder="Website"  value="<?php echo (!empty($edit['website']) ? $edit['website'] : ''); ?>" autocomplete="off">
               </div>


               <div class="col-md-12 form-group">
                  <button type="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                  <!-- <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
               </div>
            </div>
            <div class="col-md-4">
              <div class="col-md-12 no-pad">
                    
                       
                        <?php $image =  !empty($edit['image']) ? 'uploads/clubs/'.$edit['image'] : 'AdminMedia/images/default.png'; ?>
                        <div class="row">
                           <div class="col-md-12">
                              <label class="lab-photo">Sport Facility Image</label>
                           </div>
                        </div>
                        <div class="form-group">
                           <img src=" <?php echo!empty($image) ? base_url() .$image : ""; ?>" class="photo" width="150">
                        </div>
                        <input name="facilityimage" class="form-control" id="facilityimage" type="file" >
                     </div>
            </div>
            <!-- End box-body -->
         </div>
         <!-- End box -->
         </form>
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
<script type="text/javascript" src="<?php echo base_url('AdminMedia/validations/js_sports-clubs/sports-clubs.js'); ?>"></script> 

<!--start::code for ck editor-->

<script src="<?php echo base_url(); ?>AdminMedia/editor/ckeditor.js"></script>

<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD19o3ef65KJnJ9qCKaph5XuR-hSW6sfXM&libraries=places"></script> -->

<script>

  $("#facilityimage").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
    function imageIsLoaded(e) {
        $('.photo').attr("src", e.target.result);
    }



    /*[START::get current location of user::]*/
      function detectLocation() {

        if (navigator.geolocation) {
          

          navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
          x.innerHTML = "Geolocation is not supported by this browser.";
        }
      }

      function showPosition(position) {
         
        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var geocoder = geocoder = new google.maps.Geocoder();

          geocoder.geocode({ 'latLng': latlng }, function (results, status) {

              if (status == google.maps.GeocoderStatus.OK) {
                  if (results[1]) {
                        var place=results[1];
                        $("#event_location").val(results[1].formatted_address);

                        if(place!="")
                        {

                          var basic_addr="";
                          if(place.address_components!=""){
                            //alert(JSON.stringify(place.address_components));
                            $.each( place.address_components, function( i, val ){
                              
                              if(val.types[0]=='country')
                              {
                                if(val.long_name!=''){
                                $("#country").val(val.long_name);
                                }
                              }
                              if(val.types[0]=='administrative_area_level_1')
                              {

                                if(val.long_name!=''){
                                $("#state").val(val.long_name);
                                }
                              }
                              if(val.types[0]=='locality')
                              {
                                if(val.long_name!=''){
                                $("#event_city").val(val.long_name);
                                }
                              }
                              
                              // else if(val.types[0]=='sublocality_level_2')
                              // {

                              //   if(val.long_name!=''){
                              //   $("#").val(val.long_name);
                              //   }
                              // }
                              
                            });
                          }
                          
                          
                        }

                 
                    }
              }
          });
      }

    /*[END ::get current location of user::]*/


    
    /*[START ::google address::]*/
       var placeSearch, autocomplete;
      var componentForm = {
        
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        
      };
     google.maps.event.addDomListener(window, 'load', function () { 
        autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('event_location')),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);

      });
     

      function fillInAddress() {
        var place = autocomplete.getPlace();
       // alert(place);
         if(place!="")
        {

          var basic_addr="";
          if(place.address_components!=""){
            // alert(JSON.stringify(place.address_components));
            $.each( place.address_components, function( i, val ){
              
              if(val.types[0]=='country')
              {
                if(val.long_name!=''){
                $("#country").val(val.long_name);
                }
              }
              if(val.types[0]=='administrative_area_level_1')
              {

                if(val.long_name!=''){
                $("#state").val(val.long_name);
                }
              }
              if(val.types[0]=='locality')
              {
                if(val.long_name!=''){
                $("#event_city").val(val.long_name);
                }
              }
              
            });
          }
          
          
        }

       // alert(JSON.stringify(place));
        
        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          //alert(addressType);
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
           //alert(val);
            document.getElementById(addressType).value = val;
          }
        }
      }
      /*[END ::google address::]*/
</script>


<!--end::code for ck editor-->
</body>
</html>