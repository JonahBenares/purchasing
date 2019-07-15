    <?php $CI =& get_instance(); ?>
    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
        .td{
            padding: 0px!important;            
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph">
                            <form method='POST' action="<?php echo base_url(); ?>rfdis/add_rfd_item">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4>Item List</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Description</th>
                                            <th width="10%">Qty</th>
                                            <th width="20%">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $x=1; 
                                        foreach($items AS $it) {  
                                    ?>
                                        <input type='hidden' name='item_id<?php echo $x; ?>' value='<?php echo $it->item_id; ?>'>
                                        <tr>
                                            <td><?php echo $CI->getname("item_name", "item", "item_id", $it->item_id) . ", " .$CI->getname("item_specs", "item", "item_id", $it->item_id); ?></td>
                                            <td class="td" style="padding-left: 0px!important;"><input type="text" class="form-control" name="quantity<?php echo $x; ?>" onkeypress="return isNumberKey(this, event)" autocomplete='off'></td>
                                            <td class="td" style="padding-left: 0px!important;"><input type="text" class="form-control" name="price<?php echo $x; ?>" onkeypress="return isNumberKey(this, event)" autocomplete='off'></td>
                                        </tr> 
                                        <?php $x++; } ?>          
                                    </tbody>
                                </table>
                                <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                <input type='hidden' name='rfd_id' value='<?php echo $rfd_id; ?>'>
                                <input type='submit' class="btn btn-primary btn-block" value='Save'>
                            </div>
                        </form>
                        </div>   
                        <div class="hr-bold"></div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>