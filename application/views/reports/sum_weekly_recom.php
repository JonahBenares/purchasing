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
    <script type="text/javascript">
        $(document).on("click", ".addremarks", function () {
            var pr_details_id = $(this).data('id');
            var to = $(this).data('to');
            var from = $(this).data('from');
            var remarks = $(this).data('remarks');
            var cancel = $(this).data('cancel');
            var po_offer_id = $(this).data('offerid');
            var status = $(this).data('status');
            var pr_id = $(this).data('prid');
            $(".modal #pr_id").val(pr_id);
            $(".modal #status").val(status);
            $(".modal #pr_details_id").val(pr_details_id);
            $(".modal #date_to").val(to);
            $(".modal #date_from").val(from);
            $(".modal #remarks").val(remarks);
            $(".modal #po_offer_id").val(po_offer_id);
        });
    </script>
    <div id="filter_weekly_recom" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>reports/search_weekly_recom/<?php echo $recom_date_from;?>/<?php echo $recom_date_to;?>">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">PR Number:</p>
                            <input type="text" name="pr_no" class="form-control">
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
                        <input type="hidden" name="recom_date_from" value = "<?php echo $recom_date_from; ?>">            
                        <input type="hidden" name="recom_date_to" value = "<?php echo $recom_date_to; ?>">                       
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area m-t-15 ">
        <form name="myform" action="<?php echo base_url(); ?>index.php/reports/insert_changestatus_weekly" method="post">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd p-b-0" >
                                <div class="main-sparkline8-hd">
                                    <h1><button onclick="return quitBox('quit');" class=" btn btn-xs btn-success"><span class="fa fa-arrow-left"></span></button>
                                        Summary of Weekly Recommendations 
                                    </h1>
                                    <p class="p-l-25">&nbsp;<b style="color:blue"> <?php echo $recom_date_from;?> - <?php echo $recom_date_to;?></b></p> 
                                    <div class="sparkline8-outline-icon">
                                        <?php //if(!empty($pr)){ foreach($pr AS $p) { ?> 
                                        <input type="submit" class="btn btn-success btn-custon-three" name="submit" value="Change Status">
                                        <a class="btn btn-custon-three btn-warning" data-toggle="modal" data-target="#pending_recom"> 
                                            <span class="fa fa-tasks" style="padding: 0px"></span> Pending
                                        </a>
                                        <?php if(!empty($filt)){ ?>
                                        <a href="<?php echo base_url(); ?>reports/export_weekly_recom/<?php echo $recom_date_from; ?>/<?php echo $recom_date_to; ?>/<?php echo $enduse; ?>/<?php echo $purpose; ?>/<?php echo $requestor; ?>/<?php echo $uom; ?>/<?php echo $description; ?>/<?php echo $supplier; ?>/<?php echo $pr_no; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>reports/export_weekly_recom/<?php echo $recom_date_from; ?>/<?php echo $recom_date_to; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                        <?php } ?>
                                        <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_weekly_recom"> 
                                            <span class="fa fa-filter p-l-0"></span> Filter
                                        </a>
                                    </div>
                                </div>
                            </div>  
                            <?php if(!empty($filt)){ ?>     
                            <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href="<?php echo base_url(); ?>index.php/reports/sum_weekly_recom/<?php echo $recom_date_from; ?>/<?php echo $recom_date_to; ?>" class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                            <?php } ?>    
                              
                            <!-- <span class='btn btn-success disabled'>Filter Applied</span>, <a href='' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>    -->                 
                                  
                            <div class="sparkline8-graph" >
                                <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>                                       
                                            <tr>
                                                <th>On-hold</th>
                                                <th>Proceed</th>
                                                <th>Recom Date</th>
                                                <th>Recom Date From</th>
                                                <th>Recom Date To</th>
                                                <th>Enduse</th>
                                                <th>Requested by</th>
                                                <th>QTY as per PR</th>
                                                <th>QTY as per Recom</th>
                                                <th>UOM</th>
                                                <th>Description</th>
                                                <th>Supplier</th>
                                                <th>Status Remarks</th>
                                                <th>Status</th>
                                                <th>Site PR/JO No.</th>
                                                <th>Delivery Lead Time/ Work Duration</th>
                                                <th>Unit Price (Peso)</th>
                                                <th>Total Peso</th>
                                                <th>15 Days PDC</th>
                                                <th>30 Days PDC</th>
                                                <th>60 Days PDC</th>
                                                <th>Terms</th>	
                                                <th align="center" width="10%"><span class="fa fa-bars"></span></th>								
                                            </tr>                                       
                                        </thead>                                    
                                        <tbody>
                                            <?php 
                                                $x=1;
                                                foreach($weekly_recom AS $p){ 
                                                    $po_issue=$CI->like($p['status'], "PO Issued");
                                                    $delivered_by=$CI->like($p['status'], "Delivered by");
                                            ?>
                                            <tr
                                            <?php if($p['status']=='Fully Delivered'){
                                                echo "class='green'";
                                            } else if($p['status']=='Partially Delivered') {
                                                echo "class='yellow'";
                                            } else if($p['status']=='Cancelled') {
                                                echo "class='cd'";
                                            } else if($p['status']=='Partially Delivered / Cancelled') {
                                                echo "class='cd'";
                                            }else if($p['status']=='For Recom') {
                                                echo "class='orange'";
                                            } else if($p['status']=='On-Hold') {
                                                echo "class='blue'";
                                            }else if($po_issue=='1') {
                                                echo "class='peach'";
                                            }else if($delivered_by=='1') {
                                                echo "class='purple'";
                                            } ?>>
                                                <?php if($p['status']!='Fully Delivered' && $p['status']!='Cancelled' && $p['on_hold']==0){ ?>
                                                <td><input type="checkbox" name="onhold[]" id="onhold<?php echo $x; ?>" value="<?php echo $p['pr_details_id'];?>" class="form-control" style="width: 50%" <?php echo ((strpos($p['on_hold'], "1") !== false) ? ' checked' : '');?> onclick="hidecheck(<?php echo $x; ?>);"></td>
                                                <td></td>
                                                <?php } else if($p['status']!='Fully Delivered' && $p['status']!='Cancelled' && $p['on_hold']==1){ ?>
                                                <td><input type="checkbox" name="onhold[]" id="onhold<?php echo $x; ?>" value="<?php echo $p['pr_details_id'];?>" class="form-control" style="width: 50%;pointer-events: none" <?php echo ((strpos($p['on_hold'], "1") !== false) ? ' checked' : '');?> onclick="hidecheck(<?php echo $x; ?>);"></td>
                                                <td><input type="checkbox" name="proceed[]" id="proceed<?php echo $x; ?>" value="<?php echo $p['pr_details_id'];?>" class="form-control" style="width: 50%" <?php echo ((strpos($p['on_hold'], "0") !== false) ? ' checked' : '');?> onclick="hidecheck(<?php echo $x; ?>);"></td>
                                                <?php }else{ ?>
                                                <td></td>
                                                <td></td>
                                                <?php } ?>
                                                <td><?php echo date("F d, Y",strtotime($p['recom_date'])); ?></td>
                                                <td><?php echo date("F d, Y",strtotime($p['recom_date_from'])); ?></td>
                                                <td><?php echo date("F d, Y",strtotime($p['recom_date_to'])); ?></td>
                                                <td class="nowrap"><?php echo $p['enduse']; ?></td>
                                                <td><?php echo $p['requestor']; ?></td>
                                                <td><?php echo $p['quantity']; ?></td>
                                                <td><?php echo $p['recom_qty']; ?></td>
                                                <td><?php echo $p['uom']; ?></td>
                                                <td><?php echo $p['item_description']; ?></td>
                                                <td><?php echo $p['supplier']; ?></td>
                                                <td><?php echo $p['status_remarks']; ?></td>
                                                <td><?php echo $p['status']; ?></td>
                                                <td><?php echo $p['pr_no']."-".COMPANY; ?></td>
                                                <td><?php echo $p['work_duration']; ?></td>
                                                <td><?php echo number_format($p['recom_unit_price'],2); ?></td>
                                                <td><?php echo ($p['terms']!="15 days PDC" || $p['terms']!="30 days PDC" || $p['terms']!="60 days PDC" || $p['terms']=="") ? number_format($p['total'],2) : '0.00'; ?></td>
                                                <td><?php echo ($p['terms']=="15 days PDC") ? number_format($p['total'],2) : '0.00';?></td>
                                                <td><?php echo ($p['terms']=="30 days PDC") ? number_format($p['total'],2) : '0.00';?></td>
                                                <td><?php echo ($p['terms']=="60 days PDC") ? number_format($p['total'],2) : '0.00';?></td>
                                                <td><?php echo $p['terms']; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs addremarks" data-toggle="modal" data-target="#addremarks" title='Add Remarks' data-id="<?php echo $p['pr_details_id']; ?>" data-from="<?php echo $recom_date_from; ?>" data-offerid="<?php echo $p['po_offer_id']; ?>" data-to="<?php echo $recom_date_to; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-status="<?php echo $p['status']; ?>" data-prid="<?php echo $p['pr_id']; ?>" data-remarks="<?php echo $p['remarks']; ?>">
                                                        <span class="fa fa-plus"></span>
                                                    </button>
                                                </td>
                                            </tr> 
                                            <?php $x++; }  ?>       
                                        </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="date_recom_from" value="<?php echo $recom_date_from; ?>">
            <input type="hidden" name="date_recom_to" value="<?php echo $recom_date_to; ?>">
        </form>
    </div>
    <div class="modal fade" id="pending_recom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Summary of Pending Weekly Recommendation
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                            
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/generate_pending_weekly_recom_report" target='_blank'>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <small>Date From:</small>
                                <input placeholder="Date From" name="date_recom_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                            </div>
                            <div class="col-lg-6">
                                <small>Date To:</small>
                                <input placeholder="Date To" name="date_recom_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary btn-block" value='Proceed'>
                        <!--<a href="<?php echo base_url(); ?>index.php/reports/pending_weekly_recom"  class="btn btn-primary " target="_blank">Proceed</a>-->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addremarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Remarks
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/add_remarks_weekly">
                    <div class="modal-body">
                        <div class="form-group">
                        <textarea class="form-control" rows="5" name='remarks' id='remarks'></textarea>
                        </div>
                        <div class="form-group">
                        <textarea class="form-control" rows="5" name='cancel_remarks' id='cancel_remarks' placeholder="Cancel Remarks"></textarea>
                        </div>
                        <div class="form-group">
                        <center>
                          <input type = "checkbox" class="form-control" name='cancel' value = "1" ><span style='font-size:11px;'>Tick the box above if you need to cancel remaining items (applicable for partially delivered items only)</span>
                        </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type='hidden' name='status' id='status'>
                        <input type='hidden' name='pr_details_id' id='pr_details_id'>
                        <input type='hidden' name='pr_id' id='pr_id'>
                        <input type='hidden' name='po_offer_id' id='po_offer_id'>
                        <input type='hidden' name='recom_date_from' id='date_from'>
                        <input type='hidden' name='recom_date_to' id='date_to'>
                        <input type="submit" class="btn btn-primary btn-block" value='Save changes'>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function goBack() {
            window.history.back();
        }
        function hidecheck(count) {
             if(document.getElementById('proceed'+count).checked){
                $('#onhold'+count).attr('disabled','disabled');
             }else{
                $('#onhold'+count).removeAttr('disabled');
             }
        }
    </script>

    
    <!-- Data table area End-->