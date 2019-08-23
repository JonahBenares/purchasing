
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h5>Add Item/s to Vendor:</h5>
                            <h4><u><?php echo $vendor;?></u></h4>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <form method='POST' action='<?php echo base_url(); ?>vendors/insert_itemvendor'>
                                    <table width="100%">
                                        <tbody>
                                        <?php for($x=1;$x<=10;$x++){ ?>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <p class="m-b-0">Item #<?php echo $x; ?>:</p>
                                                        <select name="item<?php echo $x; ?>" class="form-control" >
                                                            <option value = "">--Select Item--</option>
                                                            <?php foreach($item AS $i){ ?>
                                                            <option value = "<?php echo $i->item_id?>"><?php echo $i->item_name.", ".$i->brand_name.", ".$i->item_specs; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </td>     
                                            </tr>
                                        <?php } ?>
                                        <input type="hidden" name="id" value = "<?php echo $id; ?>">    
                                            <tr>
                                                <td><input type="submit" class="btn btn-custon-three btn-primary btn-block" value = "Add Item/s"></td>
                                            </tr>                                
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>                      
                        <div style="height: 10px; background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>