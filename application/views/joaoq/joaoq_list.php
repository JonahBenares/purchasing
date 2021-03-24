    <script src="<?php echo base_url(); ?>assets/js/aoq.js"></script> 
    <div id="cancelAOQ" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel AOQ</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>aoq/cancel_aoq">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling AOQ:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='aoq_id' name='aoq_id' >        
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                                    <li><span class="bread-blod">JO AOQ List</span>
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
         <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd" style="padding-bottom: 0px">
                            <div class="main-sparkline8-hd" >
                                <h1>JO AOQ List</h1>
                                <small>JOB ORDER - ABSTRACT OF QUOTATION</small> 
                                <div class="sparkline8-outline-icon">
                                <a href="<?php echo base_url(); ?>joaoq/joaoq_served" class="btn btn-custon-three btn-success" ><span class="fa fa-archive p-l-0"></span> Served AOQ</a>
                                <a href="<?php echo base_url(); ?>joaoq/joaoq_cancelled" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled AOQ</a>
                                  <!--   <a class="btn btn-custon-three btn-primary" href=">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Create AOQ
                                    </a> -->
                                </div>
                            </div>
                        </div>
                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>AOQ Date</th>
                                            <th>JOR #</th>
                                            <th>Supplier</th>
                                            <th>Department</th>
                                            <th>Enduse</th>
                                            <th>Requestor</th>
                                            <th width="1%">Status</th>
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
                                            <td>
                                                <span class='label label-warning'> For TE </span>
                                                <span class='label label-success'>Awarded</span>
                                                <span class='label label-warning'>Draft</span>
                                                <span class='label label-primary'> Refer To Manila </span>
                                            </td>
                                            <td>
                                                <center>
                                                    <!-- for three vendors -->
                                                    <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt/" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a>

                                                    <!-- for three vendors -->
                                                    <!-- <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt_four" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a> -->

                                                    <!-- for three vendors -->
                                                    <!-- <a href="<?php echo base_url(); ?>joaoq/joaoq_prnt_five/" target = "_blank" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a> -->

                                                    <a href="<?php echo base_url(); ?>joaoq/refer_mnl/" class="btn btn-custon-three btn-primary btn-xs"  onclick="return confirm('Are you sure?')" title="Refer To MNL">
                                                        <span class="fa fa-location-arrow"></span>
                                                    </a>

                                                    <a href="<?php echo base_url(); ?>joaoq/update_served/" class="btn btn-custon-three btn-success btn-xs"  onclick="return confirm('Are you sure?')" title="Served">
                                                        <span class="fa fa-archive"></span>
                                                    </a>

                                                    <a class="cancelAOQ btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelAOQ" data-id=" "><span class="fa fa-ban" title="Cancel"></span></a>
                                                </center>
                                            </td>
                                        </tr>                      
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
                 </form>
            </div>
        </div>
    </div>
    <!-- Data table area End-->