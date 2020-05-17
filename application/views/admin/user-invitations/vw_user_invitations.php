<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->

<div class="content-wrapper">
   <section class="content-header">
      <h1>User Invitation List </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary no-height">

           <form id="filter"  method='get'  enctype="multipart/form-data"> 
         
            <div class="box-body no-height" style="margin-bottom: 10px;">
               <div class="row">
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
                     <label>To Date </label>
                     <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" autocomplete="off">
                        <div class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                  </div>
                 <!--  <div class="col-md-2 form-group">
                     <label>User Type</label>
                     <select class="form-control" name="type">
                        <option value="">Select User Type</option>
                        <?php  if(!empty($usertypeDetails)){
                               foreach ($usertypeDetails as $key => $value) {
                        ?>
                         <option value="<?php echo !empty($value['pk_id']) ?$value['pk_id'] : '';?>"<?php echo( (!empty($type) && $type==$value['pk_id'])?'selected' : '') ?>><?php echo !empty($value['usertype']) ?$value['usertype'] : '';?></option>

                        <?php }}?>
                     </select>
                  </div>
                  <div class="col-md-2 form-group">
                     <label>Approved</label>
                     <select class="form-control" name="approved">
                        <option value="">Select</option>
                        <option value="1"<?php echo( (!empty($approved) && $approved==1)?'selected' : '') ?>>Yes</option>
                        <option value="2"<?php echo( (!empty($approved) && $approved==2)?'selected' : '') ?>>No</option>
                     </select>
                  </div> -->
                  <div class="col-md-2 form-group">
                     
                    <!--  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/filter-review');?>';"><i class="fa fa-filter"></i>Filter</button> -->
                     <button type="submit" class="btn btn-primary filter-btn"  onclick="javascript: form.action='<?php echo base_url('admin/filter-review');?>';"><i class="fa fa-filter"></i>  Filter</button>
                  </div>
                  <div class="col-md-2 form-group">
                    <?php if(!empty($reviewDetails)){
                    ?>
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/review-export-to-excel');?>';" >Export to Excel</button>
                   <?php }?>
                  </div>
               </div>
            </div>
         </form>
         </div>
         <div class="box box-primary">
            <div class="box-body">
               <table id="example" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                     <tr>
                        <th width="8%">Sr. No.</th>
                        <th width="20%">Invitation Date</th>
                        <th width="20%">Invitation From</th>
                        <th width="20%">Invitation To</th>
                        <th width="20%">Status</th>
                        <th style="text-align: center !important;" width="10%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (!empty($reviewDetails)) {
                             // echo "<pre>"; print_r($reviewDetails);
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                             
                            $i = ($page_no * 10) - 9;
                             foreach ($reviewDetails as $key => $value){
                             ?>
                     <tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td><?php  echo !empty($value['createdDate']) ? date('d-m-Y',strtotime($value['createdDate'])) : '';?></td>
                        <td>
                        <?php  echo !empty($value['usertype']) ? ucfirst($value['usertype']) : '';
                           ?>
                        <!-- <?php if(!empty($value[0])){
                                foreach ($value[0] as $key => $val) {
                        ?> -->

                    <!--   <?php }
                    }?> -->
                      </td>
                        <td><?php  echo !empty($value['forName']) ? ucwords($value['forName']) : '';?></td>
                        <td><?php  echo !empty($value['givenName']) ? ucwords($value['givenName'])  : '';?></td>
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
                                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/review-status/<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs" onclick="reviewData(<?php echo $value['pk_id']; ?>)" title="View"><i class="fa fa-eye"></i></button>
                          <!--  <button type="button" class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#viewreveiwsModal" title="View"><i class="fa fa-eye"></i></button> -->
                        </td>
                     </tr>
                  <?php $i++;}}?>
                  </tbody>
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
<div class="modal fade" id="viewreveiwsModal" role="dialog">
   <div class="modal-dialog" style="min-width: 600px;">
      <!-- Payment Modal start-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
            <h4 class="modal-title">User Reviews Details</h4>
         </div>
         <div class="modal-body">
            <div class="col-md-12 no-pad">
               <div class="str">
                  <!-- <i id="star" class="fa fa-star rvw-str"></i> -->
                  <i id="star"></i>
                  <p id="rate"></p> 
                 <!--  <small style="margin: 10px 0 0 5px;">(<div id="count"></div>Reviews)</small> -->
               </div>
            </div>
            <div class="col-md-4 no-pad">
               <label>User Type</label>
               <h2 class="view-cnt" id="type"></h2>
            </div>
            <div class="col-md-4 no-pad">
               <label>Review For</label>
               <h2 class="view-cnt" id="forName"></h2>
            </div>
            <div class="col-md-4 no-pad">
               <label>Review Given By</label>
               <h2 class="view-cnt" id="givenName"></h2>
            </div>
            <div class="col-md-12 no-pad">
               <label>Feedback</label>
               <h2 class="view-cnt" id="feedback"></h2>
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
    $(".userevwsLi").addClass("active");
    // $("#example").DataTable();
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

    $('#fromdate').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: nowDate
    }).on('changeDate', function (selected){
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

    function reviewData(id) {
        var html="";
        if (id != '') {
            var base_url = "<?php echo base_url(); ?>";
            $.ajax({
                type: "post",
                data: {id: id},
                url: base_url + "admin/user-reviews/Cn_user_reviews/view",
                dataType: 'json',
                                       
                success: function (data){                
                    $("#viewreveiwsModal").modal('show'); 
                    $("#type").html(data.usertype);
                    $("#forName").html(data.forName);
                    $("#givenName").html(data.givenName);
                    $("#feedback").html(data.feedback);
                    $("#rate").html(data.rate);
                  
                   
                    if(data.rate !=0){
                        var rating = (data.rate);
                        html+= '<ul class="star1">'; 
                        var star_array= new Array();
                        for (var i =0; i < 5; i++) { 
                            var ival=i+0.5; 
                            if (ival == rating){
                                  star_array.push('fa fa-star-half-o');   
                            }else
                            if(ival<=rating){
                                star_array.push('fa fa-star');
                            } else{
                                star_array.push('blank'); 
                            }
                        }

                        for (var j=0; j < star_array.length ; j++){ 
                            if (star_array[j]=='blank') {
                                var font_class='fa fa-star';
                            }
                            else{
                                var font_class=star_array[j];
                            }
                             
                            if(star_array[j] =='blank'){
                                var font_color = 'gray';
                            }else{
                               var font_color = '#F88715';
                            }
                            html+= '<span class="'+font_class+'" style="color:'+font_color+'">';
                            html+= '</span>';                                  
                        }  

                        html+= '</ul>';     
                    }
                    $("#star").html(html); 
                }
            });
        }else{
            $("#type").html("");
            $("#forName").html("");
            $("#givenName").html("");
            $("#feedback").html("");
            $("#rate").html("");
        }
    }
</script>

</body>
</html>