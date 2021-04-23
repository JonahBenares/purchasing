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
                                    <li><span class="bread-blod">JOD List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div id="addrepPO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add <b>Repeat</b> Order</h4>
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
                            <select name="supplier" id='supplierrep' onchange="chooseSupplierrep()" class="form-control">
                                 <option value='' selected="selected">-Choose Supplier/Vendor-</option>
                               
                            </select>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Address:</p>
                            <b><span id='addressrep'></span></b>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Contact Person:</p>
                            <b><span id='contactrep'></span></b>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Telephone #:</p>
                            <b><span id='phonerep'></span></b>
                        </div>
                         <div class="form-group">
                            <p class="m-b-0">Notes:</p>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                        <center>
                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                        </center>
                    </div>
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                </form>
            </div>
        </div>
    </div> -->
    <div id="addPOD" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add JO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>index.php/jod/create_jo">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Date:</p>
                            <input type="date" name="po_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">JO NO:</p>
                            <input type="text" name="po_no" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <select name="vendor" id='supplier' onchange="chooseSupplier()" class="form-control">
                            <option value='' selected>-Choose Supplier/Vendor-</option>
                             <?php foreach($vendor AS $ven){ ?>
                                <option value='<?php echo $ven->vendor_id; ?>'><?php echo $ven->vendor_name; ?></option>
                            <?php } ?>
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
                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
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
                    <h4 class="modal-title">Cancel JO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>pod/cancel_po">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling JO:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='joi_id' name='joi_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div id="cancelDuplicatePO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel and Duplicate PO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>po/">
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
    </div> -->
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="POST">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd">
                                <div class="main-sparkline8-hd">
                                    <h1>JOD List</h1>
                                    <small>JOB ORDER <b>DIRECT</b></small>
                                    <div class="sparkline8-outline-icon">
                                    <a type='button' class="btn btn-custon-three btn-primary" data-toggle="modal" data-target="#addPOD">
                                        <span class="fa fa-plus p-l-0"></span> Add JOD
                                    </a>
                                    <a type='button' class="btn btn-custon-three btn-info" data-toggle="modal" data-target="#addrepPO">
                                        <span class="fa fa-repeat p-l-0 "> </span> Add Repeat Order
                                    </a>
                                    <a href="<?php echo base_url(); ?>jod/done_jod" class="btn btn-custon-three btn-success"><span class="p-l-0 fa fa-check"></span> Done JOD</a> 
                                    <a href="<?php echo base_url(); ?>jod/cancelled_jod" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled JOD</a>
                                    </div>
                                </div>
                            </div>                       
                            <div class="sparkline8-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>JOD Date</th>
                                                <th>JOD #</th>
                                                <th>Supplier</th>
                                                <th>PR #</th>
                                                <th>Status</th>
                                                <th>Mode of Purchase</th>
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($header AS $h){ ?>
                                            <tr>
                                                <td><?php echo date("F d, Y",strtotime($h['joi_date'])); ?></td>
                                                <td><?php echo $h['joi_no']."-".COMPANY;?></td>
                                                <td><?php echo $h['supplier']; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <center>
                                                        <a href="<?php echo base_url(); ?>jod/update_done/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-success btn-xs" title='Done JO'>
                                                            <span class="fa fa-check"></span>
                                                        </a>
                                                        <a href="<?php echo base_url(); ?>jod/jo_direct/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                        <!-- <a href="<?php echo base_url(); ?>po/purchase_order_saved/" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                            <span class="fa fa-eye"></span>
                                                        </a> -->
                                                        <a class="cancelDuplicateJO btn btn-custon-three btn-info btn-xs" data-toggle="modal" data-target="#cancelDuplicateJO" data-id="" title="Cancel and Duplicate">
                                                            <span class="fa fa-ban"></span> 
                                                            <span class="fa fa-files-o"></span>
                                                        </a>
                                                        <a class="cancelPO btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelPO" data-id="<?php echo $h['joi_id']; ?>"><span class="fa fa-ban" title="Cancel"></span></a>
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
            </form>
        </div>
    </div>
    <!-- Data table area End-->