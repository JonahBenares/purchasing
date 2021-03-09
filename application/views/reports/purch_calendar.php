   <?php 
    $CI =& get_instance();  

?>
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
        .modal-lg {
            width: 1200px;
        }
    </style>
    <script type="text/javascript">
        $(document).on("click", "#show", function () {
             var proj_act_id = $(this).data('id');
             var pr_no = $(this).data('pr');
               $("#proj_act_id").val(proj_act_id);
               $("#pr_no").html(pr_no);
        });
    </script>
    <!-- <div id="filter_purch_calendar" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url(); ?>reports/search_purch_calendar">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">PR Number:</p>
                            <input placeholder="PR Number" type="text" name="pr_no" class="form-control">
                        </div> 
                        <div class="form-group">
                            <label>Project / Activity:</label>
                            <select name="proj_act" class="form-control" cols="2">
                                <option value = "">--Select Project / Activity--</option>
                                <?php foreach($proj_act AS $pa){ ?>
                                <option value = "<?php echo $pa->proj_act_id; ?>"><?php echo $pa->proj_activity?></option>
                                <?php } ?>
                            </select>
                        </div> 
                        <div class="form-group">
                            <p class="m-b-0">Remarks:</p>
                            <input placeholder="Remarks" type="text" name="c_remarks" class="form-control">
                        </div>    
                        <div class="form-group">
                            <p class="m-b-0">Verified Date Needed:</p>
                            <input placeholder="Verified Date Needed" type="text" onfocus="(this.type='date')" id="date" name="ver_date_needed" class="form-control">
                        </div>
                                                <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Target Start Date:</label>
                                    <input placeholder="Target Start Date" name="target_start_date" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                </div>
                                <div class="col-lg-6">
                                    <label>Target Completion:</label>
                                    <input placeholder="Target Completion" name="target_completion" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                </div>
                            </div>   
                        </div>
                         <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Actual Start:</label>
                                    <input placeholder="Actual Start" name="actual_start" class="form-control" type="text" onfocus="(this.type='date')" id="date" >
                                </div>
                                <div class="col-lg-6">
                                    <label>Actual Completion:</label>
                                    <input placeholder="Actual Completion" name="actual_completion" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                </div>
                            </div>   
                        </div>                      
                        <center>    
                        <input type="hidden" name="year" value = "<?php echo $year; ?>">                                   
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
        <!-- <div id="updateVerDate" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-pencil"></span>Update</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url(); ?>reports/update_ver_date_needed">
                    <div class="modal-body-lowpad">                           
                        <div class="form-group">
                            <p class="m-b-0">Verified Date Needed:</p>
                            <input type="text" onfocus="(this.type='date')" id="ver_date_needed" name="ver_date_needed" class="form-control">
                        </div>                                             
                        <center>
                        <input type = "hidden" name = "pr_calendar_id" id="pr_calendar_id">                         
                        <input type = "hidden" name = "year" value="<?php echo $year; ?>">                         
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Update">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
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
                             <!--    <p class="p-l-25">&nbsp;<b style="color:blue"><?php echo $cal_date_from;?> - <?php echo $cal_date_to;?></b></p> --><!-- (Today) --> 
                                <div class="sparkline8-outline-icon">
                                    <!-- 
                                    <a class="btn btn-custon-three btn-warning" data-toggle="modal" data-target="#pending_recom"> 
                                        <span class="fa fa-tasks" style="padding: 0px"></span> Pending
                                    </a> -->
                                <!--     <?php if(!empty($filt)){ ?>
                                    <a href="<?php echo base_url(); ?>reports/export_purch_calendar/<?php echo $cal_date_from; ?>/<?php echo $cal_date_to; ?>/<?php echo $pr_no; ?>/<?php echo $proj_act; ?>/<?php echo $c_remarks; ?>/<?php echo $ver_date_needed; ?>/<?php echo $target_start_date; ?>/<?php echo $target_completion; ?>/<?php echo $actual_start; ?>/<?php echo $actual_completion; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } else { ?>
                                    <a href="<?php echo base_url(); ?>reports/export_purch_calendar/<?php echo $cal_date_from; ?>/<?php echo $cal_date_to; ?>" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload"></span> Export to Excel
                                    </a>
                                    <?php } ?> -->
                                   <!-- <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_purch_calendar"> 
                                        <span class="fa fa-filter p-l-0"></span> Filter
                                    </a> -->
                                </div>
                            </div>
                        </div>
                     <?php if(!empty($filt)){ ?>     
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?><a href="<?php echo base_url(); ?>index.php/reports/purch_calendar/<?php echo $year; ?>" class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>
                          
                        <!-- <span class='btn btn-success disabled'>Filter Applied</span>, <a href='' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>    -->                 
                            <?php   

                            $firstDayOfYear = mktime(0, 0, 0, 1, 1, $year);
                            $nextMonday     = strtotime('monday', $firstDayOfYear);
                            $nextSaturday    = strtotime('saturday', $nextMonday);

                        
                            ?>
    
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>Item Number</th>
                                            <th>Project / Activity</th>
                                            <th>Remarks</th>
                                            <th>PR No/s.</th>
                                            <th>Duration (# of Days)</th>
                                            <th>Target Start date</th>
                                            <th>Target Completion</th>
                                            <th>Actual Start</th>
                                            <th>Actual Completion</th>
                                           <!--  <th>Verified Date Needed</th>
                                            <th>Estimated Price</th> -->
                                            <th>Est. Total(Materials)</th>
                                            <th>Total (Weekly Schedule)</th>
                                            <?php
                                            while (date('Y', $nextMonday) == $year) {
                                                    echo "<th>" . date('M d', $nextMonday), '-', date('M d', $nextSaturday).", ".$year . "</th>";

                                                    $nextMonday = strtotime('+1 week', $nextMonday);
                                                    $nextSaturday = strtotime('+1 week', $nextSaturday);
                                                } 
                                                ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php $x = 1; 
                                       
                                         foreach($purch_calendar AS $pc){
                                                $firstDayOfYear1 = mktime(0, 0, 0, 1, 1, $year);
                                                $nextMonday1     = strtotime('monday', $firstDayOfYear1);
                                                $nextSaturday1   = strtotime('saturday', $nextMonday1); 

                                                $firstDayOfYear2 = mktime(0, 0, 0, 1, 1, $year);
                                                $nextMonday2     = strtotime('monday', $firstDayOfYear2);
                                                $nextSaturday2   = strtotime('saturday', $nextMonday2); 
                                            ?>
                                        <tr>
                                           <!-- <td><center>
                                                    <button type="button" class="btn btn-primary btn-xs UpdateVerDate" data-toggle="modal" data-target="#updateVerDate" id="Editverdate" title='Update Verified Date Needed' data-id="<?php echo $pc['pr_calendar_id']; ?>" data-year="<?php echo $pc['ver_date_needed']; ?>">
                                                            <span class="fa fa-pencil"></span>
                                                        </button>
                                                </center></td> -->
                                            <td><?php echo $x; ?></td>
                                            <td><?php echo $pc['proj_activity']; ?></td>
                                            <td><?php echo $pc['c_remarks']; ?></td>
                                            <td>
                                                <a class="btn btn-link" style="color:blue" data-toggle="modal" id="show" data-target="#exampleModal" data-id = '<?php echo $pc['proj_act_id'] ?>' data-year="<?php echo $year; ?>"><?php echo $pc['pr_no']; ?></a></td>
                                            <td><?php echo $pc['duration']; ?></td>
                                            <td><?php echo ($pc['target_start_date']=="") ? '' : date('F j, Y', strtotime($pc['target_start_date'])); ?></td>
                                            <td><?php echo ($pc['target_completion']=="") ? '' : date('F j, Y', strtotime($pc['target_completion'])); ?></td>
                                            <td><?php echo ($pc['actual_start']=="") ? '' : date('F j, Y', strtotime($pc['actual_start'])); ?></td>
                                            <td><?php echo ($pc['actual_completion']=="") ? '' : date('F j, Y', strtotime($pc['actual_completion'])); ?></td>
                                            <!-- <td><?php echo date('F j, Y', strtotime($pc['ver_date_needed'])); ?></td>
                                            <td><?php echo number_format($pc['estimated_price'],2); ?></td> -->
                                            <td><?php echo number_format($pc['est_total_materials'],2); ?></td>
                                            <td>
                                            <?php    $total = array();
                                            // 
                                             
                                                while (date('Y', $nextMonday2) == $year) {
                                                    $start2 = date("Y-m-d", ($nextMonday2));
                                                    $end2 = date("Y-m-d",($nextSaturday2));
                                                    $total[] = $CI->get_weekly_total($start2, $end2, $pc['proj_act_id']);

                                                    $nextMonday2 = strtotime('+1 week', $nextMonday2);
                                                    $nextSaturday2 = strtotime('+1 week', $nextSaturday2);
                                                } 

                                                echo number_format(array_sum($total),2);
                                                ?>
                                            </td>
                                             <?php
                                            // 
                                               
                                                
                                                while (date('Y', $nextMonday1) == $year) {
                                                    $start = date("Y-m-d", ($nextMonday1));
                                                    $end = date("Y-m-d",($nextSaturday1));
                                                    echo "<td>".number_format($CI->get_weekly_total($start, $end, $pc['proj_act_id']),2)."</td>";

                                                    $nextMonday1 = strtotime('+1 week', $nextMonday1);
                                                    $nextSaturday1 = strtotime('+1 week', $nextSaturday1);
                                                } 
                                                ?>
                                        </tr>
                                         <?php $x++; } ?>  
                                         
                                    </tbody>
                                </table>
                            </div>                           
                        </div>

                        <!-- modal -->
                        <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                         <div id="showall"></div>
                                    </div>
                                </div>
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
        $(document).on('click', '#show', function(e){
            e.preventDefault();
            var uid = $(this).data('id');    
            var year = $(this).data('year'); 
            var loc= document.getElementById("baseurl").value;
            var redirect1=loc+'reports/getCalendar_disp';
            $.ajax({
                  url: redirect1,
                  type: 'POST',
                  data: 'id='+uid+'&year'+year,
                beforeSend:function(){
                    $("#showall").html('Please wait ..');
                },
                success:function(data){
                   $("#showall").html(data);
                },
            })
        });
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