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
                                    <li><span class="bread-blod">RFD List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addRFD" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add RFD</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>index.php/rfdis/create_rfd">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">RFD Date:</p>
                            <input type="date" name="rfd_date" class="form-control">
                        </div>
                     
                        <div class="form-group">
                            <p class="m-b-0">Company:</p>
                            <input type="text" name="company" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Pay To :</p>
                            <select name='pay_to' class="form-control" required>
                                <option value='' selected="">-Select Vendor-</option>
                            <?php foreach($supplier AS $sup){ ?>
                                <option value="<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                      
                        <div class="form-group">
                            <p class="m-b-0">APV No:</p>
                            <input type="text" name="apv_no" class="form-control" autocomplete="off">
                        </div>
                      
                        <div class="row">
                            <div class="form-group col-lg-2">
                                <p class="m-b-0">Cash :</p>
                                <input type="radio" name="cash" class="" value='1'>
                            </div>
                            <div class="form-group col-lg-2">
                                <p class="m-b-0">Check :</p>
                                <input type="radio" name="cash" class="" value='2'>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Check Name:</p>
                            <input type="text" name="check_name" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Bank No:</p>
                            <input type="text" name="bank_no" class="form-control" autocomplete="off">
                        </div>
                           <div class="form-group">
                            <p class="m-b-0">Due Date:</p>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Check Due:</p>
                            <input type="date" name="check_due" class="form-control">
                        </div>
                        <center>
                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                          <!--   <a href="<?php echo base_url(); ?>index.php/rfdis/rfdis_prnt" class="dropdown-item">Proceed</a> -->
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
                                <h1>RFD List</h1>
                                <small>REQUEST FOR DISBURSEMENT</small> 
                                <div class="sparkline8-outline-icon">
                                    <a type='button' class="btn btn-custon-three btn-primary"  data-toggle="modal" data-target="#addRFD"> 
                                        <span class="fa fa-plus p-l-0"></span> Add RFD
                                    </a>
                                </div>
                            </div>
                        </div>                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Company</th>
                                            <th>Pay to</th>
                                            <th>APV NO</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($rfd)){
                                            foreach($rfd AS $r){ ?>                                        
                                        <tr>
                                            <td><?php echo date('F j, Y', strtotime($r['rfd_date'])); ?></td>
                                            <td><?php echo $r['company']; ?></td>
                                            <td><?php echo $r['pay_to']; ?></td>
                                            <td><?php echo $r['apv_no']; ?></td>
                                            <td><?php echo number_format($r['net_amount'],2); ?></td>
                                            <td><?php echo $r['type']; ?></td>
                                            <td>
                                                <center>
                                                    <?php if($r['type']=='Direct Purchase'){ ?>
                                                         <a href="<?php echo base_url(); ?>rfdis/rfdis_prnt/<?php echo $r['rfd_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                    <?php } else { ?>
                                                         <a href="<?php echo base_url(); ?>po/rfd_prnt/<?php echo $r['po_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
                                                        <span class="fa fa-eye"></span>
                                                         </a>
                                                    <?php } ?>
                                                </center>
                                            </td>
                                        </tr>    
                                        <?php } 
                                    } ?>                             
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