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
                        <form method='POST' action="<?php echo base_url(); ?>po/add_repeatPO"
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4 class="">PO No: </h4>
                                <select class="form-control" style="width: 25%" name='po' id='po' onchange="generatePO(this.value, '<?php echo $po_id; ?>', '<?php echo $supplier; ?>', '<?php echo base_url(); ?>');">
                                    <option value="" selected="">-Select PO-</option>
                                    <?php foreach($po AS $p){ 
                                       /* if($p->revision_no == 0){
                                            $po = $p->po_no;
                                        } else {
                                            $po = $p->po_no.".r".$p->revision_no;
                                        }*/ ?>
                                        <option value="<?php echo $p->po_no; ?>"><?php echo $p->po_no; ?></option>
                                    <?php } ?>
                                </select>   
                                <?php if(!empty($po_url)) { ?> 
                                <h4><?php echo $po_url; ?>                                                       
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
                                      
                                        foreach($items AS $it){ 
                                            $total = $it['quantity'] * $it['price'];?>
                                        <tr>
                                            <td style="padding: 0px!important"><input type="text" name="quantity<?php echo $x; ?>" id="quantity<?php echo $x; ?>" value="<?php echo $it['quantity']; ?>" onblur='changePrice(<?php echo $x; ?>,0)'  onkeypress="return isNumberKey(this, event)" class="form-control emphasis" style='border:0px'></td>
                                            <td><?php echo $it['unit']; ?></td>
                                            <td><span style='color:red'><?php echo $it['offer']; ?></span>, <?php echo $it['item'] . ", " . $it['specs']; ?></td>
                                            <td style="padding: 0px!important"><input type="text" name="price<?php echo $x; ?>" id="price<?php echo $x; ?>" value="<?php echo $it['price']; ?>"  onkeypress="return isNumberKey(this, event)" class="form-control" onblur='changePrice(<?php echo $x; ?>,0)' style='border:0px' readonly></td>
                                            <td><input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="<?php echo number_format($total,2); ?>" style='text-align:right; border:0px' readonly></span>
                                            <td><?php echo $it['pr_no']; ?></td>
                                            <input type='hidden' name='pr_id<?php echo $x; ?>' value="<?php echo $it['pr_id']; ?>">   
                                            <input type='hidden' name='pr_no<?php echo $x; ?>' value="<?php echo $it['pr_no']; ?>">   
                                            <input type='hidden' name='item_id<?php echo $x; ?>' value="<?php echo $it['item_id']; ?>">
                                            <input type='hidden' name='offer<?php echo $x; ?>' value="<?php echo $it['offer']; ?>">      

                                        </tr>
                                        <?php 
                                        $x++; } ?>
                                    </tbody>
                                </table>
                                 <input type='hidden' name='old_po' value="<?php echo $old_po; ?>"> 
                                <input type='hidden' name='po_id' value="<?php echo $po_id; ?>">   
                                <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                <button class="btn btn-primary btn-block">Save</button>
                                <?php } ?>
                            </div>
                        </div>   
                        <div class="hr-bold"> </div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>