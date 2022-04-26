    <?php $CI =& get_instance(); ?>
    <div id="filter_pr" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>reports/search_joi/<?php echo $year;?>/<?php echo $month;?>">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">JOR No:</p>
                            <select name="jor_no" class="form-control">
                                <option value = "">--Select JOR Number--</option>
                                <?php 
                                    foreach($jor_no AS $pr){ 
                                        $jo=$pr->jo_no;
                                        if($jo!=''){
                                            $jo_no=$jo;
                                        }else{
                                            $jo_no=$pr->user_jo_no;
                                        }
                                ?>
                                <option value = "<?php echo $pr->jor_id;?>"><?php echo $jo_no."-".COMPANY; ?></option>
                                <?php } ?>
                            </select>
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Date of JO:</p>
                            <input type="date" name="date_joi" class="form-control">
                        </div>    
                        <div class="form-group">
                            <p class="m-b-0">JO No:</p>
                            <input type="text" name="joi_no" class="form-control">
                        </div> 
                        <div class="form-group">
                            <p class="m-b-0">Project Title:</p>
                            <input type="text" name="project_title" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Requestor:</p>
                            <input name="requestor" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Scope of Work:</p>
                            <input name="scope_of_work" class="form-control">
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
                                    JOI Summary <b style="color:blue"><?php echo $date; ?></b>
                                </h1>
                                <small class="p-l-25">&nbsp;JOB ORDER</small> 
                                <div class="sparkline8-outline-icon">
                                    <?php if(!empty($filt)){ ?>
                                        <a href="<?php echo base_url(); ?>reports/export_joi/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $jor_no1; ?>/<?php echo $date_joi; ?>/<?php echo $joi_no; ?>/<?php echo $project_title; ?>/<?php echo $requestor; ?>/<?php echo $scope_of_work; ?>/<?php echo $supplier; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>reports/export_joi/<?php echo $year; ?>/<?php echo $month; ?>" class="btn btn-custon-three btn-info"> 
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
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href='<?php echo base_url(); ?>index.php/reports/joi_report/<?php echo $year; ?>/<?php echo $month; ?>' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>      
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>JOR No.</th>
                                            <th>Project Title</th>
                                            <th>Date Of JO</th>
                                            <th>JO No.</th>
                                            <th>Requested By</th>
                                            <th>JO Qty</th>
                                            <th>Received Qty</th>
                                            <th>UOM</th>
                                            <th>Scope of Work</th>
                                            <th>Materials Offer</th>
                                            <th>Materials Qty</th>
                                            <th>Materials UOM</th>
                                            <th>Status</th>
                                            <th>Status Remarks</th>
                                            <th>Supplier </th>
                                            <th>Payment Term</th>
                                            <th>Materials Unit Price</th>
                                            <th>Materials Total Price</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <th>Remarks</th>										
                                        </tr>
                                    </thead>
                                    <style type="text/css">
                                        tr.fd td{
                                            background-color: #b9ffb9;
                                        }

                                        tr.cd td{
                                            background-color: #cacaca;
                                        }

                                        tr.pi td{
                                            background-color: #ffecd0;
                                        }
                                    </style>
                                    <tbody>                               
                                        <tr>
                                            <?php 
                                            if(!empty($po)){
                                            foreach($po AS $p){ 
                                                /*foreach($po_items AS $items){*/
                                                    $total = $p['joi_qty']*$p['unit_price'];
                                                    $mat_total = $p['materials_qty']*$p['materials_unitprice'];
                                                    $jo_issue=$CI->like($p['status'], "JO Issued");
                                        ?>                                     
                                        <!-- <tr
                                        <?php if($p['served']=='1' && $p['joi_qty'] > $p['qty']){ 
                                            echo "class='pi'";
                                        } else if($p['served']=='1'){
                                            echo "class='fd'";
                                        }else if($p['cancelled']=='1') {
                                            echo "class='cd'";
                                        }else if($p['status']=='JO Issued') {
                                            echo "class='pi'";
                                        } ?>> -->
                                        <tr
                                        <?php if($p['jor_status']=='Fully Delivered' && $p['cancelled']=='0'){
                                                echo "class='green'";
                                            } else if($p['jor_status']=='Partially Delivered' && $p['cancelled']=='0') {
                                                echo "class='yellow'";
                                            } else if($p['status']=='Cancelled') {
                                                echo "class='cd'";
                                            } else if($p['status']=='Partially Delivered / Cancelled') {
                                                echo "class='cd'";
                                            }else if($jo_issue=='1') {
                                                echo "class='peach'";
                                            }
                                        ?>>
                                            <td><?php echo $p['jo_no']."-".COMPANY." / ".$p['user_jo_no'];?></td>
                                            <td><?php echo $p['project_title'];?></td>
                                            <td><?php echo $p['joi_date'];?></td>
                                            <td><?php echo $p['joi_no']."-".COMPANY;?></td>
                                            <td><?php echo $p['requestor'];?></td>
                                            <td><?php echo $p['joi_qty'];?></td>
                                            <td><?php echo $p['qty'];?></td>
                                            <td><?php echo $p['uom'];?></td>
                                            <td><?php echo $p['item'];?></td>
                                            <td><?php echo $p['materials_offer'];?></td>
                                            <td><?php echo $p['materials_qty'];?></td>
                                            <td><?php echo $p['materials_unit'];?></td>
                                            <td><?php echo ($p['jor_status']!='' && $p['cancelled']=='0') ? $p['jor_status'] : $p['status']; ?></td>
                                            <td><?php echo ($p['jor_status_remarks']!='' && $p['cancelled']=='0') ? $p['jor_status_remarks'] : $p['status_remarks']; ?></td>
                                            <td><?php echo $p['supplier'];?></td>
                                            <td><?php echo $p['terms'];?></td>
                                            <td><?php echo $p['materials_unitprice'];?></td>
                                            <td><?php echo $p['materials_currency'] . " ".number_format($mat_total,2);?></td>
                                            <td><?php echo $p['currency'] . " ".$p['unit_price'];?></td>
                                            <td><?php echo $p['currency'] . " ".number_format($total,2);?></td>
                                            <td><?php echo $p['notes'];?></td>
                                        </tr> 
                                        <?php } } ?>
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