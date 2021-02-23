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
                                    <li><span class="bread-blod">Employee List</span>
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
                                <h1>Employee List</h1>
                                <div class="sparkline8-outline-icon">
                                    <a class="btn btn-custon-three btn-primary" href="#" data-toggle="modal" data-target="#PrimaryModalhdbgcl">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Add Employee
                                    </a>
                                    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header header-color-modal bg-color-1">
                                                    <h4 class="modal-title">Add New Employee</h4>
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/insert_emp">
                                                    <div class="modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Employee Name:</p>
                                                            <input type = "text" name="emp_name" class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Department:</p>
                                                            <select name="dept" class="form-control" cols="2">
                                                                <option value = "">--Select Department--</option>
                                                                <?php foreach($department AS $d){ ?>
                                                                <option value = "<?php echo $d->department_id; ?>"><?php echo $d->department_name?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Position:</p>
                                                            <input type="text" name="position" class="form-control">
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <p class="m-b-0">Location:</p>
                                                            <input type="text" name="location" class="form-control">
                                                        </div> -->
                                                        <center>
                                                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                                                        </center>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th>Employee</th>
                                            <th>Department</th>
                                            <th>Position</th>
                                            <th><center>Action</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($employees AS $emp){ ?>
                                        <tr>
                                            <td></td>
                                            <td><?php echo $emp['employee'];?></td>
                                            <td><?php echo $emp['department'];?></td>
                                            <td><?php echo $emp['position'];?></td>
                                            <td>
                                                <center>
                                                    <a <?php echo base_url(); ?>index.php/vendors/update_proj_activity/<?php echo $pa['vendor_id'];?>" class="btn btn-custon-three btn-info btn-xs">
                                                        <span class="fa fa-pencil"></span>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>index.php/masterfile/delete_employee/<?php echo $emp['emp_id'];?>" class="btn btn-custon-three btn-danger btn-xs" onclick="confirmationDelete(this);return false;">
                                                        <span class="fa fa-times"></span>
                                                    </a>
                                                </center>
                                            </td>
                                        </tr> 
                                    <?php } ?>                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->