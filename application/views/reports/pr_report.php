<?php $CI =& get_instance(); ?>
    <style type="text/css">
        tr td{
            white-space: nowrap!important;
            padding-right: 5px!important;
        }    
    </style>
    <script type="text/javascript">
        $(document).on("click", ".addremarks", function () {
             var pr_details_id = $(this).data('id');
             var year = $(this).data('year');
             var month = $(this).data('month');
             var remarks = $(this).data('remarks');
             var cancel = $(this).data('cancel');
             var po_offer_id = $(this).data('offerid');
             var status = $(this).data('status');
              var pr_id = $(this).data('prid');
               $(".modal #pr_id").val(pr_id);
              $(".modal #status").val(status);
             $(".modal #pr_details_id").val(pr_details_id);
             $(".modal #year").val(year);
             $(".modal #month").val(month);
             $(".modal #remarks").val(remarks);
             $(".modal #po_offer_id").val(po_offer_id);
        });
    </script>

    <script type="text/javascript">
        $(document).on("click", "#fulfilled_by", function () {
            var pr_details_id = $(this).data('id');
            var year = $(this).data('year');
            var month = $(this).data('month');
            var remarks = $(this).data('remarks');
            var cancel = $(this).data('cancel');
            var po_offer_id = $(this).data('offerid');
            var status = $(this).data('status');
            var pr_id = $(this).data('prid');
            var date_delivered = $(this).data('datedel');
            var supplier = $(this).data('vendorid');
            var company = $(this).data('companyid');
            var unit_price = $(this).data('unitp');
            var qty_delivered = $(this).data('qtydel');
            $(".modal #pr_id").val(pr_id);
            $(".modal #status").val(status);
            $(".modal #pr_details_id1").val(pr_details_id);
            $(".modal #year").val(year);
            $(".modal #month").val(month);
            $(".modal #remarks").val(remarks);
            $(".modal #po_offer_id").val(po_offer_id);
            $(".modal #date_delivered").val(date_delivered);
            $(".modal #supplier").val(supplier);
            $(".modal #company").val(company);
            $(".modal #unit_price").val(unit_price);
            $(".modal #qty_delivered").val(qty_delivered);
        });
    </script>
    <script type="text/javascript">
        $(document).on("click", ".on_recom", function () {
             var pr_details_id = $(this).data('id');
             var year = $(this).data('year');
             var month = $(this).data('month');
             var remarks = $(this).data('remarks');
             var cancel = $(this).data('cancel');
             var po_offer_id = $(this).data('offerid');
             var status = $(this).data('status');
             var recom_unit_price = $(this).data('price');
              var pr_id = $(this).data('prid');
               $(".modal #pr_id").val(pr_id);
              $(".modal #status").val(status);
             $(".modal #pr_details_id").val(pr_details_id);
             $(".modal #year").val(year);
             $(".modal #month").val(month);
             $(".modal #remarks").val(remarks);
             $(".modal #po_offer_id").val(po_offer_id);
             $(".modal #recom_unit_price").val(recom_unit_price);
        });
    </script>
     <script type="text/javascript">
        $(document).on("click", ".calendar", function () {
             var pr_details_id = $(this).data('id');
             var year = $(this).data('year');
             var month = $(this).data('month');
             var remarks = $(this).data('remarks');
             var cancel = $(this).data('cancel');
             var po_offer_id = $(this).data('offerid');
             var status = $(this).data('status');
              var pr_id = $(this).data('prid');
              var ver_date = $(this).data('verify');
              var pr_calendar_id = $(this).data('prcalendar');
              var proj_act_id = $(this).data('proj');
              var estimated_price = $(this).data('estimated');

               $(".modal #pr_id").val(pr_id);
              $(".modal #status").val(status);
             $(".modal #pr_details_id").val(pr_details_id);
             $(".modal #year").val(year);
             $(".modal #month").val(month);
             $(".modal #remarks").val(remarks);
             $(".modal #po_offer_id").val(po_offer_id);
             $(".modal #ver_date_needed").val(ver_date);
             $(".modal #pr_calendar_id").val(pr_calendar_id);
             $(".modal #proj_act_id").val(proj_act_id);
             $(".modal #estimated_price").val(estimated_price);

            if(estimated_price!=''){
                document.getElementById("estimated_price").disabled = true;
            }else{
                document.getElementById("estimated_price").disabled = false;
            }

            if(proj_act_id!=''){
                document.getElementById("proj_act_id").disabled = true;
            }else{
                document.getElementById("proj_act_id").disabled = false;
            }

        });
    </script>   
    <style>
    .class{
        background-color: green;
    }
    </style>
    <div id="filter_pr" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>reports/search_pr/<?php echo $year;?>/<?php echo $month;?>">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">Date Received/Email:</p>
                            <input type="date" name="date_receive" class="form-control">
                        </div> 
                        <div class="form-group">
                            <p class="m-b-0">Purchase Request:</p>
                            <input type="text" name="purchase_request" class="form-control">
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
                            <p class="m-b-0">PR No:</p>
                            <input type="text" name="pr_no" class="form-control">
                        </div>    
                        <div class="form-group">
                            <p class="m-b-0">Requestor:</p>
                            <input type="requestor" name="requestor" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Description:</p>
                            <input type = "text" name="description" class="form-control">
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
        <form name="myform" action="<?php echo base_url(); ?>index.php/reports/insert_changestatus" method="post">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd p-b-0" >
                                <div class="main-sparkline8-hd">
                                    <h1><button onclick="return quitBox('quit');" class=" btn btn-xs btn-success"><span class="fa fa-arrow-left"></span></button>
                                        PR Summary <b style="color:blue"><?php echo $date; ?></b>
                                    </h1>
                                    <small class="p-l-25">&nbsp;PURCHASE REQUEST</small> 
                                    <div class="sparkline8-outline-icon">
                                        <button type="button" class="btn btn-sm btn-warning btn-custon-three" data-toggle="modal" data-target="#legend">Legend</button>
                                        <input type="submit" class="btn btn-success btn-custon-three" name="submit" value="Change Status">
                                        <?php if(!empty($filt)){ ?>
                                            <a href="<?php echo base_url(); ?>reports/export_pr/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $date_receive; ?>/<?php echo $purpose1; ?>/<?php echo $enduse1; ?>/<?php echo $pr_no1; ?>/<?php echo $requestor; ?>/<?php echo $description; ?>/<?php echo $purchase_request; ?>" class="btn btn-custon-three btn-info"> 
                                                <span class="fa fa-upload"></span> Export to Excel
                                            </a>
                                            <a href="<?php echo base_url(); ?>reports/export_pr_summary/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $date_receive; ?>/<?php echo $purpose1; ?>/<?php echo $enduse1; ?>/<?php echo $pr_no1; ?>/<?php echo $requestor; ?>/<?php echo $description; ?>/<?php echo $purchase_request; ?>" class="btn btn-custon-three btn-info"> 
                                                <span class="fa fa-upload"></span> Calapan Export to Excel
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?php echo base_url(); ?>reports/export_pr/<?php echo $year; ?>/<?php echo $month; ?>" class="btn btn-custon-three btn-info"> 
                                                <span class="fa fa-upload"></span> Export to Excel
                                            </a>
                                            <a href="<?php echo base_url(); ?>reports/export_pr_summary/<?php echo $year; ?>/<?php echo $month; ?>" class="btn btn-custon-three btn-info"> 
                                                <span class="fa fa-upload"></span> Calapan Export to Excel
                                            </a>
                                        <?php } ?>
                                        <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_pr"> 
                                            <span class="fa fa-filter p-l-0"></span> Filter
                                        </a>
                                    </div>
                                </div>
                            </div>     
                            <?php if(!empty($filt)){ ?>     
                            <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href='<?php echo base_url(); ?>index.php/reports/pr_report/<?php echo $year; ?>/<?php echo $month; ?>' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                            <?php } ?>         
                            <div class="sparkline8-graph" >
                                <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>                                       
                                            <tr>
                                                <th>On-hold</th>
                                                <th>Proceed</th>
                                                <th>Date Received/ Emailed</th>
                                                <th>Purchase Request</th>
                                                <th>Purpose</th>
                                                <th>Enduse</th>
                                                <th>PR No.</th>
                                                <th>Requestor</th>
                                                <th>WH Stocks</th>
                                                <th>Item No.</th>
                                                <th>Qty</th>
                                                <th>Revised Qty</th>
                                                <th>UOM</th>
                                                <th>Grouping</th>
                                                <th>Description</th>
                                                <th>Status Remarks</th>
                                                <th>Status</th>
                                                <th>Date Needed</th>
                                                <th>Remarks</th>
                                                <th>Cancel Remarks</th>
                                                <th>End User's Comments</th>    
                                                <th align="center" width="15%"><center><span class="fa fa-bars"></span></center></th>										
                                            </tr>
                                           
                                        </thead>
                                        
                                        <tbody>    
                                        <?php if(!empty($pr)){ $x=1; foreach($pr AS $p) { 
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
                                            } ?>
                                            >   

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
                                                <td><?php echo date('F j, Y', strtotime($p['date_prepared'])); ?></td>
                                                <td><?php echo $p['purchase_request']; ?></td>
                                                <td><?php echo $p['purpose']; ?></td>
                                                <td><?php echo $p['enduse']; ?></td>
                                                <td><?php echo $p['pr_no']."-".COMPANY; ?></td>
                                                <td><?php echo $p['requestor']; ?></td>
                                                <td><?php echo $p['wh_stocks']; ?></td>
                                                <td><?php echo $p['item_no']; ?></td>
                                                <td><?php echo $p['qty']; ?></td>
                                                <td ><span style='font-size:11px'><?php echo $p['revised_qty']; ?></span></td>
                                                <td><?php echo $p['uom']; ?></td>
                                                <td><?php echo $p['grouping_id']; ?></td>
                                                <td><?php echo $p['item_description'] . (($p['unserved_qty']!=0) ? " - <span style='color:red; font-size:11px'>UNSERVED ". $p['unserved_qty'] . " " . $p['unserved_uom'] . "</span>" : ""); ?></td> 
                                                <td><?php echo $p['status_remarks']; ?></td>                                         
                                                <td><?php echo $p['status']; ?></td>                                           
                                               
                                                <td><?php echo (empty($p['date_needed']) ? '' : date('M j, Y', strtotime($p['date_needed']))); ?></td>
                                                <td><?php echo ($p['fulfilled_by']==1) ? $p['remarks'] ."<br> (Delivered by ".$p['company']." details: "."<br> - Date: ".date('M j, Y', strtotime($p['date_delivered'])) ."<br> - Supplier: ".$p['supplier'] ."<br> - Unit Price: ".$p['unit_price'] ."<br> - Qty: ".$p['qty_delivered'].")" : $p['remarks'];?></td>
                                                <td><?php echo $p['cancel_remarks'];?></td>
                                                <td></td>
                                                <td align="center">  
                                                    <center>
                                                        <?php if($p['status']!='Fully Delivered' && $p['status']!='Cancelled'  && $p['status']!='Partially Delivered / Cancelled'){ ?>
                                                         <button type="button" class="btn btn-primary btn-xs calendar" data-toggle="modal" data-target="#calendar" title='Calendar' data-id="<?php echo $p['pr_details_id']; ?>" data-year="<?php echo $year; ?>" data-offerid="<?php echo $p['po_offer_id']; ?>" data-month="<?php echo $month; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-status="<?php echo $p['status']; ?>" data-prid="<?php echo $p['pr_id']; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-verify="<?php echo $p['ver_date_needed']; ?>" data-prcalendar="<?php echo $p['pr_calendar_id']; ?>" data-proj="<?php echo $p['proj_act_id']; ?>" data-estimated="<?php echo $p['estimated_price']; ?>">
                                                            <span class="fa fa-calendar"></span>
                                                        </button>
                                                        <?php } ?>
                                                        
                                                        <?php if($p['on_hold']==0 && $p['status']!='Fully Delivered' && $p['status']!='Cancelled' && $p['status']!='Partially Delivered / Cancelled'){ ?>                                                          
                                                        <button type="button" class="btn btn-primary btn-xs on_recom" data-toggle="modal" data-target="#on_recom" title='Recom' data-id="<?php echo $p['pr_details_id']; ?>" data-year="<?php echo $year; ?>" data-offerid="<?php echo $p['po_offer_id']; ?>" data-month="<?php echo $month; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-status="<?php echo $p['status']; ?>" data-prid="<?php echo $p['pr_id']; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-price="<?php echo $p['recom_unit_price'];?>">
                                                            <span class="fa fa-list-ul"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-xs fulfilled" data-toggle="modal" id = "fulfilled_by" data-target="#fulfilled" title='Fulfilled by Sister Company' data-id="<?php echo $p['pr_details_id']; ?>" data-year="<?php echo $year; ?>" data-offerid="<?php echo $p['po_offer_id']; ?>" data-month="<?php echo $month; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-status="<?php echo $p['status']; ?>" data-prid="<?php echo $p['pr_id']; ?>" data-datedel="<?php echo $p['date_delivered']; ?>" data-vendorid="<?php echo $p['supplier_id']; ?>" data-companyid="<?php echo $p['company_id']; ?>" data-unitp="<?php echo $p['unit_price']; ?>" data-qtydel="<?php echo $p['qty_delivered']; ?>" data-remarks="<?php echo $p['remarks']; ?>">
                                                            <span class="fa fa-truck"></span>
                                                        </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-primary btn-xs addremarks" data-toggle="modal" data-target="#addremarks" title='Add Remarks' data-id="<?php echo $p['pr_details_id']; ?>" data-year="<?php echo $year; ?>" data-offerid="<?php echo $p['po_offer_id']; ?>" data-month="<?php echo $month; ?>" data-remarks="<?php echo $p['remarks']; ?>" data-status="<?php echo $p['status']; ?>" data-prid="<?php echo $p['pr_id']; ?>" data-remarks="<?php echo $p['remarks']; ?>">
                                                            <span class="fa fa-plus"></span>
                                                        </button>  
                                                    </center>
                                                </td>
                                            </tr>    
                                        <?php $x++; } } ?> 
                                        <!-- <tr class="orange">
                                            <td colspan="22"><br></td>
                                        </tr>   
                                        <tr class="green">
                                            <td colspan="22"><br></td>
                                        </tr>         
                                        <tr class="yellow">
                                            <td colspan="22"><br></td>
                                        </tr>
                                        <tr class="peach">
                                            <td colspan="22"><br></td>
                                        </tr>
                                        <tr class="blue">
                                            <td colspan="22"><br></td>
                                        </tr>
                                        <tr class="purple">
                                            <td colspan="22"><br></td>
                                        </tr> -->
                                        </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="year" value="<?php echo $year; ?>">
            <input type="hidden" name="month" value="<?php echo $month; ?>">
        </form>
    </div>

    <div class="modal fade" id="legend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <table width="100%" style="margin: 40px">
                    <tr>
                        <td width="40%" style="background-color: #fd9c77"></td>
                        <td width="5%"></td>
                        <td>Recom Items</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="margin:5px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #bcffc7"></td>
                        <td></td>
                        <td>Fully Delivered</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="margin:5px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f7ffb9 "></td>
                        <td></td>
                        <td>Partially Delivered</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="margin:5px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffecd0 "></td>
                        <td></td>
                        <td>Issued PO</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="margin:5px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #d2deff "></td>
                        <td></td>
                        <td>On-hold</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="margin:5px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #eeccff "></td>
                        <td></td>
                        <td>Delivered by another company</td>
                    </tr>
                </table>
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
                <form method='POST' action="<?php echo base_url(); ?>reports/add_remarks">
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
                        <input type='hidden' name='year' id='year'>
                        <input type='hidden' name='month' id='month'>
                        <input type="submit" class="btn btn-primary btn-block" value='Save changes'>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fulfilled" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fulfilled by Sister Company
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/add_sister_company">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Date Delivered:</label>
                            <input placeholder="Date Delivered" name="date_delivered" class="form-control" type="date" id="date_delivered">
                        </div>
                        <div class="form-group">
                        <label>Company:</label>
                            <select name="comp" id="company" class="form-control" cols="2">
                                <option value = "">--Select Company--</option>
                                <?php foreach($company AS $cn){ ?>
                                <option value = "<?php echo $cn->company_id; ?>"><?php echo $cn->company_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Supplier:</label>
                            <select name="supp" id="supplier" class="form-control" cols="2">
                                <option value = "">--Select Supplier--</option>
                                <?php foreach($supplier AS $sp){ ?>
                                <option value = "<?php echo $sp->vendor_id; ?>"><?php echo $sp->vendor_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Unit Price:</label>
                                    <input type="number" name="unit_price" class="form-control" id="unit_price" placeholder="Unit Price">
                                </div>
                                <div class="col-lg-6">
                                    <label>Quantity Delivered:</label>
                                    <input type="text" name="qty_delivered" class="form-control" id="qty_delivered" placeholder="Quantity Delivered">
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <input type='hidden' name='status' id='status'>
                        <input type='hidden' name='pr_details_id' id='pr_details_id1'>
                        <input type='hidden' name='pr_id' id='pr_id'>
                        <input type='hidden' name='po_offer_id' id='po_offer_id'>
                        <input type='hidden' name='year' id='year'>
                        <input type='hidden' name='month' id='month'>                       
                        <input type="submit" class="btn btn-primary btn-block" value='Save changes'>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="on_recom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">For Recommendation
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/on_recom">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                <label>Date From:</label>
                                    <input placeholder="Date From" name="recom_date_from" class="form-control" type="date" onfocus="(this.type='date')" id="recom_date_from">
                                </div>
                                <div class="col-lg-6">
                                <label>Date To:</label>
                                    <input placeholder="Date To" name="recom_date_to" class="form-control" type="date" onfocus="(this.type='date')" id="recom_date_to">
                                </div>
                            </div>   
                        </div>
                        <div class="form-group">
                            <label>Delivery lead Time / Work Duration:</label>
                        <textarea class="form-control" rows="5" name='work_duration' id='work_duration' placeholder="Delivery lead Time / Work Duration"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                 <div class="col-lg-6">
                        <label>Terms:</label>
                            <select name="terms" class="form-control" cols="2">
                                <option value = "">--Select Terms--</option>
                                <?php foreach($terms AS $t){ ?>
                                <option value = "<?php echo $t->terms_id; ?>"><?php echo $t->terms?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Unit Price:</label>
                                    <input placeholder="Unit Price" name="recom_unit_price" class="form-control" type="text" id="recom_unit_price">
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type='hidden' name='status' id='status'>
                        <input type='hidden' name='pr_details_id' id='pr_details_id'>
                        <input type='hidden' name='pr_id' id='pr_id'>
                        <input type='hidden' name='po_offer_id' id='po_offer_id'>
                        <input type='hidden' name='year' id='year'>
                        <input type='hidden' name='month' id='month'>                       
                        <input type="submit" class="btn btn-primary btn-block" value='Save changes'>                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="calendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Calendar
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/calendar">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Verified Date Needed:</label>
                                    <input placeholder="Verified Date Needed" name="ver_date_needed" class="form-control" type="text" onfocus="(this.type='date')" id="ver_date_needed" required="required">
                                </div>
                                <div class="col-lg-6">
                                    <label>Estimated Price:</label>
                                    <input placeholder="Estimated Price" name="estimated_price" class="form-control" type="text" id="estimated_price" required="required">
                                </div>
                            </div>   
                        </div>
                        <div class="form-group">
                            <label>Project / Activity:</label>
                            <select name="proj_act" id="proj_act_id" class="form-control" cols="2">
                                <option value = "">--Select Project / Activity--</option>
                                <?php foreach($proj_act AS $pa){ ?>
                                <option value = "<?php echo $pa->proj_act_id; ?>"><?php echo $pa->proj_activity?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <div class="modal-footer">
                        <input type='hidden' name='pr_calendar_id' id='pr_calendar_id'>
                        <input type='hidden' name='status' id='status'>
                        <input type='hidden' name='pr_details_id' id='pr_details_id'>
                        <input type='hidden' name='pr_id' id='pr_id'>
                        <input type='hidden' name='po_offer_id' id='po_offer_id'>
                        <input type='hidden' name='year' id='year'>
                        <input type='hidden' name='month' id='month'>                         
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