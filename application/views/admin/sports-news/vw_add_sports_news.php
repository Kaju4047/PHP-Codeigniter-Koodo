<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Add Sports News
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/sports-news-list"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
          </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12  no-mob-pad no-pad">
         <div class="box box-primary">
            <div class="box-body">
               <div class="col-md-12 no-pad">
               <div class="col-md-3 form-group">
                  <label>Type</label>
                  <select class="form-control">
                     <option selected disabled>Select Type</option>
                     <option>Cricket</option>
                     <option>Hockey</option>
                  </select>
               </div>
              
                <div class="col-md-6 form-group">
                  <label>News Heading</label>
                  <input type="text" class="form-control">
               </div>
               <div class="clearfix"></div>
                <div class="col-md-9 form-group">
                  <label>Description</label>
                 <textarea class="form-control" id="editor" style="resize: none;height: 110px" name="description" placeholder=""></textarea>
               </div>
               <div class="col-sm-3 form-group">
                  <label>Upload Image</label>
                  <input type="file" class="form-control upld-file">
                  <img src="<?php echo base_url(); ?>AdminMedia/images/default.png" class="img-upload sports-icon">
                  <!-- <span class="img-note">Note: (Upload PNG file & Image size - Width: 100px , Height:100px)</span> -->
               </div>

               <div class="col-md-12 form-group">
                  <button type="submit" class="btn btn-success submit"><i class="fa fa-check-circle"></i> Submit</button>
                  <!-- <a href=""><button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</button></a> -->
               </div>
            </div>
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
<script>
   $(".sportsnewsLi").addClass("active");
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
</script>
<!--start::code for ck editor-->

<script src="<?php echo base_url(); ?>AdminMedia/editor/ckeditor.js"></script>
<script>
                                    CKEDITOR.replace('editor', {
                                        filebrowserUploadUrl: $("#base_url").val() + "admin/cms/Cn_cms/ImageUpload"
                                    });

                                    CKEDITOR.replace('editor');
                                    /*[start:: code to upload local images]*/
                                    var config = {
                                        '.chosen-select': {},
                                        '.chosen-select-deselect': {allow_single_deselect: true},
                                        '.chosen-select-no-single': {disable_search_threshold: 10},
                                        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                                        '.chosen-select-width': {width: "95%"}
                                    }
                                    for (var selector in config) {
                                        $(selector).chosen(config[selector]);
                                    }
                                    /*[end:: code to upload local images]*/
</script>

<!--end::code for ck editor-->
</body>
</html>