    <script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
    <script type="text/javascript">
        function Change() {
            if((document.getElementById('check').checked)) {
                document.getElementById('show_hide').style.visibility="hidden";
            }
            else {
                document.getElementById('show_hide').style.visibility="visible";
            }
        }
    </script>
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
                                    <li><span class="bread-blod">PO List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="approveLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approved Revision <span class="fa fa-thumbs-o-up"></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>po/approve_revision">
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="m-b-0">Approved by:</p>
                            <input type="text" name="approve_rev" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Approved Date:</p>
                            <input type="date" name="approve_date" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="po_id" id="po_id" >
                        <input type='submit' value='Approve' class="btn btn-custon-three btn-primary btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="addrepPO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add <b>Repeat</b> Order</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>po/create_reorderpo">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Date:</p>
                            <input type="date" name="po_date" value = "<?php echo date('Y-m-d'); ?>" style = "pointer-events: none;" class="form-control">
                        </div>
                      <!--   <div class="form-group">
                            <p class="m-b-0">PO NO:</p>
                            <input type="text" name="po_no" class="form-control" autocomplete="off">
                        </div> -->
                        <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <select name="supplier" id='supplierrep' onchange="chooseSupplierrep()" class="form-control selectpicker" data-show-subtext="true" data-live-search="true">
                                <option value='' selected>-Choose Supplier/Vendor-</option>
                                  <?php foreach($vendor AS $sup){ ?>
                                    <option value="<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                            <?php } ?>                            
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
                           <!--  <a href="<?php echo base_url(); ?>index.php/po/reporder_prnt" class="dropdown-item">Repeat Order</a> -->
                        </center>
                    </div>
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                </form>
            </div>
        </div>
    </div>
    <div id="addPO" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add PO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>po/create_po">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Date:</p>
                            <input type="date" name="po_date" value = "<?php echo date('Y-m-d'); ?>" style = "pointer-events: none;" class="form-control">
                        </div>
                      <!--   <div class="form-group">
                            <p class="m-b-0">PO NO:</p>
                            <input type="text" name="po_no" class="form-control" autocomplete="off">
                        </div> -->
                        <!-- <div class="form-group">
                            <u><p class="m-b-0"><input type="checkbox" name="dp" onclick="Change()" id="check" value = '1'> Direct Purchase </p></u>
                        </div> -->
                        <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <select name="vendor" id='supplier' onchange="chooseSupplierPR()" class="form-control selectpicker" data-live-search="true">
                            <option value='' selected>-Choose Supplier/Vendor-</option>
                             <?php foreach($vendor AS $ven){ ?>
                                <option value='<?php echo $ven->vendor_id; ?>'><?php echo $ven->vendor_name; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div id="show_hide">
                            <div class="form-group">
                                <p class="m-b-0">PR No.:</p>
                                <select name="prno" id='prno' onchange="choosePR()" style = 'width:100%;' class="form-control select2">
                               </select>
                            </div>
                            <div class="form-group">
                                <p class="m-b-0">Purpose:</p>
                                <b><span id='purpose'></span></b>
                            </div>
                            <div class="form-group">
                                <p class="m-b-0">Enduse:</p>
                                <b><span id='enduse'></span></b>
                            </div>
                            <div class="form-group">
                                <p class="m-b-0">Requestor:</p>
                                <b><span id='requestor'></span></b>
                            </div>
                        </div>
                        <center>
                           <input type="hidden" name="aoq_id" id="aoq_id">
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
                <form method="POST" action = "<?php echo base_url();?>po/">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling PO:</p>
                            <textarea name="reason" class="form-control" ></textarea>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>PO List</h1>
                                <small>PURCHASE ORDER</small>
                                <div class="sparkline8-outline-icon">
                                <a type='button' class="btn btn-custon-three btn-primary" data-toggle="modal" data-target="#addPO">
                                    <span class="fa fa-plus p-l-0"></span> Add PO
                                </a>
                                <!-- <a type='button' class="btn btn-custon-three btn-info" data-toggle="modal" data-target="#addrepPO">
                                    <span class="fa fa-repeat p-l-0 "> </span> Add Repeat Order
                                </a> -->
                                <a href="<?php echo base_url(); ?>po/served_po" class="btn btn-custon-three btn-success"><span class="p-l-0 fa fa-check"></span> Delivered PO</a> 
                                <a href="<?php echo base_url(); ?>po/incom_podel" class="btn btn-custon-three btn-warning"><span class="p-l-0 fa fa-adjust"></span> Incomplete PO Delivery</a> 
                                <a href="<?php echo base_url(); ?>po/cancelled_po" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled PO</a>
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
                                            <th>Mode of Purchase</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($header)){
                                                foreach($header AS $head){ 
                                        ?>
                                        <tr>
                                            <td><?php echo date('F j, Y', strtotime($head['po_date'])); ?></td>
                                           <!--  <td></td> -->
                                            <td><a class="btn-link txt-primary" onclick="viewHistory('<?php echo base_url(); ?>','<?php echo $head['po_id']; ?>','<?php echo $head['po_no']; ?>')"><?php echo $head['po_no']."-".COMPANY. (($head['revision_no']!=0) ? ".r".$head['revision_no'] : "");?></a></td>
                                            <td><?php echo $head['supplier']; ?></td>
                                            <td><?php echo $head['pr']."-".COMPANY; ?></td>
                                            <td><?php 

                                            if($head['revised']==1) {
                                                echo '<span class="label label-warning">Request for Revision</span>';
                                            } else {
                                                if($head['served']==0 && $head['saved']==1) {
                                                    echo '<span class="label label-warning">PO Issued</span>';
                                                } else if($head['served']==1) {
                                                  echo '<span class="label label-success">Delivered</span>'; 
                                                } 

                                                 if($head['draft']==1) {
                                                  echo '<span class="label label-info">Draft</span>'; 
                                                }
                                            } ?></td>
                                            <td><?php
                                                if($head['po_type']==0){
                                                    echo "Purchase Request";
                                                } else if($head['po_type']==1){
                                                    echo "Direct Purchase";
                                                } else if($head['po_type']==2){
                                                    echo "Repeat Order";
                                                }
                                            ?></td>
                                            <td>
                                                <center>       
                                                <?php if($head['saved']==1){ ?>                                                 
                                                    <a href="" class="btn btn-custon-three btn-success btn-xs deliverpo" title='Deliver PO' onclick="deliver_po('<?php echo base_url(); ?>','<?php echo $head['po_id']?>','<?php echo $head['dr_id']?>')">
                                                        <span class="fa fa-truck"></span>
                                                    </a>
                                                    <?php } if($head['revised']==1){ ?>
                                                    <a class="btn btn-custon-three btn-info btn-xs approverev" title='Aprrove Revision' data-toggle="modal" data-target="#approve" data-id="<?php echo $head['po_id']?>">
                                                        <span class="fa fa-thumbs-up"></span>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if($head['saved']==0 && $head['draft']==0  && $head['po_type']==0){ ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                      <?php }else if($head['saved']==0 && $head['draft']==0  && $head['po_type']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>pod/po_direct/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else if($head['saved']==0 && $head['draft']==1  && $head['po_type']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>pod/po_direct_draft/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                      <?php } else if($head['saved']==0 && $head['draft']==0  && $head['po_type']==2){ ?>
                                                    <a href="<?php echo base_url(); ?>po/reporder_prnt/<?php echo $head['po_id']?>/<?php echo $head['pr_id'];?>/<?php echo $head['grouping_id'];?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==0 && $head['draft']==1 && $head['po_type']==2){ ?>
                                                    <a href="<?php echo base_url(); ?>po/reporder_prnt_draft/<?php echo $head['po_id']?>/<?php echo $head['pr_id'];?>/<?php echo $head['grouping_id'];?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==0 && $head['draft']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order_draft/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==1 && $head['po_type']==0 && $head['revised']==0){ ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order_saved/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==1 && $head['po_type']==0 && $head['revised']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order_rev/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==1 && $head['po_type']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>pod/po_direct/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } else if($head['saved']==1 && $head['po_type']==2){ ?>
                                                    <a href="<?php echo base_url(); ?>po/reporder_prnt/<?php echo $head['po_id']?>/<?php echo $head['pr_id'];?>/<?php echo $head['grouping_id'];?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } ?>
                                                    <!-- <a href="<?php echo base_url(); ?>po/reporder_prnt/" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a> -->
                                                 <!--    <a class="cancelDuplicatePO btn btn-custon-three btn-info btn-xs" data-toggle="modal" data-target="#cancelDuplicatePO" data-id="" title="Cancel and Duplicate">
                                                        <span class="fa fa-ban"></span> 
                                                        <span class="fa fa-files-o"></span>
                                                    </a> -->
                                                    <a class="cancelPO btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelPO" data-id="<?php echo $head['po_id']?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                    <!--<a href="" class="btn btn-custon-three btn-danger btn-xs"  data-toggle="modal" data-target="#cancelPO" title="WITH MODAL REASON">Cancel</a>-->
                                                </center>
                                            </td>
                                        </tr>   
                                        <?php } } ?>                                 
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2();
    </script>
    <!-- Data table area End-->