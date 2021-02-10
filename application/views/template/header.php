<!DOCTYPE html>
<?php
    if (isset($this->session->userdata['logged_in'])) {
        $username = ($this->session->userdata['logged_in']['username']);
        $password = ($this->session->userdata['logged_in']['password']);
    } else {
        echo "<script>alert('You are not logged in. Please login to continue.'); 
            window.location ='".base_url()."masterfile/index'; </script>";
    }
?>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Procurement System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon
    		============================================ -->
            
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/message/logo4.ico">
        <!-- Google Fonts
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.dataTables.min.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/googleapis.css">
        <!-- Bootstrap CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <!-- Bootstrap CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
        <!-- adminpro icon CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminpro-custon-icon.css">
        <!-- meanmenu icon CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/meanmenu.min.css">
        <!-- animate CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
        <!-- mCustomScrollbar CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.min.css">
        <!-- data-table CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/data-table/bootstrap-table.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/data-table/bootstrap-editable.css">
        <!-- normalize CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/normalize.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/buttons.css">

        <!-- charts C3 CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/c3.min.css">
        <!-- forms CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/form/all-type-forms.css">
        <!-- style CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <!-- responsive CSS
    		============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/modals.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css" />
        <script src="<?php echo base_url(); ?>assets/js/vendor/jquery-1.11.3.min.js"></script>
        <!-- modernizr JS
    		============================================ -->
       <!--  <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.8.3.min.js"></script> -->
    </head>
    <body class="materialdesign">
        <div class="wrapper-pro">