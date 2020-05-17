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
(in_array('sport_new', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Advertise With Us
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">

         <div class="box box-primary">

             <div class="box-body">
               <div class="row">
                  <div class="col-md-6" style="display: inline-block;float: right;margin-bottom: 10px;">
                     <form name="frmSearch" id="frmSearch" action="<?php echo base_url(); ?>admin/advertise-with-us" method="GET" autocomplete="off">
                        
                        <input type="text" name="search_term" id="search_term" class="form-control" value="<?php echo!empty($this->input->get('search_term')) ? $this->input->get('search_term') : ''; ?>" placeholder="Search" style="width: 87%; display: inline-block;margin-left: 43px;">
                       
                        <button type="submit" class="btn btn-primary" title="Search" onclick="javascript: form.action='<?php echo base_url('admin/advertise-with-us');?>';" style="position: absolute;top: 0;right: 0;margin-right: 15px;height: 34px;"><i class="fa fa-search"></i></button>

                       <!--  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/subscription');?>';" >Search</button> -->

                     </form>
                  </div>
               </div>


               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="4%">Sr. No.</th>
                        <th width="7%">Name</th>
                        <th width="8%">Contact No.</th>
                        <th width="10%">Email</th>
                        <th width="7%">City</th>
                        <!-- <th width="20%">Description</th> -->
                        <th width="1%">Action</th>
                     </tr>
                  </thead>
                  <tbody>

                    <?php if(!empty($advertiseEnquiryList)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                        foreach ($advertiseEnquiryList as $key => $value) {
                    ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td><?php echo !empty($value['name'])?$value['name']:'' ?></td>
                        <td><?php echo !empty($value['mobile_no'])?$value['mobile_no']:'' ?></td>
                        <td><?php echo !empty($value['email_id'])?$value['email_id']:'' ?></td>
                        <td><?php echo !empty($value['city'])?$value['city']:'' ?></td>
                      <!--   <td>Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.</td> -->
                        <td class="text-center">
                           <!-- <button type="button" class="btn btn-primary btn-xs" title="View" data-toggle="modal" data-target="#viewnewsModal"><i class="fa fa-eye"></i></button> -->
                           <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_dataView(<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>)"><i class="fa fa-eye"></i></button>
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
<!-- Modal -->
<div class="modal fade" id="viewnewsModal" role="dialog" TABINDEX=-1>
   <div class="modal-dialog">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
            <h4 class="modal-title">Advertise With Us</h4>
         </div>
         <div class="modal-body">
            <div class="col-md-6">
                  <label>Name</label> 
                  <h2 class="view-cnt" id="name"></h2>
            </div>
           
               <div class="col-md-6">
                  <label>Contact Number</label>
                  <h2 class="view-cnt" id="mobile_no"></h2>
               </div>
               <div class="col-md-6">
                  <label>Email</label>
                  <h2 class="view-cnt" id="email_id"></h2>
               </div>
               
               <div class="col-md-6">
                  <label>City</label>
                  <h2 class="view-cnt" id="city">Pune</h2>
               </div>
        
            <div class="col-md-12">
               <label>Description</label>
               <h2 class="view-cnt" id="description"></h2>
            </div>
         
      </div>
      <div class="modal-footer">
      </div>
   </div>
</div>
</div>
<!----Modal End---->
<script>
   $(".sportsnewsLi").addClass("active");
   // $("#example").DataTable();

     function get_dataView(id){
      // alert(id);
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";

              $.ajax({
                  type: "get",
                  data: {id: id},
                  url: base_url + "admin/sports-news/Cn_sports_news/view",
                  dataType: 'json',
                                       
                   success: function (data)
                   {
                    // alert(JSON.stringify(data));
                    
                        $("#viewnewsModal").modal('show');
                
                         $("#name").text(data.name);
                         $("#mobile_no").text(data.mobile_no);
                         $("#email_id").text(data.email_id);
                         $("#city").text(data.city);
                         description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                         $("#description").text(description);
                         // if (data.tornamentImage== '') {
                         //     $("#tournament_image").attr("src",'<?php echo base_url()?>AdminMedia/images/default.png');
                         // }else{
                         //    $("#tournament_image").attr("src",'<?php echo base_url()?>uploads/tournaments/images/'+data.tornamentImage);
                         // }
                                                
                    }
               });
        } 
    }
</script>
</body>
</html>