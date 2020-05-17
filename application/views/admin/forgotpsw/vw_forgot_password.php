<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= SITE_TITLE ?>-Forgot Password</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/font-awesome.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/ionicons.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/AdminLTE.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/plugins/iCheck/square/blue.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/notificationMsg.css">
        <style>
            
             a:hover, a:active, a:focus {
                color: #c54842 !important;
            }
            a {
                color:#c31919!important;
            }
           
            .btn-block{
                border-color: #a51010!important;
                background-color: #c31919!important;
            }
        </style>
    </head>
    <body class="hold-transition forget-page" >
        <div class="forget-box">

            <!-- <h2 class="text-center"><?= SITE_TITLE ?></h2> -->

            <div class="forget-box-body">
                <div class="login-logo">
                    <!--  <h2>OFFER MANAGEMENT</h2> -->
                     <!-- <p style="color:white;"><b>IDOT</b></p> -->
                     <div style="text-align: center;margin-bottom: 15px;">
                     <img src="<?php echo base_url(); ?>AdminMedia/images/logo.png" width="auto" height="90">
                 </div>
                </div>
                <p class="forget-box-msg pad">Enter your email address that you used to register. We'll send you an email with your username and a link to reset your password.</p>
                <form name="frmForgotPwd" id="frmForgotPwd" class="forget-form" action="<?php echo base_url(); ?>admin/forget-password-action" method="post" >

                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Email" name="email">
                        <span class="fa fa-envelope form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-danger btn-block btn-flat" style="margin-bottom:20px;">Reset My Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="text-center">
                    <a href="<?php echo base_url(); ?>admin/login" class="text-center" style="margin-bottom:20px;">Sign In ! </a>
                </div>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
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
                    <a href="#" id="close_cross" class="close" aria-label="close" style="text-decoration: none;position: absolute;top: 1px;right: 6px;opacity: 0.4;">&times;</a>
                    <strong><?php echo $hint_text; ?> !</strong> <?php echo $msg; ?>
                </div>
            </div>

        </div>
        <div class="clearfix"></div>
        <!-- jQuery 2.2.3 -->
        <script type="text/javascript" src="<?php echo base_url('AdminMedia/js/jquery-2.2.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('AdminMedia/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/js_common_validations.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/notificationMsg.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_forgot_pwd/js_forgot_pwd.js"></script>
    </body>
</html>