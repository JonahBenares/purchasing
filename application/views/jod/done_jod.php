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
                                    <li><a href="<?php echo base_url(); ?>index.php/po/po_list">PO List </a> <span class="bread-slash">/</span></li>
                                    <li><span class="bread-blod">DONE PO List</span></li>
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
                        <div class="sparkline8-hd" style="background: #48ce66">
                            <div class="main-sparkline8-hd">
                                <h1 class="text-white">Done PO List</h1>
                                <small class="text-white">PURCHASE ORDER</small>
                                <div class="sparkline8-outline-icon">
                                    <h2><span class="fa fa-check"></span></h2>
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
                                            <td><?php echo $head['po_no']."-".COMPANY;?></td>
                                            <!-- <td><a class="btn-link txt-primary" onclick="viewHistory()"></a></td> -->
                                            <td><?php echo $head['supplier']; ?></td>
                                            <td><?php echo $head['pr']."-".COMPANY; ?></td>
                                            <td><?php //echo (($head['rfd']==0) ? '<span class="label label-warning">Pending RFD</span>' : ''); ?></td>
                                            <td><!-- Repeat Order Purchase Request --></td>
                                            <td>
                                                <center>
                                                    <a href="<?php echo base_url(); ?>po/update_done/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-success btn-xs" title='Done PO'>
                                                        <span class="fa fa-check"></span>
                                                    </a>
                                                    <?php if($head['saved']==0){ ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else { ?>
                                                    <a href="<?php echo base_url(); ?>po/purchase_order_saved/<?php echo $head['po_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } ?>
                                                    <!-- <a href="<?php echo base_url(); ?>po/reporder_prnt/" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a> -->
                                                    <a class="cancelDuplicatePO btn btn-custon-three btn-info btn-xs" data-toggle="modal" data-target="#cancelDuplicatePO" data-id="" title="Cancel and Duplicate">
                                                        <span class="fa fa-ban"></span> 
                                                        <span class="fa fa-files-o"></span>
                                                    </a>
                                                    <a class="cancelPO btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelPO" data-id=""><span class="fa fa-ban" title="Cancel"></span></a>
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
                 </form>
            </div>
        </div>
    </div>
    <!-- Data table area End-->