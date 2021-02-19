    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.2/css/fixedColumns.dataTables.min.css">
    <style type="text/css">
        tr td, tr th{
            white-space: nowrap!important;
            /*border: 1px solid #000;*/
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
    <div id="filter_purch_calendar" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "">
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
                            <input type="requestor" name="requestor" class="form-control">
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
                                <p class="p-l-25">&nbsp;<b style="color:blue"><?php echo $cal_date_from;?> - <?php echo $cal_date_to;?></b></p><!-- (Today) --> 
                                <div class="sparkline8-outline-icon">
                                    <!-- 
                                    <a class="btn btn-custon-three btn-warning" data-toggle="modal" data-target="#pending_recom"> 
                                        <span class="fa fa-tasks" style="padding: 0px"></span> Pending
                                    </a> -->
                                    <?php if(!empty($filt)){ ?>
                                    <a href="<?php echo base_url(); ?>reports/export_purch_calendar/<?php echo $cal_date_from; ?>/<?php echo $cal_date_to; ?>/<?php echo $enduse; ?>/<?php echo $purpose; ?>/<?php echo $requestor; ?>/<?php echo $uom; ?>/<?php echo $description; ?>/<?php echo $supplier; ?>/<?php echo $pr_no; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } else { ?>
                                    <a href="<?php echo base_url(); ?>reports/export_purch_calendar/<?php echo $cal_date_from; ?>/<?php echo $cal_date_to; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } ?>
                                    <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_purch_calendar"> 
                                        <span class="fa fa-filter p-l-0"></span> Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php if(!empty($filt)){ ?>     
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href="<?php echo base_url(); ?>index.php/reports/purch_calendar/<?php echo $cal_date_from; ?>/<?php echo $cal_date_to; ?>" class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>     
                          
                        <!-- <span class='btn btn-success disabled'>Filter Applied</span>, <a href='' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>    -->                 
                              
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Number</th>
                                            <th>Project / Activity</th>
                                            <th>Remarks</th>
                                            <th>PR No/s.</th>
                                            <th>Duaration (# of Days)</th>
                                            <th>Target Start date</th>
                                            <th>Target Completion</th>
                                            <th>Actual Start</th>
                                            <th>Actual Completion</th>
                                            <th>Verified Date Neded</th>
                                            <th>Estimated Price</th>
                                            <th>Est. Total(Materials)</th>
                                            <th>Total (Weekly Schedule)</th>
                                            <th>Jan 10-15</th>
                                            <th>Feb 10-15</th>
                                            <th>March 10-15</th>
                                            <th>April 10-15</th>
                                            <th>Jun 10-15</th>
                                            <th>Jul 10-15</th>
                                            <th>Aug 10-15</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php $x = 1; foreach($purch_calendar AS $pc){
                                            
                                            ?>
                                        <tr>
                                            <td><?php echo $x; ?></td>
                                            <td><?php echo $pc['proj_act']; ?></td>
                                            <td><?php echo $pc['c_remarks']; ?></td>
                                            <td><?php echo $pc['pr_no']."-".COMPANY; ?></td>
                                            <td><?php echo $pc['duration']; ?></td>
                                            <td><?php echo date('F j, Y', strtotime($pc['target_start_date'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($pc['target_completion'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($pc['actual_start'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($pc['actual_completion'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($pc['ver_date_needed'])); ?></td>
                                            <td><?php echo number_format($pc['estimated_price'],2); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                         <?php $x++; } ?>  
                                         
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
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table').DataTable( {
                // scrollY:        "300px",
                scrollX:        true,
                scrollCollapse: true,
                // paging:         false,
                fixedColumns:   {
                    leftColumns: 3
                }
            } );
        } );
    </script>
   <!--  <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

    
    <!-- Data table area End-->