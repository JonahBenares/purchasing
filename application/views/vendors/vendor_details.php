    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
        .text-white{
            color: #fff;
        }
    </style>
    <script type="text/javascript">
        function toggle_multi(source) {
      checkboxes_multi = document.getElementsByClassName('vendor_list');
      for(var i=0, n=checkboxes_multi.length;i<n;i++) {
        checkboxes_multi[i].checked = source.checked;
      }
    }
    </script>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-4">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: unset;">
                            <div class="datatable-dashv1-list custom-datatable-overright"> 
                                <h4>
                                    <a href="" onclick="return quitBox('quit');" class="btn btn-success"><span class="fa fa-arrow-left"></span> Back</a>
                                    <div class="pull-right">
                                        Vendor Details
                                    </div>                                                                      
                                </h4>
                                <hr class="m-t-0 m-b-0">
                                <table>
                                    <tbody>
                                    <?php foreach($vendor AS $v){ ?>
                                        <tr>
                                            <td><u><b>Vendor:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><h5><?php echo $v->vendor_name; ?></h5></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Product/Service:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->product_services; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Address:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->address; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Phone Number:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->phone_number; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Fax Number:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->fax_number; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Terms:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->terms; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Type:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->type; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Contact Person:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->contact_person; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Notes:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->notes; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Tin #:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->tin; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>EWT(%):</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->ewt; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>VAT:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php if($v->vat == 1){ echo 'Yes'; }else{ echo 'No';} ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><u><b>Status:</b></u></td>
                                        </tr>
                                        <tr>
                                            <td><p><?php echo $v->status; ?></p></td>                                            
                                        </tr>  
                                        <?php } ?>                              
                                    </tbody>
                                </table>
                                <a href="<?php echo base_url(); ?>vendors/export_vendor/<?php echo $id; ?>" class="btn btn-custon-three btn-primary btn-block">Export To Excel</a>
                            </div>
                        </div>                      
                        <div class="hr-bold"></div>
                    </div>
                </div>
                <!--  -->
                <form method='POST' action='<?php echo base_url(); ?>rfq/create_rfq' >
                <div class="col-lg-8">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: unset;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4>Item List 
                                    <div class="pull-right">
                                        <a onclick="addVendorItem('<?php echo base_url(); ?>','<?php echo $id;?>')" class="btn btn-custon-three btn-primary"><span class="fa fa-plus"></span> Add Item</a>
                                        <!-- <input type='submit' onclick="return confirm('Are you sure you want to create RFQ?')" class="text-white btn btn-custon-three btn-secondary" value="Create RFQ"> -->
                                    </div>
                                </h4>
                                <table width="100%" data-toggle="table" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th align="center"><input type="checkbox" name="" onClick="toggle_multi(this)"></th>
                                            <th>Item Description</th>
                                            <th>Brand</th>
                                            <th>Unit Price</th>
                                            <th>Offer Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                    <?php foreach($vendors AS $va){ ?>                                         
                                        <tr>
                                            <td align="center"><input type="checkbox" name="item_id[]" value="<?php echo $va['item_id']; ?>" class='vendor_list'></td>
                                            <td><?php echo $va['item']. " ". $va['specs']; ?></td>
                                            <td><?php echo $va['brand'];?></td>
                                            <td><?php echo $va['price'];?></td>
                                            <td><?php echo $va['offer_date'];?></td>
                                            <td>
                                                <center>
                                                    <a href="<?php echo base_url(); ?>index.php/vendors/delete_item/<?php echo $vendor_id;?>/<?php echo $va['vendordet_id'];?>" class="btn btn-custon-three btn-danger btn-xs" onclick="confirmationDelete(this);return false;">
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
                        <div class="hr-bold"></div>                   
                    </div>
                </div>
                 <input type='hidden' name='vendor_id' value="<?php echo $vendor_id; ?>"> 
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function quitBox(cmd)
        {   
            if (cmd=='quit')
            {
                open(location, '_self').close();
            }   
            return false;   
        }
    </script>