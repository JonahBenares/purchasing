    <div id="filter_pr" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>reports/search_unserved/<?php echo $year;?>/<?php echo $month;?>">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">PR No:</p>
                            <select name="pr_no" class="form-control">
                                <option value = "">--Select PR Number--</option>
                                <?php foreach($pr_no1 AS $pr){ ?>
                                <option value = "<?php echo $pr->pr_id;?>"><?php echo $pr->pr_no."-".COMPANY; ?></option>
                                <?php } ?>
                            </select>
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Date of PO:</p>
                            <input type="date" name="date_po" class="form-control">
                        </div>    
                        <div class="form-group">
                            <p class="m-b-0">PO No:</p>
                            <input type="text" name="po_no" class="form-control">
                        </div> 
                        <div class="form-group">
                            <p class="m-b-0">Purpose:</p>
                            <input type="text" name="purpose" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Enduse:</p>
                            <input type="text" name="enduse" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Requestor:</p>
                            <input name="requestor" class="form-control">
                            <!-- <select name="requestor" class="form-control">
                                <option value = "">--Select Requestor--</option>
                                <?php foreach($employees AS $emp){ ?>
                                <option value = "<?php echo $emp->employee_id;?>"><?php echo $emp->employee_name; ?></option>
                                <?php } ?>
                            </select> -->
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Description:</p>
                            <input name="description" class="form-control">
                            <!-- <select name="description" class="form-control">
                                <option value = "">--Select Item--</option>
                                <?php foreach($items AS $i){ ?>
                                <option value = "<?php echo $i->item_id;?>"><?php echo $i->item_name." - ".$i->item_specs; ?></option>
                                <?php } ?>
                            </select> -->
                        </div>      
                        <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <select name="supplier" class="form-control">
                                <option value = "">--Select Supplier--</option>
                                <?php foreach($vendors AS $s){ ?>
                                <option value = "<?php echo $s->vendor_id;?>"><?php echo $s->vendor_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <input type="hidden" name="month" value = "<?php echo $month; ?>">            
                        <input type="hidden" name="year" value = "<?php echo $year; ?>">                
                        <center>                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                            <!-- <a href="<?php echo base_url(); ?>index.php/pr/purchase_request">Proceed</a> -->
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div class="admin-dashone-data-table-area m-t-15 ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1><button onclick="return quitBox('quit');" class=" btn btn-xs btn-success"><span class="fa fa-arrow-left"></span></button>
                                    Undelivered PO Report <b style="color:blue"><?php echo $date; ?></b>
                                </h1>
                                <small class="p-l-25">&nbsp;UNDELIVERED PO REPORT</small> 
                                <div class="sparkline8-outline-icon">
                                    <?php if(!empty($filt)){ ?>
                                        <a href="<?php echo base_url(); ?>reports/export_unserved/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $pr_no; ?>/<?php echo $date_po; ?>/<?php echo $po_no; ?>/<?php echo $purpose; ?>/<?php echo $enduse; ?>/<?php echo $requestor; ?>/<?php echo $description; ?>/<?php echo $supplier; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>reports/export_unserved/<?php echo $year; ?>/<?php echo $month; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                    <?php } ?>
                                    <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_pr"> 
                                        <span class="fa fa-filter p-l-0"></span> Filter
                                    </a>
                                </div>
                            </div>
                        </div>   
                        <?php if(!empty($filt)){ ?>     
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href='<?php echo base_url(); ?>index.php/reports/unserved_report/<?php echo $year; ?>/<?php echo $month; ?>' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>      
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>PR No.</th>
                                            <th>Purpose</th>
                                            <th>Enduse</th>
                                            <th>Date Of PO</th>
                                            <th>PO No.</th>
                                            <th>Requested By</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Item Description</th>
                                            <th>Status</th>
                                            <th>Date Needed</th>
                                            <th>Supplier </th>
                                            <th>Payment Term</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <th>Remarks</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>                               
                                        <tr>
                                            <?php 
                                                if(!empty($po)){
                                                    //if(!empty($unserved)){
                                                    //foreach($unserved AS $u){
                                                        foreach($po AS $p){ 
                                                            //if($p['pr_details_id']==$u['pr_details_id']){
                                                            /*foreach($po_items AS $items){*/
                                                            //$total = $u['unserved_qty']*$p['unit_price'];
                                            ?>                                     
                                        <tr>
                                            <td><?php echo $p['pr_no']."-".COMPANY;?></td>
                                            <td><?php echo $p['purpose'];?></td>
                                            <td><?php echo $p['enduse'];?></td>
                                            <td><?php echo $p['po_date'];?></td>
                                            <td><?php echo $p['po_no']."-".COMPANY;?></td>
                                            <td><?php echo $p['requested_by'];?></td>
                                            <td><?php echo $p['unserved_qty'];?></td>
                                            <td><?php echo $p['uom'];?></td>
                                            <td><?php echo $p['item'];?></td>
                                            <td><?php echo $p['status']; ?></td>
                                            <td><?php echo (empty($p['date_needed']) ? '' : date('M j, Y', strtotime($p['date_needed']))); ?></td>
                                            <td><?php echo $p['supplier'];?></td>
                                            <td><?php echo $p['terms'];?></td>
                                            <td><?php echo $p['unit_price'];?></td>
                                            <td><?php echo number_format($p['total'],2);?></td>
                                            <td><?php echo $p['notes'];?></td>
                                        </tr> 
                                        <?php } } //} //} //}  ?>
                                        </tr>                    
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function goBack() {
            window.history.back();
        }
    </script>
    
    <!-- Data table area End-->