
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Item</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>items/edit_item">
                                                    <div class="modal-body-lowpad">
                                                        <?php foreach($item AS $i){ ?>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Item Description:</p>
                                                            <textarea name="item" class="form-control" cols="2"><?php echo $i->item_name;?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Specification:</p>
                                                            <textarea name="spec" class="form-control" cols="2"><?php echo $i->item_specs;?></textarea>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <p class="m-b-0">Unit:</p>
                                                            <select name="unit" class="form-control">
                                                                <option value = "">--Select Unit--</option>
                                                                <?php foreach($unit AS $u){ ?>
                                                                <option value = "<?php echo $u->unit_id?>" <?php echo (($i->unit_id == $u->unit_id) ? ' selected' : '');?>><?php echo $u->unit_name;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div> -->
                                                        <div class="form-group">
                                                            <p class="m-b-0">Brand:</p>
                                                            <input type="text" name="brand" class="form-control" value = "<?php echo $i->brand_name;?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Part Number:</p>
                                                            <input type="text" name="pn" class="form-control" value = "<?php echo $i->part_no;?>">
                                                        </div>
                                                        <center>
                                                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                                                        </center>
                                                        <input type = "hidden" name = "item_id" value="<?php echo $id; ?>">
                                                        <?php } ?>
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