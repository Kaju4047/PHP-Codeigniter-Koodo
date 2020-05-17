<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>Post Report List 
    <div class="pull-right">
           <a href="javascript:history.go(-1)"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a> 
         </div></h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="col-md-12  no-mob-pad no-pad">
      <div class="box box-primary no-height">
        <div class="box-body no-height" style="margin-bottom: 10px;">
          <form id="filter"  method='get'  enctype="multipart/form-data"> 
            <div class="row">
              <div class="col-md-3 form-group">
                <label>From Date</label>
                <div class="input-group date" data-date-format="dd.mm.yyyy">
                  <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($fromdatefilter) ? date('d-m-Y',strtotime($fromdatefilter)) : '';?>" autocomplete="off">
                  <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 form-group">
                <label>To Date </label>
                <div class="input-group date" data-date-format="dd.mm.yyyy">
                  <input type="text" id="todate" name="todate" class="form-control" placeholder="dd-mm-yyyy" value="<?php echo !empty($todatefilter) ? date('d-m-Y',strtotime($todatefilter)) : '';?>" autocomplete="off">
                  <div class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                  </div>
              </div>             
             
               <div class="col-md-3 form-group">
                  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/report-users');?>';"><i class="fa fa-filter"></i> Filter</button>
               </div>

                  <div class="col-md-3 form-group">

                 <?php if(!empty($postreport)){ ?>          
                     <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/report-post-export-to-excel');?>';"><i class="fa fa-report" aria-hidden="true"></i> Export to Excel</button>
                      <?php  } ?>
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
                        <th width="6%">Sr. No.</th>
                        <th width="10%">Reporter Name</th>
                        <th width="10%">Post User Name</th>
                        <th width="30%">Post</th>
                        <th width="5%">Remove post of User For Reporter</th>
                        <th width="7%">Report Date</th>
                        <th width="5%">Status</th>
                        <th style="text-align: center !important;" width="5%">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(!empty($postreport)){
                       $page_no= !empty($this->uri->segment(3)) ? $this->uri->segment(3): 1;
                            $i = ($page_no * 10) - 9;
                             foreach ($postreport as $key => $value){?>
                     <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td><?php  echo !empty($value['givenby']) ? ucwords($value['givenby']) : '-';?></td>                      
                        <td><?php  echo !empty($value['forname']) ? ucwords($value['forname']) : '-';?></td>
                        <td class="text-center">
                          <?php $imgdata = !empty($value['image']) ? 'uploads/sportbook/post/'.$value['image'] : ''; 
                          if (!empty($imgdata)) {                        
                          ?>
                          <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" width="20%" height="50"></br>
                        <?php } ?>
                          <?php  echo !empty($value['text']) ? ucfirst($value['text']) : '';?>
                        </td>
                        <td>  <input style="font: center" type="checkbox" name="remove_post" class="remove_post"  <?php echo ((!empty($value['remove_post_of_report']) && $value['remove_post_of_report'] == '1') ? 'checked' : 'unchecked'); ?> attrreporter="<?php echo $value['fk_uid_given_by']?>" attrpostuser="<?php echo $value['fk_uid_for']?>" value="<?php echo $value['remove_post_of_report']?>">
                        </td>
                        <td><?php  echo !empty($value['createdDate']) ? date('d-m-Y',strtotime($value['createdDate'])) : '-';?></td>
                     
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
                           
                            <a onClick="return confirm('Are you sure you want to change status of this record ?')"  href="<?php echo base_url(); ?>admin/sportbook-report-status/<?php echo (!empty($value['fk_post_id']) ? $value['fk_post_id'] : ''); ?>/<?php echo (!empty($status) ? $status : ''); ?>"> <i class="<?php echo $class; ?>" aria-hidden="true" title="<?php echo $title; ?>"></i></a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs" title="View" onclick="get_dataView(<?php echo (!empty($value['pk_id']) ? $value['pk_id'] : ''); ?>)"><i class="fa fa-eye"></i></button>

                           <a href="<?php echo base_url(); ?>admin/delete-report-post/<?php echo (!empty($value['fk_post_id']) ? $value['fk_post_id'] : ''); ?>"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
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
  <div class="modal fade" id="viewprodModal" role="dialog" TABINDEX=-1>
    <div class="modal-dialog" style="min-width: 700px;">
    
      <!-- Payment Modal start-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title">Report</h4>
        </div>
        <div class="modal-body">
<!--          <div class="col-md-9 no-pad"> -->
          
          <div class="col-md-12">
            <label>Description</label>
            <h2 class="view-cnt" id="description"></h2>
         </div>
        </div>
     </div>
        <div class="modal-footer">
         </div>
      </div>
      
    </div>
  </div>
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script>
    $(".sportsbookLi").addClass("active");
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
                  url: base_url + "admin/sports-book/Cn_sports_book/reportView",
                  dataType: 'json',
                                       
                   success: function (data)
                   {
                    // alert(JSON.stringify(data));
                    
                        $("#viewprodModal").modal('show');
                         description=  (data.description).substr(0,1).toUpperCase()+(data.description).substr(1);
                         $("#description").text(description);
                        
                                                
                    }
               });
        } 
    }

    //  Remove post of User For Reporter

    $('.remove_post').click(function() {
         var attrpostuser =$(this).attr("attrpostuser");
         var attrreporter =$(this).attr("attrreporter");
         var value = $(this).val();
          var base_url = "<?php echo base_url(); ?>";
         // alert(attrpostuser);
         // alert(attrreporter);
         if (value==1) {
          var check_val=2;
         }else
         if(value==2){
            var check_val=1;
         }
         // alert(check_val);
         $.ajax({
                  type: "get",
                  data: {attrpostuser: attrpostuser,attrreporter:attrreporter,check_val:check_val },
                  url: base_url + "admin/sports-book/Cn_sports_book/removePostStatus",
                  dataType: 'json',
                                       
                   success: function (data)
                   {                                               
                    }
               });

    });


</script>
<!-- <script>
function myPrintFunction() {

  var css = '@page { size: landscape; margin: 0mm;}',
    head = document.head || document.getElementsByTagName('print_page')[0],
    style = document.createElement('style');

style.type = 'text/css';
style.media = 'print';

if (style.styleSheet){
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));
}

head.appendChild(style);


  window.print();
}
</script> -->
</body>
</html>