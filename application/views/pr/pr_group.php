    <div class="breadcome-area mg-b-30 small-dn">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="breadcome-heading">
                                    <form role="search" class="">
                                        <input type="text" placeholder="Search..." class="form-control">
                                        <a href=""><i class="fa fa-search"></i></a>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <ul class="breadcome-menu">
                                    <li><a href="<?php echo base_url(); ?>index.php/masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/pr/pr_list">PR List</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/pr/purchase_request">Purchase Request</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">PR Group</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <form>
                                    <h4>PR no: <b><?php echo $pr_no; ?></b></h4>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="15%"><a href="" ></a>

                                                <li class="dropdown" style="list-style:none;margin:0px;width:100%"> 
                                                    <a style="width:100%;text-align: left; letter-spacing: 1px;font-size: 13px; font-weight: 700" data-toggle="dropdown" href="#">
                                                        <h3 class="m-b-0"><b>Group 1</b></h3>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-alerts animated fadeInLeft" style="width:350px;top:30px;border:1px solid #ffb17f;left:0px;">
                                                        <span class="arrow-top2"></span>
                                                        <li style="padding:5px">
                                                            <b>Item List</b>
                                                            <table class="table table-hover table-bordered" style="margin:0px">
                                                                <tr>
                                                                    <td width="1%"><strong>1</strong></td>
                                                                    <td>
                                                                        <label style="color:#555;font-weight: 600"></label >
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>2</strong></td>
                                                                    <td>
                                                                        <label style="color:#555;font-weight: 600"></label >
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>3</strong></td>
                                                                    <td>
                                                                        <label style="color:#555;font-weight: 600"></label >
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </td>
                                            <td width="75%">
                                                - Arising Builders Hardware and Construction Supply<br>
                                                - CJ KARR Industrial Sales And Service<br>
                                                - Compresstech Resources, Inc.<br>
                                                - Dawson Technology PTY LTD.<br>
                                            </td>
                                            <td width="10%"><a href="" onclick="choose_vendor('<?php echo base_url(); ?>')" class="btn btn-warning btn-md btn-block">Choose Vendor</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td width="15%"><a href=""><h3 class="m-b-0"><b>Group 2</b></h3></a></td>
                                            <td width="75%">
                                                - First Pilipinas Power and Automation, Inc<br>
                                                - Gini GTB Industrial Network Inc./AsiaPhil<br>
                                                - Hardware and Industrial Solutions Incorporated<br>
                                            </td>
                                            <td width="10%"><a href="" onclick="choose_vendor('<?php echo base_url(); ?>')" class="btn btn-warning btn-md btn-block">Choose Vendor</a></td>
                                        </tr>
                                    </table>
                                    <center><a href="<?php echo base_url(); ?>index.php/rfq/rfq_list" class="btn btn-primary btn-md p-l-100 p-r-100">Proceed</a></center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->