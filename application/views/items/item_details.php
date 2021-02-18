    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-4" style="position:fixed">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright"> 
                                <h4>Item Details</h4>
                                <hr>
                                <table>
                                    <tbody>
                                        <?php foreach($item AS $i){ ?>
                                        <tr>
                                            <td><u><b>Item Description:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><h5><?php echo $i['item_name'];?></h5></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Specification:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['item_spec'];?></p></td>
                                        </tr>
                                        <!-- <tr>
                                            <td><u><b>Unit:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['unit'];?></p></td>
                                        </tr> -->
                                        <tr>
                                            <td><u><b>Brand:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['brand_name'];?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Part Number:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['pn_no'];?></p></td>                                            
                                        </tr>  
                                        <tr>
                                            <td><u><b>Unit Price:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['unit_price'];?></p></td>                                            
                                        </tr>  
                                        <tr>
                                            <td><u><b>Offer Date:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $i['offer_date'];?></p></td>                                            
                                        </tr>  
                                        <?php } ?>                                    
                                    </tbody>
                                </table>
                                <a href="<?php echo base_url(); ?>items/export_items/<?php echo $id; ?>" class="btn btn-custon-three btn-primary btn-block">Export To Excel</a>
                            </div>
                        </div>                      
                        <div class="hr-bold"></div>
                    </div>
                </div>
                <!--  -->
                <div class="col-lg-8 col-lg-offset-4">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4>Vendors List</h4>
                                <table id="table" data-toggle="table" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Address</th>
                                            <th>Phone Number</th>
                                            <th>Terms</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($vendors AS $v){ ?>
                                        <tr>
                                            <td>
                                                <a class="btn-link txt-primary" onclick="viewVendor('<?php echo base_url(); ?>','<?php echo $v['vendor_id'];?>')"><?php echo $v['vendor'];?></a>
                                            </td>
                                            <td><?php echo $v['address'];?></td>
                                            <td><?php echo $v['phn_no'];?></td>
                                            <td><?php echo $v['terms'];?></td>
                                            <td><?php echo $v['notes'];?></td>
                                        </tr>        
                                        <?php } ?>        
                                    </tbody>
                                </table>
                            </div>
                        </div>   
                        <div class="hr-bold"></div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>