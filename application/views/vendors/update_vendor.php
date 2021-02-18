
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Vendor</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>vendors/edit_vendor">
                                                    <div class="modal-body-lowpad">
                                                    <?php foreach($vendor AS $v){ ?>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Vendor:</p>
                                                            <input type="text" name="vendor" class="form-control" value = "<?php echo $v->vendor_name;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Product/Services:</p>
                                                            <textarea name="product" class="form-control"><?php echo $v->product_services;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Address:</p>
                                                            <textarea name="address" class="form-control"><?php echo $v->address;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Phone Number:</p>
                                                            <input type="text" name="phone" class="form-control" value = "<?php echo $v->phone_number;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Fax Number:</p>
                                                            <input type="text" name="fax" class="form-control" value = "<?php echo $v->fax_number;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Terms:</p>
                                                            <textarea name="terms" class="form-control"><?php echo $v->terms;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Type:</p>
                                                            <textarea name="type" class="form-control"><?php echo $v->type;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Contact Person:</p>
                                                            <input type="text" name="contact" class="form-control" value = "<?php echo $v->contact_person;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Tin Number:</p>
                                                            <input type="text" name="tin" class="form-control" value = "<?php echo $v->tin;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Notes:</p>
                                                            <textarea name="notes" class="form-control"><?php echo $v->notes;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">EWT(%):</p>
                                                            <input name="ewt" class="form-control" value = "<?php echo $v->ewt;?>" onkeypress="return isNumberKey(this, event)">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">VAT:
                                                                        <input type="radio" name="vat" value = '1' <?php echo ((strpos($v->vat, "1") !== false) ? ' checked' : '');?> class="form-control">
                                                                    </p>
                                                                </div>
                                                                <div class = "col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <p class="m-b-0">NON-VAT: 
                                                                        <input type="radio" name="vat" value = '0' <?php echo ((strpos($v->vat, "0") !== false) ? ' checked' : '');?> class="form-control">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Status:</p>
                                                            <select type="text" name="status" class="form-control">
                                                                <option value = "Active" <?php echo (($v->status == 'Active') ? ' selected' : '');?>>Active</option>
                                                                <option value = "Inactive" <?php echo (($v->status == 'Inactive') ? ' selected' : '');?>>Inactive</option>
                                                            </select>
                                                        </div>
                                                        <input type = "hidden" name = "vendor_id" value="<?php echo $id; ?>">
                                                        <?php } ?>
                                                        <center>
                                                            <input type = "submit" class="btn btn-custon-three btn-info btn-block" value = "Update">
                                                        </center>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>                      
                        <div style="height: 10px; background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>