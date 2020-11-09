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
                                    <li><span class="bread-blod">DR List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addDr" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add DR</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>dr/add_dr">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">DR Date:</p>
                            <input type="date" name="dr_date" class="form-control">
                        </div>
                        <center>
                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                            <!-- a href="<?php echo base_url(); ?>index.php/dr/dr_prnt" class="dropdown-item">Proceed</a> -->
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
         <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1>DR List</h1>
                                <small>DELIVERY RECEIPT</small> 
                                <!-- <div class="sparkline8-outline-icon">
                                    <a type='button' class="btn btn-custon-three btn-primary"  data-toggle="modal" data-target="#addDr"> 
                                        <span class="fa fa-plus p-l-0"></span> Add DR
                                    </a>
                                </div> -->
                            </div>
                        </div>                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Dr No</th>
                                            <th>Type</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($head)){ foreach($head AS $h){ ?>
                                        <tr>
                                            <td><?php echo (!empty($h['dr_date']) ? date('F j, Y', strtotime($h['dr_date'])) : ''); ?></td>
                                            <td><?php echo $h['dr_no']."-".COMPANY; ?></td>
                                            <td><?php
                                                if($h['dr_type']==0){
                                                    echo "Purchase Order";
                                                } else if($h['dr_type']==1){
                                                    echo "Direct Purchase";
                                                } else if($h['dr_type']==2){
                                                    echo "Direct Purchase w/o RFD";
                                                } 
                                                ?>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php
                                                     if($h['dr_type']==0){ ?>
                                                        <a href="<?php echo base_url(); ?>po/delivery_receipt/<?php echo $h['po_id']; ?>/<?php echo $h['dr_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                    <?php } else if($h['dr_type']==1){ ?>
                                                        <a href="<?php echo base_url(); ?>po/delivery_receipt/<?php echo $h['po_id']; ?>/<?php echo $h['dr_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                         <!-- <a href="<?php echo base_url(); ?>rfdis/rfdis_dr/<?php echo $h['rfd_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                            <span class="fa fa-eye"></span>
                                                        </a> -->
                                                    <?php } else if($h['dr_type']==2){ ?>
                                                         <a href="<?php echo base_url(); ?>dr/dr_prnt/<?php echo $h['dr_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                    <?php } ?>
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