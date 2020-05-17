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
            Tournament Details
         </h1>
      </div>
      <div class="col-md-8 no-right-pad" style="margin-bottom: 5px;">
         <h1 style="margin: 0px;">
            Enquiry Details
            <div class="pull-right">
               <a href="<?php echo base_url(); ?>admin/tournaments-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
            </div>
         </h1>
      </div>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-pad">
         <div class="row">
            <div class="col-md-4">
               <div class="box box-primary bord-rad-z" style="min-height: 0px;">
                  <div class="box-body bord-rad-z" style="min-height: 0px;padding: 0px;">
                     <div class="view-hd tourn-head">
                        <p><?php  echo !empty($tounamentsDetails['tornamentName']) ? ucfirst($tounamentsDetails['tornamentName']) : '';?></p>
                     </div>
                     <div class="col-md-12 tourn-img-div">
                       <!--  <div class="tourn-img" style="background-image: url('<?php echo base_url(); ?>AdminMedia/images/cricket.png');">
                        </div> -->
                         <div class="tourn-img">
                            <?php $imgdata = !empty($tounamentsDetails['tornamentImage']) ? 'uploads/tournaments/images/' . $tounamentsDetails['tornamentImage'] : 'AdminMedia/images/default.png'; ?>
                    
                            <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="100%">
                           </div>
                     </div>
                     <div class="col-md-12">
                        <div style="padding: 5px;background-color: #fff;">
                           <table class="tourntable">
                              <tbody>
                                 <tr>
                                    <th>Sport</th>
                                    <td><?php  echo !empty($tounamentsDetails['sportname']) ? ucfirst($tounamentsDetails['sportname']) : '';?></td>
                                 </tr>
                                 <tr>
                                    <th width="30%">Start Date</th>
                                    <td style="border-top:none;" width="77%">18-6-2019</td>
                                 </tr>
                                 <tr>
                                    <th width="24%">End Date</th>
                                    <td style="border-top:none;" width="77%">18-10-2019</td>
                                 </tr>
                                 <tr>
                                    <th width="24%">No. Entries</th>
                                    <td style="border-top:none;" width="77%"><?php  echo !empty($tounamentsDetails['entery_number']) ? $tounamentsDetails['entery_number'] : '';?></td>
                                 </tr>
                                 <tr>
                                    <th>Entry Fees</th>
                                    <td><i class="fa fa-rupee"></i> <?php  echo !empty($tounamentsDetails['entery_fees']) ? $tounamentsDetails['entery_fees'] : '';?></td>
                                 </tr>
                                 <tr>
                                    <th>Price Money</th>
                                    <td><i class="fa fa-rupee"></i> <?php  echo !empty($tounamentsDetails['price_money']) ? $tounamentsDetails['price_money'] : '';?></td>
                                 </tr>
                                 <tr>
                                    <th>Venue</th>
                                    <td><?php  echo !empty($tounamentsDetails['address']) ? ucfirst($tounamentsDetails['address']) : '';?></td>
                                 </tr>
                                 <tr>
                                    <th>Entry Form</th>
                                     <td>
                                     <?php $imgdata = !empty($tounamentsDetails['entry_form']) ?  base_url('uploads/tournaments/entryform/' . $tounamentsDetails['entry_form']) : 'AdminMedia/images/default.png'; ?>
                                      <a href="<?php echo $imgdata; ?>" download>
                                      <button type="button" class="btn btn-success btn-xs" title="Download Entry Form"><i class="fa fa-download"></i>Download</button></a></td>

                                    <!-- <td><button type="button" class="btn btn-success btn-xs"><i class="fa fa-download"></i> Download</button></td> -->
                                 </tr>
                                 <tr>
                                    <th>Description</th>
                                    <td><?php  echo !empty($tounamentsDetails['description']) ? ucfirst($tounamentsDetails['description']) : '';?></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     
                  </div>
                  <!-- End box-body -->
               </div>
               <div class="clearfix"></div>
               <div class="col-md-12 no-pad">
                  <div>
                     <h3 class="cret-by">Created By</h3>
                  </div>
                  <div class="tranc pending new-card">
                     <div class="col-sm-4 " style="padding:0 10px;">
                         <?php $imgdata = !empty($tounamentsDetails['userImage']) ? 'uploads/users/' . $tounamentsDetails['userImage'] : 'AdminMedia/images/default.png'; ?>
                    
                            <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="100%">
                       <!--  <img src="<?php echo base_url(); ?>AdminMedia/images/avatar5.png" class="img-responsive"> -->
                     </div>
                     <div class="col-md-8 no-pad">
                        <h2><?php  echo !empty($tounamentsDetails['created_by']) ? ucwords($tounamentsDetails['created_by']) : '';?></h2>
                        <h3><?php  echo !empty($tounamentsDetails['mob']) ? $tounamentsDetails['mob'] : '';?></h3>
                        <h3><?php  echo !empty($tounamentsDetails['email']) ? $tounamentsDetails['email'] : '';?></h3>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
               <div class="box box-primary" style="min-height: 0px;">
                  <div class="box-body">
                     <div class="col-md-12 no-pad">
                        <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                           <thead class="table-example">
                              <tr>
                                 <th width="7%">Sr. No.</th>
                                 <th width="17%">Date Time</th>
                                 <th width="19%">Name</th>
                                 <th width="15%">Mobile No.</th>
                                 <th width="20%">Email Id</th>
                                 <th width="21%">Location</th>
                                 <th width="1%">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td class="text-center">1</td>
                                 <td>18-6-2019 11:30 AM</td>
                                 <td>John Doe</td>
                                 <td>9876543210</td>
                                 <td>johndoe@gmail.com</td>
                                 <td>Vishrantwadi,Pune.</td>
                                 <td class="text-center"><button title="Download" type="button" class="btn btn-success bord-rad btn-xs"><i class="fa fa-download"></i> Download</button></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!-- End box-body -->
               </div>
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
   $(".tournLi").addClass("active");
    $("#example").DataTable();
</script>
</body>
</html>