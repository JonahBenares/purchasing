    <script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
        .emphasis{
            /*border-bottom: 1px solid red!important;*/
            background-color: #ffe5e5!important;
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: left">
                        <form method='POST' action="<?php echo base_url(); ?>po/add_repeatPO">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4 class="">PO No: </h4>
                                <select class="form-control" style="width: 25%" name='po' id='po' onchange="generatePO('<?php echo base_url(); ?>','<?php echo $vendor_id; ?>','<?php echo $po_id; ?>','<?php echo $pr_id; ?>','<?php echo $group_id; ?>',this.value);">
                                    <option value="" selected="">-Select PO-</option>
                                    <?php foreach($head As $h){ ?>
                                        <option value="<?php echo $h->po_id; ?>"><?php echo $h->po_no; ?></option>
                                    <?php } ?>
                                </select>   
                                <h4>PO                                                      
                                <table class="table-bordered" width="100%" style="margin-top: 20px">
                                    <thead>
                                        <tr>
                                            <th width="10%">Qty</th>
                                            <th width="10%">UOM</th>
                                            <th width="50%">Description</th>
                                            <th width="10%">Price</th>
                                            <th width="10%">Total</th>
                                            <th width="10%">PR No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $x=1;
                                        if(!empty($items)){
                                         foreach($items AS $i){ ?>
                                        
                                        <tr>
                                            <td style="padding: 0px!important"><input type="text" name="quantity<?php echo $x; ?>" id="quantity<?php echo $x; ?>" onblur='changePrice(<?php echo $x; ?>,0)'  onkeypress="return isNumberKey(this, event)" class="form-control emphasis" style='border:0px'></td>
                                            <td><?php echo $i['uom']; ?></td>
                                            <td><?php echo $i['offer']; ?></td>
                                            <td style="padding: 0px!important"><input type="text" name="price<?php echo $x; ?>" id="price<?php echo $x; ?>" value="<?php echo $i['price']; ?>"  onkeypress="return isNumberKey(this, event)" class="form-control" onblur='changePrice(<?php echo $x; ?>,0)' style='border:0px' readonly></td>
                                            <td style="padding: 0px!important">
                                                <input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' style='text-align:right; border:0px' readonly></span>
                                            <td><?php echo $i['pr_no']; ?></td>    
                                        </tr>
                                        <input type='hidden' name='po_items_id<?php echo $x; ?>' value="<?php echo $i['item_id']; ?>"> 
                                        <input type='hidden' name='pr_id' value="<?php echo $pr_id; ?>">
                                        <input type='hidden' name='group_id' value="<?php echo $group_id; ?>"> 
                                        <?php $x++; } 
                                    } ?>
                                    <?php $y=1; foreach($pr_det AS $p){ ?>
                                        <input type='hidden' name='pr_details_id<?php echo $y; ?>' value="<?php echo $p['pr_details_id']; ?>"> 
                                    <?php $y++; } ?>
                                    </tbody>
                                </table>
                                 <input type='hidden' name='old_po' value="<?php echo $old_po; ?>"> 
                                <input type='hidden' name='po_id' value="<?php echo $po_id; ?>">   
                                <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                <input type='submit' class="btn btn-primary btn-block" value='Save'>
                            </div>
                        </div>   
                    </form>
                        <div class="hr-bold"> </div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>