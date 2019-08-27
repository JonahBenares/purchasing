    <script type="text/javascript">
        $(document).on("click", ".addremarks", function () {
             var pr_details_id = $(this).data('id');
             var year = $(this).data('year');
             var month = $(this).data('month');
             var remarks = $(this).data('remarks');
             $(".modal #pr_details_id").val(pr_details_id);
             $(".modal #year").val(year);
             $(".modal #month").val(month);
             $(".modal #remarks").val(remarks);
          
        });
    </script>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1><button onclick="goBack()" class=" btn btn-xs btn-success"><span class="fa fa-arrow-left"></span></button>
                                    PR Summary <b style="color:blue"><?php echo $date; ?></b>
                                </h1>
                                <small class="p-l-25">&nbsp;PURCHASE REQUEST</small> 
                                <div class="sparkline8-outline-icon">
                                    <?php if(!empty($filt)){ ?>
                                        <a href="<?php echo base_url(); ?>reports/export_pr/<?php echo $year; ?>/<?php echo $month; ?>/<?php echo $date_receive; ?>/<?php echo $purpose1; ?>/<?php echo $enduse1; ?>/<?php echo $pr_no1; ?>/<?php echo $requestor; ?>/<?php echo $description; ?>" class="btn btn-custon-three btn-info"> 
                                            <span class="fa fa-upload"></span> Export to Excel
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>reports/export_pr/<?php echo $year; ?>/<?php echo $month; ?>" class="btn btn-custon-three btn-info"> 
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
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href='<?php echo base_url(); ?>index.php/reports/pr_report/<?php echo $year; ?>/<?php echo $month; ?>' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>         
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                <table class="table-bordered" width="200%">
                                    <thead>
                                       
                                        <tr>
                                            <th>Date Received/ Emailed</th>
                                            <th>Purpose</th>
                                            <th>Enduse</th>
                                            <th>PR No.</th>
                                            <th>Requestor</th>
                                            <th>WH Stocks</th>
                                            <th>Item NO.</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Description</th>
                                            
                                            <th>Status Remarks</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                            <th>End User's Comments</th>	
                                            <th align="center"><span class="fa fa-bars"></span></th>										
                                        </tr>
                                       
                                    </thead>
                                    <tbody>    
                                    <?php if(!empty($pr)){ foreach($pr AS $p) { ?>                      
                                        <tr>
                                            <td><?php echo date('F j, Y', strtotime($p['date_prepared'])); ?></td>
                                            <td><?php echo $p['purpose']; ?></td>
                                            <td><?php echo $p['enduse']; ?></td>
                                            <td><?php echo $p['pr_no']; ?></td>
                                            <td><?php echo $p['requestor']; ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo $p['qty']; ?></td>
                                            <td><?php echo $p['uom']; ?></td>
                                            <td><?php echo $p['item_description'] . (($p['unserved_qty']!=0) ? " - <span style='color:red; font-size:11px'>UNSERVED ". $p['unserved_qty'] . " " . $p['unserved_uom'] . "</span>" : ""); ?></td>
                                            
                                            <td><?php echo $p['status_remarks']; ?></td>
                                            <td><?php echo $p['status']; ?></td>
                                            <td><?php echo $p['remarks'];?></td>
                                            <td></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs addremarks" data-toggle="modal" data-target="#addremarks" title='Add Remarks' data-id="<?php echo $p['pr_details_id']; ?>" data-year="<?php echo $year; ?>" data-month="<?php echo $month; ?>" data-remarks="<?php echo $p['remarks']; ?>">
                                                        <span class="fa fa-plus"></span>
                                                    </button>                                                 
                                                </div>
                                            </td>
                                        </tr>    
                                    <?php } } ?>               
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
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
                        <textarea class="form-control" rows="5" name='remarks' id='remarks'></textarea>
                    </div>
                    <div class="modal-footer">
                        <input type='hidden' name='pr_details_id' id='pr_details_id'>
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
    </script>

    
    <!-- Data table area End-->