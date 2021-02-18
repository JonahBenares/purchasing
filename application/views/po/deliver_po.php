    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: unset;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="modal-body-lowpad">
                                    <form method='POST' action="<?php echo base_url(); ?>po/save_delivery">
                                    <table width="100%">
                                        <tr>
                                            <td width="20%" style="padding-left: 0px!important">
                                                <div class="form-group">
                                                    <p class="m-b-0">Date Delivered:</p>
                                                    <input type="date" class="form-control" name="date_delivered" required="required">
                                                </div>
                                            </td>
                                            <td width="80%"></td>
                                        </tr>
                                    </table>                                   
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="5%" align="center">#</td>
                                                <td>Item Description</td>
                                                <td width="15%">Delivered Qty</td>
                                                <td width="15%">Received Qty</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $x=1;
                                            foreach($items AS $i){ ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $i->offer; ?></td>
                                                <td><?php echo $i->delivered_quantity; ?></td>
                                                <td style="padding-left: 0px!important;padding: 0px!important"><input style="background: #ffd1d1" type="text" class="form-control" name="received_qty<?php echo $x; ?>" required="required"></td>
                                            </tr>
                                            <input type = "hidden" id='po_items_id' name='po_items_id<?php echo $x ?>' value="<?php echo $i->po_items_id; ?>" >  
                                         <input type = "hidden" id='aoq_offer_id' name='aoq_offer_id<?php echo $x ?>' value="<?php echo $i->aoq_offer_id; ?>" >  
                                            <?php
                                            $x++; } ?>
                                        </tbody>
                                    </table>
                                    <center>      
                                        <input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>     
                                        <input type='hidden' name='dr_id' value='<?php echo $dr_id; ?>'>     
                                        <input type='hidden' name='count' value='<?php echo $x; ?>'>               
                                        <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                                    </center>
                                </div>  
                                </form>
                            </div>
                        </div>   
                        <div class="hr-bold"></div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>