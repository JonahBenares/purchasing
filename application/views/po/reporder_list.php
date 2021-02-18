    <script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
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
                                    <li><span class="bread-blod">REPEAT ORDER List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addPO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add Repeat Order</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>index.php/po/create_reorderpo">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Date:</p>
                            <input type="date" name="po_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">PO NO:</p>
                            <input type="text" name="po_no" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <select name="supplier" id='supplier' onchange="chooseSupplier()" class="form-control">
                            <option value='' selected>-Choose Supplier/Vendor-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Address:</p>
                            <b><span id='address'></span></b>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Contact Person:</p>
                            <b><span id='contact'></span></b>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Telephone #:</p>
                            <b><span id='phone'></span></b>
                        </div>
                         <div class="form-group">
                            <p class="m-b-0">Notes:</p>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                        <center>
                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                            <a href="<?php echo base_url(); ?>index.php/po/reporder_prnt" class="dropdown-item">Repeat Order</a>
                        </center>
                    </div>
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                </form>
            </div>
        </div>
    </div>
    <div id="cancelPO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel PO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>po/cancel_po">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling PO:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='po_id' name='po_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <div id="cancelDuplicatePO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel and Duplicate PO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>po/cancel_and_duplicate">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling PO:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='po_id' name='po_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
         <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>REPEAT ORDER List</h1>
                                <!-- <small>PURCHASE ORDER</small> -->
                                <div class="sparkline8-outline-icon">
                                <input type='button' class="btn btn-custon-three btn-primary" value='Add Repeat Order'  data-toggle="modal" data-target="#addPO"> 
                                </div>
                            </div>
                        </div>
                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>PO Date</th>
                                            <th>PO #</th>
                                            <th>Supplier</th>
                                            <th>PR #</th>
                                            <th>Status</th>
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
                                            <td>
                                                <center>
                                                     <a href="" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <a class="cancelDuplicatePO btn btn-custon-three btn-info btn-xs" data-toggle="modal" data-target="#cancelDuplicatePO" data-id="">Cancel & Duplicate</a>
                                                    <a class="cancelPO btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelPO" data-id="">Cancel</a>
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