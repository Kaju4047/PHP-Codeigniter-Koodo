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
(in_array('system_user', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<style>
   .table td{
   text-align: left;
   }
   .table th{
   text-align: center;
   }
   .table tr td.text-center { /* I don't think they are 0 based */
   text-align: center;
   }
</style>
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         System User List
         <div class="pull-right">
            <a href="<?php echo base_url(); ?>admin/add-sub-user"><button type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add User
            </button></a>
         </div>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 no-mob-pad no-pad">
         <div class="box box-primary">
            <div class="box-body">
               <div class="row">
                  <div class="col-md-6" style="display: inline-block;float: right;margin-bottom: 10px;">
                     <form name="frmSearch" id="frmSearch" action="<?php echo base_url(); ?>admin/sub-user" method="GET" autocomplete="off">
                        
                        <input type="text" name="search_term" id="search_term" class="form-control" value="<?php echo!empty($this->input->get('search_term')) ? $this->input->get('search_term') : ''; ?>" placeholder="Search" style="width: 87%; display: inline-block;margin-left: 43px;">
                       
                        <button type="submit" class="btn btn-primary" title="Search" onclick="javascript: form.action='<?php echo base_url('admin/sub-user');?>';" style="position: absolute;top: 0;right: 0;margin-right: 15px;height: 34px;"><i class="fa fa-search"></i></button>

                       <!--  <button type="submit" class="btn btn-primary filter-btn" onclick="javascript: form.action='<?php echo base_url('admin/subscription');?>';" >Search</button> -->

                     </form>
                  </div>
               </div>
               <div class="table-responsive">
                  <table id="" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th width="8%" class="text-center">Sr. No.</th>
                           <th width="25%">Name</th>
                           <th width="22%">Email</th>
                           <th width="11%">Mobile No.</th>
                           <th width="15%">City</th>
                           <th width="8%" class="text-center">Status</th>
                           <th width="10%" class="text-center">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($cmpList)) {
                               $page_no = !empty($uri_page_no) ? $uri_page_no : '';
                               $i = 1;
                               if (!empty($page_no)) {
                                   $i = ($page_no * 10) - 9;
                               }
                               foreach ($cmpList as $key => $value) {
                                   ?>
                        <tr>
                           <td class="text-center"><?php echo $i; ?> </td>
                           <td><?php echo!empty($value['UA_Name']) ? ucwords($value['UA_Name'])  : ''; ?></td>
                           <td><?php echo!empty($value['UA_email']) ? $value['UA_email'] : ''; ?></td>
                           <td><?php echo!empty($value['UA_mobile']) ? $value['UA_mobile'] : ''; ?></td>
                           <td><?php echo!empty($value['UA_City']) ? ucwords($value['UA_City']) : ''; ?></td>
                           <td class="text-center">
                              <?php if (!empty($value['UA_status'] && $value['UA_status'] == '1')) { ?>
                              <a  onclick="return confirm('Do you really want to Inactive this user ?')"  href="<?php echo base_url(); ?>admin/sub-user-changeStatus/<?php echo!empty($value['UA_pkey']) ? $value['UA_pkey'] : ''; ?>/2"><i class="fa fa-toggle-on tgle-on " aria-hidden="true" title="Active"></i></a>
                              <?php } else { ?>
                              <a  onclick="return confirm('Do you really want to Active this user ?')"  href="<?php echo base_url(); ?>admin/sub-user-changeStatus/<?php echo!empty($value['UA_pkey']) ? $value['UA_pkey'] : ''; ?>/1"><i class="fa fa-toggle-on tgle-off fa-rotate-180" aria-hidden="true" title="Inactive"></i></a>
                              <?php } ?>
                           </td>
                           <td class="text-center">
                              <a href="<?php echo base_url(); ?>admin/view-sub-user/<?php echo!empty($value['UA_pkey']) ? $value['UA_pkey'] : ''; ?>"><button type="button" class="btn btn-primary btn-xs" title="View"><i class="fa fa-eye"></i></button></a>

                              <a href="<?php echo base_url(); ?>admin/edit-sub-user/<?php echo!empty($value['UA_pkey']) ? $value['UA_pkey'] : ''; ?>"><button type="button" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></button></a>

                              <a onclick="return confirm('Do you really want to Delete this user ?')" href="<?php echo base_url(); ?>admin/sub-user-delete/<?php echo!empty($value['UA_pkey']) ? $value['UA_pkey'] : ''; ?><?php echo!empty($page_no) ? '/' . $page_no : ''; ?>"><button type="button" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button></a>
                           </td>
                        </tr>
                        <?php
                           $i++;
                           }
                           } else {
                           ?>
                        <tr>
                           <td colspan="7">No Data Available.</td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
                  <ul class="pagination pull-right">
                     <?php if (isset($cmpList_links) && !empty($cmpList_links)) { ?>
                     <li class="page-item"><?php echo $cmpList_links ?></li>
                     <?php } ?>
                  </ul>
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
<script type="text/javascript">
   $(".sysLi").addClass("active");
   $('#example').DataTable();
</script>
</body>
</html>