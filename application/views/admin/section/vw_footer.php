
<div class="msg_div">
    <?php
    $msg = '';
    $error_class = 'alert-success';
    $hint_text = 'Success';
    if ($this->session->flashdata("success") != "") {
        $msg = $this->session->flashdata("success");
        $error_class = 'alert-success';
        $hint_text = 'Success';
    } else if ($this->session->flashdata("error") != "" || (validation_errors() != "")) {
        $msg = ($this->session->flashdata("error") ? $this->session->flashdata("error") : validation_errors());
        $error_class = 'alert-danger';
        $hint_text = 'Error';
    }
    ?>
    <div class="err-msg2" style="position: absolute;right: : 5px;bottom: 1px;z-index: 1; <?php echo (!empty($msg) ? 'display:block;' : 'display:none;'); ?>">
        <div class="alert <?php echo $error_class; ?>"  >
            <a href="#" class="close" aria-label="close" style="text-decoration: none;position: absolute;top: 1px;right: 6px;opacity: 0.4;">&times;</a>
            <strong><?php echo $hint_text; ?> !</strong> <?php echo $msg; ?>
        </div>
    </div>

</div>
<div class="clearfix"></div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <label>Design By- </label> <a href="http://mplussoft.com/" target="_blank">Mplussoft</a>
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">Koodo</a>.</strong> All rights reserved.
</footer>

</div>  <!-- End wrapper -->

<input type="hidden" id="base_url" value="<?= base_url(); ?>">
<!-- Jquery Scripts -->
<script type="text/javascript" src=" <?php echo base_url('AdminMedia/js/jquery-2.2.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/multiple-select.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/app.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/chosen.jquery.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('AdminMedia/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('AdminMedia/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/plugins/datatables/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/plugins/datatables/jszip.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('AdminMedia/plugins/datatables/buttons.html5.min.js'); ?>"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script> -->

<!-- daterangepicker -->
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/moment.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/select2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/bootstrap-timepicker.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('AdminMedia/js/jquery.sumoselect.min.js'); ?>"></script>


<!--[start::jQuery Validation files]-->
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/js_common_validations.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/notificationMsg.js"></script>
<!--[end::jQuery Validation files]-->






<script>
    $(function () {
        $("#example1").DataTable();
        $("#example2").DataTable();
        $("#example3").DataTable();
        $("#example4").DataTable();
        $("#example5").DataTable();
        $("#example6").DataTable();
        $("#example7").DataTable();

        /*$(".select2").select2();*/
    });
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        // todayBtn: true,
        todayHighlight: true
    });
    // var config = {
    //     '.chosen-select': {},
    //     '.chosen-select-deselect': {allow_single_deselect: true},
    //     '.chosen-select-no-single': {disable_search_threshold: 10},
    //     '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
    //     '.chosen-select-width': {width: "95%"}
    // }
    // for (var selector in config) {
    //     $(selector).chosen(config[selector]);
    // }


    // $(document).ready(function () {
    //     $('input[type="checkbox"]').click(function () {
    //         var inputValue = $(this).attr("value");
    //         $("." + inputValue).toggle();
    //     });
    // });
    // $(function () {
    //     $('#ms').change(function () {
    //         console.log($(this).val());
    //     }).multipleSelect({
    //         width: '100%'
    //     });
    // });
</script>