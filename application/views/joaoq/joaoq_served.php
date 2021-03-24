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
                                    <li><a href="<?php echo base_url(); ?>">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>joaoq/joaoq_list">JO AOQ List</a> <span class="bread-slash">/</span></li>
                                    <li><span class="bread-blod">JO AOQ List</span></li>
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
            <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd" style=" background: #44c372">
                                <div class="main-sparkline8-hd" >
                                    <h1 class="text-white">SERVED JO AOQ List</h1>
                                    <small class="text-white">JOB ORDER - ABSTRACT OF QUOTATION</small> 
                                    <div class="sparkline8-outline-icon">
                                    <h2><span class="fa fa-archive"></span></h2>
                                    </div>
                                </div>
                            </div>                       
                            <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
                                        <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                            <thead>
                                                <tr>
                                                    <th>AOQ Date</th>
                                                    <th>PR#</th>
                                                    <th>Supplier</th>
                                                    <th>Department</th>
                                                    <th>Enduse</th>
                                                    <th>Requestor</th>
                                                    <th>Cancel Date</th>
                                                    <th>Cancel Reason</th>
                                                    <th><center><span class="fa fa-bars"></span></center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <center>
                                                            <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt/" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                                <span class="fa fa-eye"></span>
                                                            </a>

                                                           <!--  <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt_four/" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                                <span class="fa fa-eye"></span>
                                                            </a>

                                                            <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt_five/" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                                <span class="fa fa-eye"></span>
                                                            </a> -->

                                                            <a href="<?php echo base_url(); ?>joaoq/refer_mnl/" class="btn btn-custon-three btn-primary btn-xs"  onclick="return confirm('Are you sure?')" title="Refer To MNL"><span class="fa fa-location-arrow"></span>
                                                            </a>
                                                        </center>
                                                    </td>
                                                </tr>                        
                                            </tbody>
                                        </table>
                                    </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Data table area End-->