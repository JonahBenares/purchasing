<?php $CI =& get_instance(); ?>
<style type="text/css">
    tr td{
        white-space: nowrap!important;
    }
    tr td.nowrap{
        white-space: normal;
    }
    tr.fd td{
        background-color: #b9ffb9;
    }
    tr.pd td{
        background-color: #f3ff9e;
    }
    tr.cd td{
        background-color: #cacaca;
    }
</style>
<div id="filter_items" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1">
                <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <form method="POST" action = "<?php echo base_url();?>reports/search_item_calendar/<?php echo $proj_act_id;?>/<?php echo $year;?>">
                <div class="modal-body-lowpad">         
                    <div class="form-group">
                        <p class="m-b-0">Date Needed:</p>
                        <input type="date" name="ver_date_needed" class="form-control">
                    </div>                
                    <div class="form-group">
                        <p class="m-b-0">PR Number:</p>
                        <select name="pr_no" class="form-control">
                            <option value = "">--Select PR Number--</option>
                            <?php foreach($pr_list AS $pr){ ?>
                            <option value = "<?php echo $pr->pr_id;?>"><?php echo $pr->pr_no."-".COMPANY; ?></option>
                            <?php } ?>
                        </select>
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
                        <input type="text" name="requestor" class="form-control">
                    </div>   
                    <div class="form-group">
                        <p class="m-b-0">Item Description:</p>
                        <input type = "text" name="description" class="form-control">
                    </div>  
                     <div class="form-group">
                     <label>Supplier:</label>
                        <select name="supplier" class="form-control" cols="2">
                            <option value = "">--Select Supplier--</option>
                            <?php foreach($vendors AS $sp){ ?>
                            <option value = "<?php echo $sp->vendor_id; ?>"><?php echo $sp->vendor_name?></option>
                            <?php } ?>
                        </select>
                    </div>                 
                    <center>    
                    <input type="hidden" name="proj_act_id" value = "<?php echo $proj_act_id; ?>">            
                    <input type="hidden" name="year" value = "<?php echo $year; ?>">                       
                        <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
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
                                    Purchasing Calendar <small>Schedule of Activities</small>
                                </h1>
                                <div class="sparkline8-outline-icon">
                                    <?php if(!empty($filt)){ ?>
                                    <a href="<?php echo base_url(); ?>reports/export_calendar_item/<?php echo $proj_act_id; ?>/<?php echo $year; ?>/<?php echo $ver_date_needed; ?>/<?php echo $purpose; ?>/<?php echo $enduse; ?>/<?php echo $pr_no; ?>/<?php echo $supplier; ?>/<?php echo $requestor; ?>/<?php echo $description; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } else { ?>
                                    <a href="<?php echo base_url(); ?>reports/export_calendar_item/<?php echo $proj_act_id; ?>/<?php echo $year; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } ?>
                                    <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_items"> 
                                        <span class="fa fa-filter p-l-0"></span> Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php if(!empty($filt)){ ?>    
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt; ?>, <a href="<?php echo base_url(); ?>index.php/reports/getCalendar_disp/<?php echo $proj_act_id; ?>/<?php echo $year ?>" class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a> 
                        <?php } ?>
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <tr>
                                    <td>Date Needed</td>
                                    <td>PR No.</td>
                                    <td>Purpose</td>
                                    <td>Enduse</td>
                                    <td>Requestor</td>
                                    <td>Item Desc</td>
                                    <td>Qty</td>
                                    <td>Uom</td>
                                    <td>Supplier</td>
                                    <td>Status Remarks</td>
                                    <td>Status</td>
                                    <td>Unit Price</td>
                                    <td>Estimated Price</td>
                                    <td>Estimated Total Price</td>
                                    <td>Actual Price</td>
                                    <td>Total Actual Price</td>
                                </tr>
                                 <?php 
                                    if(!empty($purch)){ foreach($purch AS $pc){ 
                                        $jo_issue=$CI->like($pc['status'], "PO Issued");
                                 ?>
                                <tr
                                <?php 
                                    if($pc['status']=='Fully Delivered'){
                                        echo "class='green'";
                                    } else if($pc['status']=='Partially Delivered') {
                                        echo "class='yellow'";
                                    } else if($pc['status']=='Cancelled') {
                                        echo "class='cd'";
                                    } else if($pc['status']=='Partially Delivered / Cancelled') {
                                        echo "class='cd'";
                                    }else if($jo_issue=='1') {
                                        echo "class='peach'";
                                    }
                                ?>>
                                    <td><?php echo date('F j, Y', strtotime($pc['ver_date_needed'])); ?></td>
                                    <td><?php echo $pc['pr_no']; ?></td>
                                    <td><?php echo $pc['purpose']; ?></td>
                                    <td><?php echo $pc['enduse']; ?></td>
                                    <td><?php echo $pc['requestor']; ?></td>
                                    <td><?php echo $pc['item_description']; ?></td>
                                    <td><?php echo $pc['quantity']; ?></td>
                                    <td><?php echo $pc['uom']; ?></td>
                                    <td><?php echo $pc['supplier']; ?></td>
                                    <td><?php echo $pc['status_remarks']; ?></td>
                                    <td><?php echo $pc['status']; ?></td>
                                    <td align="right"><?php echo number_format($pc['unit_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($pc['estimated_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($pc['estimated_total_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($pc['actual_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($pc['actual_total_price'],2); ?></td>
                                </tr>
                                <?php  } ?> 
                                <tr>
                                    <td colspan="11" align="right">Total:</td>
                                    <td colspan="1" align="right"><?php echo number_format($pc['total_unit'],2); ?></td>
                                    <td colspan="1" align="right"><?php echo number_format($pc['total_est'],2); ?></td>
                                    <td colspan="1" align="right"><?php echo number_format($pc['total_ep'],2); ?></td>
                                    <td colspan="1" align="right"><?php echo number_format($pc['total_actual'],2); ?></td>
                                    <td colspan="1" align="right"><?php echo number_format($pc['total_actualp'],2); ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>