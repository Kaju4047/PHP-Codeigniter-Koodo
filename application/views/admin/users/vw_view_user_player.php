<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->

<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
   <section class="content-header">
      <div class="col-md-4">
         <h1 style="margin:0px">
            User Details
         </h1>
      </div>
      <div class="col-md-8 no-right-pad" style="margin-bottom: 5px;">
         <h1 style="margin: 0px;">Profile Details
            <div class="pull-right">
           <!--  <a href="<?php echo base_url(); ?>admin/career-list/<?php  echo !empty($career) ? $career: '';?>"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a> -->
             <a href="javascript:history.go(-1)"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a> 
            
         </div>
         </h1>
      </div>

      
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-pad">
         <div class="row">
            <div class="col-md-4">
               <div class="box box-primary margin-bottom" style="min-height: 0px;">
                  <div class="box-body" style="min-height: 0px;padding: 0px;">
                     <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-12 no-pad">
                              <div class="view-hd">

                                 <p>Personal Details</p>
                              </div>
                           </div>
                              <div class="col-sm-12 form-group">
        
                
                  <?php $imgdata = !empty($usersDetails[0]['img']) ? 'uploads/users/' . $usersDetails[0]['img'] : 'AdminMedia/images/default.png'; ?>
                    <input type="hidden" name="fileold" id="fileold" class="form-control" value="<?php echo (!empty($edit['sportimg']) ? $edit['sportimg'] : ''); ?>">
                  <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="50%">
                 <!--  <span class="img-note">Note: (Upload PNG file & Image size - Width: 100px , Height:100px)</span> -->
               </div>

                           <div style="padding: 5px;background-color: #fff3f3">
                              <table class="tourntable">
                                 <tbody>
                                    <tr>
                                       <th width="24%">Name</th>
                                       <td style="border-top:none;" width="77%"><?php  echo !empty($usersDetails[0]['name']) ? ucwords($usersDetails[0]['name']) : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Mobile No.</th>
                                       <td><?php  echo !empty($usersDetails[0]['mob']) ? $usersDetails[0]['mob'] : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Email ID</th>
                                       <td><?php  echo !empty($usersDetails[0]['email']) ? $usersDetails[0]['email'] : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Date of Birth</th>
                                       <td><?php  echo !empty($usersDetails[0]['dob']) ? date("d-m-Y", strtotime($usersDetails[0]['dob'])) : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Gender</th>
                                       <td><?php  echo !empty($usersDetails[0]['gender']) ? ucfirst($usersDetails[0]['gender']) : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Age</th>
                                       <td><?php  echo !empty($usersDetails[0]['age']) ? $usersDetails[0]['age'] : '';?></td>
                                    </tr>
                                    <tr>
                                       <th style="vertical-align: top !important;">Location</th>
                                       <td><?php  echo !empty($usersDetails[0]['address']) ? ucfirst($usersDetails[0]['address']) : '';?></td>
                                    </tr>
                                <!--     <tr>
                                       <th>City</th>
                                       <td><?php  echo !empty($usersDetails[0]['city_name']) ? ucfirst($usersDetails[0]['city_name']) : '';?></td>
                                    </tr> -->
                                    <tr>
                                       <th>Qualification</th>
                                       <td><?php  echo !empty($usersDetails[0]['edudetails']) ? ucfirst($usersDetails[0]['edudetails']) : '';?></td>
                                    </tr>
                                    <tr>
                                       <th>Work</th>
                                       <td><?php  echo !empty($usersDetails[0]['occupation']) ? ucfirst($usersDetails[0]['occupation']) : '';?></td>
                                    </tr>
                                 </tbody>
                              </table>
                             
                           </div>
                            <div class="show-not">
                              <?php         
                                          $toggle = $contact_detail[0]['contact_detail'];
                                          $usertype = 1;
                                          $pk_id = $contact_detail[0]['pk_id'];
                                         
                                            if ($toggle == "1") {
                                                $status = "2";
                                                $class = "fa fa-toggle-on tgle-on";
                                                $title = "Active";
                                            } else if ($toggle == "2") {
                                                $status = "1";
                                                $class = "fa fa-toggle-on fa-rotate-180 tgle-off";
                                                $title = "Inactive";
                                            }
                                            ?>
                                          <a  href="<?php echo base_url(); ?>admin/document-status/<?php echo (!empty($pk_id) ? $pk_id : ''); ?>/1/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>  

                                
                                 <span>Contact Details Show or Not</span>
                              </div>
                        </div>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <!-- End box-body -->
               </div>
               <div class="box box-primary margin-bottom" style="min-height: 0px;">
                  <div class="box-body" style="min-height: 0px;padding: 0px;">
                     <div class="col-md-12 no-pad">
                        <div class="view-hd mg-bot-none">
                           <p>Document for Verification</p>
                        </div>
                     </div>
                     
                              
                     <div class="col-md-12 no-pad">
                        <div class="docs-verf">
                          <?php if (!empty($usersDetails[0]['document'])) {?>
                            <a href="<?php echo base_url(); ?>uploads/users/document/<?php  echo !empty($usersDetails[0]['document']) ? $usersDetails[0]['document']: '';?>" target="_blank">
                         
                           <button type="button" class="btn btn-primary btn-sm" ><i class="fa fa-eye"></i>View Document</button></a>
                            <?php } ?>
                        </div>

                        <div class="docs-verf">
                          <?php if(!empty($usersDetails[0]['doc_verify'])){
                              ?>
                           <label class="radio-inline">
                           <input type="radio" name="optradio" class="input_check_radio" value="2" <?php if($usersDetails[0]['doc_verify'] == '2') echo "checked='checked'"; ?> att="<?php echo $usersDetails[0]['pk_id'] ?>">Not Verified
                           </label>
                           <label class="radio-inline">
                           <input type="radio" name="optradio" class="input_check_radio" value="1" <?php if($usersDetails[0]['doc_verify'] == '1') echo "checked='checked'"; ?> att="<?php echo $usersDetails[0]['pk_id'] ?>">Verified
                           </label>
                         <?php }?>
                        </div>
                     </div>
                  </div>
               </div>
               <!--  <div class="box box-primary" style="min-height: 0px;">
                  <div class="box-body" style="min-height: 0px;padding: 0px;">
                     <div class="col-md-12 no-pad">
                        <div class="view-hd mg-bot-none">
                           <p>Upload Certificate</p>
                        </div>
                     </div>
                     <div class="col-md-12 no-pad">
                       
                        <div class="docs-verf">
                           <input type="file" class="form-control">
                        </div>
                     </div>
                  </div>
               </div> -->
            </div>
            <div class="col-md-8">
               <ul class="prl-approved nav nav-tabs">

                     <li class="active"><a href="<?php echo base_url(); ?>admin/user-view/<?php echo (!empty($contact_detail[0]['pk_id']) ? $contact_detail[0]['pk_id'] : ''); ?>/1">Player </a></li>
                  <li><a href="<?php echo base_url(); ?>admin/user-view/<?php echo (!empty($contact_detail[0]['pk_id']) ? $contact_detail[0]['pk_id'] : ''); ?>/2">Coach</a></li>
                  <li><a href="<?php echo base_url(); ?>admin/user-view/<?php echo (!empty($contact_detail[0]['pk_id']) ? $contact_detail[0]['pk_id'] : ''); ?>/3">Dealer / Practitioner / Other</a></li>
               </ul> 
                <?php $usertype=array_column($contact_detail, 'usertype');
               // print_r( $usertype);
               if (in_array('1',$usertype)) {
                  // echo "string";
                 
                ?>
               <div class="box box-primary" style="min-height: 0px;">
                  <div class="box-body">
                     <div class="col-md-12 no-pad">
                        <div class="col-md-9 no-pad">
                        
                              <div class="col-4">
                                  <label>List at Top</label>
                              <h5> 
                              <?php 
                                 // print_r($usersPlayerView['list_at_top']);
                              if (!empty($usersPlayerView['list_at_top'])) {

                              if ($usersPlayerView['list_at_top'] == '2') { ?>
                                <input type="checkbox" name="activeforjobdash" value="1" id="activeforjobdash" <?php echo ($usersPlayerView['list_at_top'] == '2' ? 'unchecked' : ''); ?> att="<?php echo $usersPlayerView['user_id'] ?>">
                                 <?php } else if ($usersPlayerView['list_at_top'] == "1") { ?>

                                  <input type="checkbox" name="activeforjobdash" value="2" id="activeforjobdash" <?php echo ($usersPlayerView['list_at_top'] == '1' ? 'checked' : ''); ?> att="<?php echo $usersPlayerView['user_id'] ?>">
                                <?php }}?>
                              </h5> 
                            </div>
                        </div>
                        <div class="col-md-3 no-right-pad pull-right">
                           <div class="rating-bx no-right-pad" >
                              <p><span><?php  echo !empty($rating[0]['count']) ?$rating[0]['count'] : '0';?></span> Ratings</p>
                              <span><?php  echo !empty($rating[0]['average']) ? round($rating[0]['average'], 2) : '0';?>
                           <!-- <i class="fa fa-star" aria-hidden="true"></i>  -->
                            
                               <?php   $rating=!empty($rating[0]['average']) ? $rating[0]['average']:'0'; ?>
                <?php if(!empty($rating)){ ?>
            
                    <?php 

                     
                    $star_array=array();
                    for($i=0;$i<5;$i++){
                    $ival=$i+0.5;
                    if ($rating==$ival) {
                    array_push($star_array,'fa-star-half-o');
                    }
                    elseif($ival<=$rating){
                    array_push($star_array,'fa fa-star');
                    }
                    else{
                    array_push($star_array,'blank');
                    }
                    }
                    ?>
                    <?php for ($j=0; $j <count($star_array) ; $j++) { 
                    if ($star_array[$j]=='blank') {
                    $class='fa fa-star-o';
                    }
                    else{
                    $class=$star_array[$j];
                    }
                    ?>
                   
                    <span class="fa <?=!empty($class)?$class: "";?>" style="<?php if($star_array[$j]=='blank'){echo "color:   #FFFFFF !important;";}else{echo "color:   #FFFFFF !important;";}?>"></span>
                    <?php }  ?>
                    
                <!-- </div> -->
              <?php } ?> 

                             </span>
                           </div>
                        </div>
                        <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Sports</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="upld-docs bord-docs">
                                       <?php foreach ($usersPlayerSport as $key => $value){ ?>                                    
                                       <span><?php  echo !empty($value['sportname']) ? ucwords($value['sportname']) : '';?></span>
                                       
                                    <?php }?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Playing Time</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p><?php  echo !empty($usersPlayerView[0]['playing_time']) ? ucwords($usersPlayerView[0]['playing_time']) : '';?></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Experience</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p><?php  echo !empty($usersPlayerView[0]['experience']) ? ucwords($usersPlayerView[0]['experience']) : '';?></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                         <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">About me / Achievement</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p><?php  echo !empty($usersPlayerView[0]['achivement']) ? ucwords($usersPlayerView[0]['achivement']) : '';?></p>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Member of club/Academy</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p><?php  echo !empty($usersPlayerView[0]['club_detail']) ? ucwords($usersPlayerView[0]['club_detail']) : '';?></p>
                                 </div>
                              </div>
                           </div>
                        </div>  
                        <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Download</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <h5 class=""><strong>Documents</strong></h5>
                              <?php foreach ($player_certificate as $key => $value){?>
                              <?php $imgdata = !empty($value['doc_certificate']) ?  base_url('uploads/users/profile_certificate_doc/'.$value['doc_certificate']) : ''; ?>
                                   <a href="<?php echo $imgdata; ?>" download>  
                                   <span><i class="fa fa-download"> &nbsp; <?php echo ($value['file_name']);?></i></span></a></br>
                               <?php  }?>
                              <h5 class="" style=""><strong>Certificates</strong></h5>
                              <?php foreach ($player_doc as $key => $value){
                                // print_r($value['doc_certificate']);
                           ?>
                           <?php $imgdata2 = !empty($value['doc_certificate']) ?  base_url('uploads/users/profile_certificate_doc/'. $value['doc_certificate']) : ''; ?>
                              <a href="<?php echo $imgdata2; ?>" download>
                              <span><i class="fa fa-download">&nbsp; <?php echo ($value['file_name']);?> </i></span></a></br>
                             <?php  }?>

                           </div>
                        </div>

                     <!--    <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">Skills</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <span class="skills-bx"><?php  echo !empty($usersPlayerSport[0]['skill']) ? ucwords($usersPlayerSport[0]['skill']) : '';?></span>
                                 </div>
                              </div>
                           </div>
                        </div>  -->


                       <!--  <div class="col-sm-12 no-pad box-style">
                           <div class="row">
                              <div class="col-sm-12">
                                 <h4 class="data-video">About Me</h4>
                              </div>
                           </div>
                           <div class="pad-box">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p><?php  echo !empty($usersPlayerView[0]['about_me']) ? ucwords($usersPlayerView[0]['about_me']) : '';?></p>
                                 </div>
                              </div>
                           </div>
                        </div> -->

                  </div>
                  <!-- End box-body -->
               </div>
               <?php }else{ ?>
              <div class="box box-primary" style="min-height: 0px;">
                  <div class="box-body">
                     <div class="col-md-12 no-pad">
                      <div class="col-md-10 no-pad">
                       <label>User not register for Player profile</label>
                     </div>
                   </div>
                 </div>
               </div>

            <?php  }?>
            </div> 
         </div>
         <!-- End box -->
      </div>
      <!-- End col-md-4 -->
      <div class="clearfix"></div>
   </section>
</div>
<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
   $(".usersLi").addClass("active");
   /*Start::For::Activly Looking For Job Change*/ 
 $("#activeforjobdash").change(function () {
        var cid = $("input[name='activeforjobdash']:checked"). val();
        var id = $('#activeforjobdash').attr('att'); 
        var usertype = '1';
        var base_url = "<?php echo base_url(); ?>";
        $.ajax({
            type: "POST",
            dataType: 'json',
            url:  base_url +"admin/users/Cn_users/list_at_top",
            data: {cid: cid, id:id,usertype:usertype},
            success: function (result) {

              $('#activeforjobdash').val(result.list_at_top);
            }
        });
    });

    $(".input_check_radio").change(function () {
        var cid = $(this).val();
        var id = $('.input_check_radio').attr('att');
        // alert(id);
        var base_url = "<?php echo base_url(); ?>";
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: base_url +"admin/users/Cn_users/doc_verify",
            data: {cid: cid, id:id},
            success: function (result) {
              $('#doc_verify').val(result.doc_verify);
            }
        });

    });
</script>
</body>
</html>