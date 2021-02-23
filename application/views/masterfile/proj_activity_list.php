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
                                    <li><span class="bread-blod">Project / Activity List</span>
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
                                <h1>Project / Activity List</h1>
                                <div class="sparkline8-outline-icon">
                                    <a class="btn btn-custon-three btn-primary" href="#" data-toggle="modal" data-target="#PrimaryModalhdbgcl">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Add Project / Activity
                                    </a>
                                    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header header-color-modal bg-color-1">
                                                    <h4 class="modal-title">Add New Project Activity</h4>
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                                <form method='POST' action='<?php echo base_url(); ?>index.php/masterfile/insert_proj_activity'>
                                                    <div class="modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Project Activity:</p>
                                                            <input type="text" name="proj_activity" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Status:</p>
                                                            <select type="text" name="status" class="form-control">
                                                                <option value = "">--Select Status--</option>
                                                                <option value = "Active">Active</option>
                                                                <option value = "Inactive">Inactive</option>
                                                            </select>
                                                        </div>
                                                        <center>
                                                            <input type="submit" class="btn btn-custon-three btn-primary btn-block" value="Save">
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
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Project / Activity</th>
                                            <th>Status</th>
                                            <th><center>Action</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($proj_act AS $pa){ ?>
                                        <tr>
                                            <td><?php echo $pa->proj_activity; ?></td>
                                            <td><?php echo $pa->status; ?></td>
                                            <td>
                                                <center>
                                                    <a onclick="updateProjAct('<?php echo base_url(); ?>','<?php echo $pa->proj_act_id; ?>')" class="btn btn-custon-three btn-info btn-xs">
                                                        <span class="fa fa-pencil"></span>
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