<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <title><?= SITE_TITLE ?>-Login</title> -->
        <title>Koodo Login</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/font-awesome.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/ionicons.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/AdminLTE.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('AdminMedia/css/plugins/iCheck/square/blue.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/notificationMsg.css">
        <style type="text/css">
         
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
    <body class="hold-transition login-page" >


        <div class="login-box  " >
            <!-- <h2 class="text-center"><?= SITE_TITLE ?></h2> -->
            <!-- /.login-logo -->
            <div class="login-box-body">

                <!--  <div class="login-logo">

                   <p style="color:white;"><b>IDOT</b></p>
               </div> -->
                <div style="text-align: center;margin-bottom: 15px;">
                     <img src="<?php echo base_url(); ?>AdminMedia/images/logo.png" width="auto" height="90">
                 </div>

                <p class="login-box-msg">Admin Login</p>
                <form class="login-form" id="frmLogin" action="<?php echo base_url(); ?>admin/login-action" method="post">


                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Username"  autocomplete="off"  name="txtUserName" value="<?php echo (!empty($_COOKIE['cok_Email'])) ? $_COOKIE['cok_Email'] : ''; ?>" >
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control"autocomplete="off" placeholder="Password" name="txtPassword" minlength="6" maxlength="20" value="<?php echo (!empty($_COOKIE['cok_Password'])) ? $_COOKIE['cok_Password'] : ''; ?>">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember" value="yes" <?php echo (!empty($_COOKIE['cok_Email']) && !empty($_COOKIE['cok_Password'])) ? 'checked="" ' : ''; ?> >Remember Me</label>
                            </div>


                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <a href="<?php echo base_url(); ?>admin/forgot" style="float:right;margin-bottom:20px;margin-top: 10px;">I forgot my password</a>

                        </div>
                        <!-- /.col -->
                    </div>
                    <button type="submit" class="btn btn-danger btn-block btn-flat submit" style="margin-bottom:20px;">Sign In</button>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
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
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script type="text/javascript" src="<?php echo base_url('AdminMedia/js/jquery-2.2.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('AdminMedia/js/bootstrap.min.js'); ?>"></script>
        <!--[start::jQuery Validation files]-->
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/js_common_validations.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/JqueryValidation/notificationMsg.js"></script>
        <!--[end::jQuery Validation files]-->
        <script type="text/javascript" src="<?php echo base_url(); ?>AdminMedia/validations/js_login/js_login.js"></script>

    </body>
</html>
