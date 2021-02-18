<!DOCTYPE html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Procurement System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/message/logo4.ico">
       <!--  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/googleapis.css"> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login-css.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
       <!--  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminpro-custon-icon.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/meanmenu.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.min.css"> -->
     <!--    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/normalize.css"> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/form.css">
       <!--  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style.css"> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
      <!--   <script src="<?php echo base_url(); ?>assets/js/vendor/modernizr-2.8.3.min.js"></script> -->

    </head>
    <body style="padding-top: 100px">
        <div class="login-form-area mg-t-30 mg-b-40">
            <div class="container-fluid">
                <div class="m-t-200"></div>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <form method="POST" action="<?php echo base_url(); ?>masterfile/loginprocess" id="adminpro-form" class="adminpro-form">
                        <div class="col-lg-4">
                            <div class="login-bg">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="logo">
                                            <a href="#"><img src="<?php echo base_url(); ?>assets/img/logo/logo.png" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $error_msg= $this->session->flashdata('error_msg');  
                                    if($error_msg){
                                ?>
                                    <div class="alert bor-radius10 shadow alert-danger alert-shake animated headShake">
                                        <center><?php echo $error_msg; ?></center>                    
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="login-input-head">
                                            <p>Username</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="login-input-area">
                                            <input type="text" name="username" />
                                            <i class="fa fa-user login-user" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="login-input-head">
                                            <p>Password</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="login-input-area">
                                            <input type="password" name="password" />
                                            <i class="fa fa-lock login-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="login-button-pro">
                                            <!-- <button type="submit" class="login-button login-button-lg btn-block">Log in</button> -->
                                            <button class="login-button login-button-lg btn-block" type = "submit">Login</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-lg-4"></div>
                </div>
            </div>
        </div>     
    </body>