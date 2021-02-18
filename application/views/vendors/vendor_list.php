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
                                    <li><span class="bread-blod">Vendor List</span>
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
                                <h1>Vendor List</h1>
                                <div class="sparkline8-outline-icon">
                                    <a class="btn btn-custon-three btn-primary" href="#" data-toggle="modal" data-target="#addVendor">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Add Vendor
                                    </a>
                                    <a class="btn btn-custon-three btn-success" href="#" data-toggle="modal" data-target="#searchVendor">
                                        <span class="fa fa-search p-l-0"></span>
                                        Search Vendor
                                    </a>
                                    <div id="addVendor" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header header-color-modal bg-color-1">
                                                    <h4 class="modal-title">Add New Vendor</h4>
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/vendors/insert_vendor">
                                                    <div class=" p-l-20 p-r-20 modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Vendor:</p>
                                                            <input type="text" name="vendor" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Product/Services:</p>
                                                            <input type="text" name="product" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Address:</p>
                                                            <input type="text" name="address" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Phone Number:</p>
                                                            <input type="text" name="phone_num" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Fax Number:</p>
                                                            <input type="text" name="fax_num" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Terms:</p>
                                                            <input type="text" name="terms" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Type:</p>
                                                            <input type="text" name="type" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Contact Person:</p>
                                                            <input type="text" name="contact" class="form-control">
                                                        </div>
                                                         <div class="form-group">
                                                            <p class="m-b-0">TIN #:</p>
                                                            <input type="text" name="tin" class="form-control" >
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">EWT(%):</p>
                                                            <input type="text" name="ewt" class="form-control" onkeypress="return isNumberKey(this, event)">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">VAT:
                                                                        <input type="radio" name="vat" value = '1' class="form-control">
                                                                    </p>
                                                                </div>
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">NON-VAT: 
                                                                        <input type="radio" name="vat" value = '0' class="form-control">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Notes:</p>
                                                            <input type="text" name="notes" class="form-control">
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
                                                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                                                        </center>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="searchVendor" class="modal modal-adminpro-general fullwidth-popup-InformationproModal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header header-color-modal bg-color-2">
                                                    <h4 class="modal-title">Search Vendor</h4>
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                                <form  method='POST' action="<?php echo base_url(); ?>index.php/vendors/search_vendor/">
                                                    <div class=" p-l-20 p-r-20 modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Vendor:</p>
                                                            <input type="text" name="vendor" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Product/Services:</p>
                                                            <input type="text" name="product" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Address:</p>
                                                            <input type="text" name="address" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Phone Number:</p>
                                                            <input type="text" name="phone" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Fax Number:</p>
                                                            <input type="text" name="fax" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Terms:</p>
                                                            <input type="text" name="terms" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Type:</p>
                                                            <input type="text" name="type" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Contact Person:</p>
                                                            <input type="text" name="contact" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Notes:</p>
                                                            <input type="text" name="notes" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">TIN #:</p>
                                                            <input type="text" name="tin" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">EWT(%):</p>
                                                            <input type="text" name="ewt" class="form-control" onkeypress="return isNumberKey(this, event)">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">VAT:
                                                                        <input type="radio" name="vat" value = '1' class="form-control">
                                                                    </p>
                                                                </div>
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">NON-VAT: 
                                                                        <input type="radio" name="vat" value = '0' class="form-control">
                                                                    </p>
                                                                </div>
                                                            </div>
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
                                                            <!-- <a href="#" class="btn btn-custon-three btn-success btn-block"><span class="fa fa-search"></span> Search</a> -->
                                                            <input type = "submit" class="btn btn-custon-three btn-success btn-block" value = "Search">
                                                        </center>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(!empty($filt)){ ?>     
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href='<?php echo base_url(); ?>index.php/vendors/vendor_list' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>
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
                                            <th>Vendor</th>
                                            <th>Product/Services</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Terms</th>
                                            <th>Type</th>
                                            <th>Notes</th>
                                            <th>EWT(%)</th>
                                            <th>VAT</th>
                                            <th>Status</th>
                                            <th><center>Action</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        if(!empty($vendors)){
                                            foreach($vendors AS $v){ 
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a class="btn-link txt-primary" onclick="vendorDetails('<?php echo base_url(); ?>','<?php echo $v['vendor_id']; ?>')"><?php echo $v['vendor'];?></a>
                                            </td>
                                            <td><?php echo $v['product']?></td>
                                            <td><?php echo $v['address']?></td>
                                            <td><?php echo $v['phone']?></td>
                                            <td><?php echo $v['terms']?></td>
                                            <td><?php echo $v['type']?></td>
                                            <td><?php echo $v['notes']?></td>
                                            <td><?php echo $v['ewt']?></td>
                                            <td><?php if($v['vat'] == 1){ echo 'Yes'; }else{ echo 'No';}?></td>
                                            <td><?php if($v['status'] == 'Active'){ echo 'Active'; }else { echo 'Inactive'; } ?></td>
                                            <td>
                                                <center>
                                                    <a onclick="updateVendor('<?php echo base_url(); ?>','<?php echo $v['vendor_id']; ?>')" class="btn btn-custon-three btn-info btn-xs">
                                                        <span class="fa fa-pencil"></span>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>index.php/vendors/delete_vendor/<?php echo $v['vendor_id'];?>" class="btn btn-custon-three btn-danger btn-xs" onclick="confirmationDelete(this);return false;">
                                                        <span class="fa fa-times"></span>
                                                    </a>
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
    <!-- Data table area End-->