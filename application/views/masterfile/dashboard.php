
    <style type="text/css">
        .fixed-table-body {
            overflow-x: hidden!important; 
        }
        a {
            color:#fff;
        }
        a:hover {
            color:#0000FF;
        }
    </style>
    <div class="breadcome-area mg-b-30 small-dn">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="breadcome-heading">
                                    <form role="search" class="">
                                        <input type="text" placeholder="Search..." class="form-control">
                                        <a href=""><i class="fa fa-search"></i></a>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <ul class="breadcome-menu">
                                    <li><a href="<?php echo base_url(); ?>">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Dashboard</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="welcome-adminpro-area">
        <div class="container-fluid">
            <!-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="dashone-adminprowrap shadow-reset mg-b-30 pulse" style="height: 320px;max-height: 320px " >
                        <div class="dash-adminpro-project-title">
                            <h2 class="m-b-0" >
                                <b>
                                    <span>Calendar</span>
                                    <div class="btn-group pull-right ">
                                        <button type="button" class="btn btn-success btn-md btn-custon-three" data-toggle="modal" data-target="#filter_pending" title="Filter">
                                            <span class="fa fa-filter"></span>
                                        </button>
                                    </div>
                                    
                                </b>
                                <p class="m-b-0">Pending PR</p>
                                <div class="modal fade" id="filter_pending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Filter
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </h5>                                                
                                            </div>
                                            <form method='POST' action="<?php echo base_url(); ?>masterfile/filter_pending">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input placeholder="Date From" name="filter_date_from" class="form-control" type="text" onfocus="(this.type='date')" id="filter_date_from">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input placeholder="Date To" class="form-control" name="filter_date_to" type="text" onfocus="(this.type='date')" id="filter_date_to">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                        
                                                    <input type="submit" class="btn btn-primary btn-block" value='Search'>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </h2>                            
                        </div>
                        <?php if(!empty($filt)){ ?>     
                        <span class='btn btn-success disabled'>Filter Applied</span><?php echo $filt ?>, <a href="<?php echo base_url(); ?>index.php/masterfile/dashboard/<?php echo $filter_date_from; ?>/<?php echo $filter_date_to; ?>" class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>                    
                        <?php } ?>  
                        <div style="overflow-y: scroll;height: 220px;max-height: 220px  ">
                            <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th>Purpose</th>
                                        <th>End Use</th>
                                        <th>Site Pr No</th>
                                        <th>Requestor</th>
                                        <th>QTY</th>
                                        <th>UOM</th>
                                        <th>Description</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($dash_calendar AS $ca){ 
                                  
                                ?>
                                    <tr>
                                        <td><?php echo $ca['purpose']; ?></td>
                                        <td><?php echo $ca['enduse']; ?></td>
                                        <td><?php echo $ca['site_pr']; ?></td>
                                        <td><?php echo $ca['requestor']; ?></td>
                                        <td><?php echo $ca['qty']; ?></td>
                                        <td><?php echo $ca['uom']; ?></td>
                                        <td><?php echo $ca['description']; ?></td>
                                       
                                    </tr> 
                                <?php  } ?>      
                                </tbody>
                            </table>
                                  
                        </div>    

                    </div>
                </div>
            </div> -->
            <div class="row">
                <!-- pr and for te -->
                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12 ">
                    <div class="dashone-adminprowrap shadow-reset mg-b-30 pulse" style="height: 285px;max-height: 285px " >
                        <div class="dash-adminpro-project-title">
                            <h2 class="m-b-0" >
                                <b>
                                    <span>Things-To-Do Today</span>
                                    <button type="button" class="btn btn-primary btn-xs pull-right btn-custon-three" data-toggle="modal" data-target="#todo">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </b>
                                <p class="m-b-0">Process immediately.</p>
                                <div class="modal fade" id="todo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Thing/s To-Do

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </h5>                                                
                                            </div>
                                            <form  method='POST' action='<?php echo base_url(); ?>index.php/masterfile/insert_todo'>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        Thing/s To-Do:
                                                        <textarea class="form-control" name = "todo" rows="5" placeholder="...."></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        Due Date:
                                                        <input type="date" class="form-control" name="due_date">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                                            
                                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </h2>                            
                        </div>
                        <div style="overflow-y: scroll;height: 200px;max-height: 200px  ">
                            <!-- loop here -->
                            <?php 
                        

                            if(!empty($todo)){
                               
                                foreach($todo AS $t){ 
                                    if($t['done']==0){ 
                            
                            ?>
                            <div class="row m-t-5" style="width: 100%">
                                <div class="col-lg-12">
                                    <div class="project-dashone-phara pad-5 reminder-style">
                                        <div class="row">
                                            <?php if($t['type']=='manual'){ ?>
                                            <div class="col-lg-1">
                                                <a href="<?php echo base_url(); ?>masterfile/todo_done/<?php echo $t['todo_id']; ?>" class="btn btn-xs btn-warning btn-custon-two"> <span class="fa fa-check"></span></a> 
                                            </div>
                                            <?php } ?>
                                            <div class="col-lg-11">
                                                <?php if($t['type']=='auto'){ 
                                                    if($t['source'] == 'po'){
                                                        $path = base_url().'po/purchase_order_rev/'.$t['todo_id'];
                                                    } else if($t['source'] == 'rfq'){
                                                        $path = base_url().'rfq/rfq_outgoing/'.$t['todo_id'];
                                                    } else if($t['source'] == 'pr'){
                                                        $path = base_url().'pr/purchase_request/'.$t['todo_id'];
                                                    } ?>
                                               
                                                 <?php } ?>                                    
                                                 <a href='<?php echo $path; ?>' target='_blank' ><h5 class="nomarg" style='margin-bottom:5px'><?php echo $t['notes']; ?></h5></a>
                                                <p class=""> 
                                                    <span style="background-color: #b94526; padding:0px 10px; "><?php echo date("F d, Y",strtotime($t['due_date']));?>  
                                                    </span> | <small><?php echo $t['remind'];?>
                                                
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <?php } } } ?> 
                            <!-- loop here --> 

                                  
                        </div>    

                    </div>
                </div>   
                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12 ">
                    <div class="dashone-adminprowrap shadow-reset mg-b-30 pulse" style="height: 285px;max-height: 285px " >
                        <div class="dash-adminpro-project-title">
                            <h2 class="m-b-0" >
                                <b>
                                    <span>Reminders for this Week</span>
                                    <button type="button" class="btn btn-primary btn-xs pull-right btn-custon-three" data-toggle="modal" data-target="#remindermowdal">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </b>
                                <p class="m-b-0">Process immediately.</p>
                                <div class="modal fade" id="remindermowdal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Reminder
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </h5>                                                
                                            </div>
                                            <form  method='POST' action='<?php echo base_url(); ?>index.php/masterfile/insert_reminder'>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        Reminder:
                                                        <textarea class="form-control" name = "reminder" rows="5" placeholder="...."></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        Due Date:
                                                        <input type="date" class="form-control" name="due_date">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                                            
                                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </h2>                            
                        </div>
                        <div style="overflow-y: scroll;height: 200px;max-height: 200px  ">
                            <!-- loop here -->
                            <?php 
                        
                         

                            if(!empty($reminder)){
                                $columns = array_column($reminder, 'due_date');
                                $a = array_multisort($columns, SORT_ASC, $reminder);

                                foreach($reminder AS $r){ 
                                    if($r['done']==0){ 
                            
                            ?>
                            <div class="row m-t-5" style="width: 100%">
                                <div class="col-lg-12">
                                    <div class="project-dashone-phara pad-5 reminder-style2">
                                        <div class="row">
                                            <?php if($r['type']=='manual'){ ?>
                                            <div class="col-lg-1">
                                                <a href="<?php echo base_url(); ?>masterfile/reminder_done/<?php echo $r['reminder_id']; ?>" class="btn btn-xs btn-info btn-custon-three"> <span class="fa fa-check"></span></a> 
                                            </div>
                                            <?php } ?>
                                            <div class="col-lg-11">
                                                <?php if($r['type']=='auto'){ 
                                                    if($r['source'] == 'po'){
                                                        $path = base_url().'po/purchase_order_rev/'.$r['reminder_id'];
                                                    } else if($r['source'] == 'rfq'){
                                                        $path = base_url().'rfq/rfq_outgoing/'.$r['reminder_id'];
                                                    } else if($r['source'] == 'pr'){
                                                        $path = base_url().'pr/purchase_request/'.$r['reminder_id'];
                                                    } ?>
                                                <a href='<?php echo $path; ?>' target='_blank'><h5 class="nomarg"><?php echo $r['notes']; ?></h5></a>
                                                 <?php } else { ?>
                                                    <h5 class="nomarg"><?php echo $r['notes']; ?></h5>
                                                 <?php } ?>        
                                                <p class=""><span style="background-color: #4848e6; padding:0px 10px"><?php echo date("F d, Y",strtotime($r['due_date']));?>  </span> | <small><?php echo $r['remind'];?></small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <?php } } } ?>    
                            <!-- loop here --> 

                                  
                        </div>    

                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 
     <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>Pending PRs</h1>
                                <div class="sparkline8-outline-icon">
                                    <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                    <span><i class="fa fa-wrench"></i></span>
                                    <span class="sparkline8-collapse-close"><i class="fa fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"  data-show-pagination-switch="true"  data-cookie="true"  data-show-export="true" data-toolbar="#toolbar" data-resizable="true">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" data-field="prdate" >PR Date</th>
                                            <th rowspan="2" data-field="prno" >PR No</th>
                                            <th rowspan="2" data-field="item" >Item Description</th>
                                            <th data-field="rfq" >RFQ</th>
                                            <th colspan="2" data-field="aoq" >AOQ</th>
                                            <th rowspan="2" data-field="po" >PO Issued</th>
                                            <th rowspan="2" data-field="del" >Delivered</th>
                                            <th rowspan="2" data-field="status" >Remarks</th>
                                        </tr>
                                        <tr>
                                            <th width="20%">Outgoing</th>
                                       
                                            <th width="20%">For TE</th>
                                            <th width="20%">TE Done</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(!empty($pendingpr)){

                                         $columns = array_column($pendingpr, 'pr_date');
                                         array_multisort($columns, SORT_ASC, $pendingpr);

                                         foreach($pendingpr AS $pr){
                                        

                                        if($pr['po_delivered']==0 || $pr['balance']!=0){ 
                                                if($pr['balance']!=0){
                                                    $status = 'Unserved: '.$pr['balance'];
                                                } else {
                                                    $status='';
                                                } ?>
                                        <tr>
                                            <td><?php echo date('m.d.Y', strtotime($pr['pr_date'])); ?></td>
                                            <td><?php echo $pr['pr_no']."-".COMPANY; ?></td>
                                            <td><span style="color:blue"><?php echo $pr['item']; ?></span></td>
                                            <td class="datatable-ct"><?php echo (($pr['rfq_outgoing']==0) ? '' : '<i class="fa fa-check"></i>'); ?></td>
                                       
                                            <td class="datatable-ct"><?php echo (($pr['for_te']==0) ? '' : '<i class="fa fa-check"></i>'); ?></td>
                                            <td class="datatable-ct"><?php echo (($pr['te_done']==0) ? '' : '<i class="fa fa-check"></i>'); ?></td>
                                            <td class="datatable-ct"><?php echo (($pr['po_issued']==0) ? '' : '<i class="fa fa-check"></i>'); ?></td>
                                            <td class="datatable-ct"><?php echo (($pr['po_delivered']==0) ? '' : '<i class="fa fa-check"></i>'); ?></td>
                                            <td class="datatable-ct"><?php echo $status; ?></td>
                                        </tr>
                                    <?php } 
                                    }
                                    }?>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="cancelPR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                     <form method='POST' action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </h5>     
                           
                            <div class="modal-body">
                                <div class="form-group">
                                    Reason for Cancelling:

                                    <textarea class="form-control" name='cancel_reason' rows="5"></textarea> 
                                   
                                </div>                                
                            </div>
                            <div class="modal-footer">
                                 <input type='hidden' name='pr_details_id' id='pr_details_id'>
                                <input type="submit" class="btn btn-danger btn-block" value='Cancel'>
                            </div>
                                    
                        </div>
                        
                    </div>
                      </form>         
                </div>
            </div>

    <!-- welcome Project, sale area start-->
    <!-- <div class="welcome-adminpro-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                        <div class="welcome-adminpro-title">
                            <h1>Welcome Adminpro</h1>
                            <p>You have 100 messages and 10 notifications.</p>
                        </div>
                        <div class="adminpro-message-list">
                            <ul class="message-list-menu">
                                <li><span class="message-serial message-cl-one">1</span> <span class="message-info">Please contact me</span> <span class="message-time">09:00 pm</span>
                                </li>
                                <li><span class="message-serial message-cl-two">2</span> <span class="message-info">Sign a contract</span> <span class="message-time">10:00 pm</span>
                                </li>
                                <li><span class="message-serial message-cl-three">3</span> <span class="message-info">Please delevary project</span> <span class="message-time">05:00 pm</span>
                                </li>
                                <li><span class="message-serial message-cl-four">4</span> <span class="message-info">Open new shop</span> <span class="message-time">04:00 pm</span>
                                </li>
                                <li><span class="message-serial message-cl-five">5</span> <span class="message-info">Improtant Notification here</span> <span class="message-time">09:00 pm</span>
                                </li>
                                <li><span class="message-serial message-cl-six">5</span> <span class="message-info">Please Report here</span> <span class="message-time">09:00 pm</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                    <div class="dashboard-line-chart shadow-reset mg-b-30">
                        <div class="flot-chart dashboard-chart">
                            <canvas id="myChartsrs1" width="400" height="170"></canvas>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="linechart-dash-rate">
                                    <h2>$5,000</h2>
                                    <p>Sales report</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="linechart-dash-rate">
                                    <h2>$7,000</h2>
                                    <p>Annual Sales</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="linechart-dash-rate">
                                    <h2>$3,000</h2>
                                    <p>revenue Sales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 desplay-n-tablet">
                    <div class="dashone-adminprowrap shadow-reset mg-b-30">
                        <div class="dash-adminpro-project-title">
                            <h2>Project progress</h2>
                            <p>You have two project right now.</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dashone-doughnut">
                                    <div id="sparklinedask1"></div>
                                    <h3>Design</h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashone-doughnut">
                                    <div id="sparklinedask2"></div>
                                    <h3>Development</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="project-dashone-phara">
                                    <p>Lorem Ipsum is simply dummy one text of the printing and the typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- welcome Project, sale area start-->
    <!-- stockprice, feed area start-->
    <!-- <div class="stockprice-feed-project-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="sparkline7-list stock-price-section shadow-reset nt-mg-b-30">
                        <div class="sparkline7-hd">
                            <div class="main-spark7-hd">
                                <h1>Stock Price Report</h1>
                                <div class="sparkline7-outline-icon">
                                    <span class="sparkline7-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                    <span><i class="fa fa-wrench"></i></span>
                                    <span class="sparkline7-collapse-close"><i class="fa fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline7-graph">
                            <div class="dashone-bar-small dashone-bar">
                                <span class="bar_dashboard">5,3,9,6,5,9,7,3,5,2,4,7,3,2,7,9,6,4,5,7,3,2,1,0,9,5,6,8,3,2,1</span>
                                <p>$505400055.00</p>
                            </div>
                            <div class="dashone-bar-heading">
                                <h2>ADN Stock Price Data!</h2>
                                <a href="#">Check the stock price!</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sparkline11-list shadow-reset nt-mg-b-30">
                                <div class="sparkline11-hd">
                                    <div class="main-sparkline11-hd">
                                        <h1>Latest Comments</h1>
                                        <div class="sparkline11-outline-icon">
                                            <span class="sparkline11-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                            <span><i class="fa fa-wrench"></i></span>
                                            <span class="sparkline11-collapse-close"><i class="fa fa-times"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sparkline11-graph dashone-comment comment-scrollbar">
                                    <div class="comment-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@Toman Alva</a> Start each day with a prayer and end your day with a prayer and thank God for a another day.</p>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 1 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo1">...</button>
                                            <ul id="adminpro-demo1" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@William Jon</a> Simple & easy online tools to increase the website visitors, improve SEO, marketing & sales, automatic blog!</p>
                                            <div class="sparkline-dashone">
                                                <span id="sparkline8"></span>
                                            </div>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 5 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo2">...</button>
                                            <ul id="adminpro-demo2" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@mexicano</a> Soy cursi, twitteo frases pedorras y vendo antojitos mexicanos. Santa Rosa, La Pampa, Argentina</p>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 15 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo3">...</button>
                                            <ul id="adminpro-demo3" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@Bhadkamkar</a> News love and follow Jesus and my family and friends l hope God bless you always.</p>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 20 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo4">...</button>
                                            <ul id="adminpro-demo4" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@SHAKHAWAT</a> Make the Best Use of What You Have.You Never Know When & Where You Find Yourself..</p>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 25 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo5">...</button>
                                            <ul id="adminpro-demo5" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-phara comment-bd-phara">
                                        <div class="comment-adminpr">
                                            <p class="comment-content"><a href="#">@Sarah</a> A 'Power Chick' committed to using my superpowers for good. Author, speaker, radio host.</p>
                                        </div>
                                        <div class="admin-comment-month">
                                            <p class="comment-clock"><i class="fa fa-clock-o"></i> 27 minuts ago</p>
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo6">...</button>
                                            <ul id="adminpro-demo6" class="comment-action-st collapse">
                                                <li><a href="#">Add</a>
                                                </li>
                                                <li><a href="#">Report</a>
                                                </li>
                                                <li><a href="#">Hide Comment</a>
                                                </li>
                                                <li><a href="#">Turn on Comment</a>
                                                </li>
                                                <li><a href="#">Turn off Comment</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="about-sparkline shadow-reset desplay-n-tablet-pro">
                        <div class="sparkline-hd">
                            <div class="main-spark-hd">
                                <h1>Project Timeline </h1>
                                <div class="outline-icon">
                                    <span class="collapse-link"><i class="fa fa-chevron-up"></i></span>
                                    <span><i class="fa fa-wrench"></i></span>
                                    <span class="collapse-close"><i class="fa fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline-content timeline-scrollbar">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="timeline-hd-main">
                                        <div class="timeline-heading-admin">
                                            <h1>You have meeting today!</h1>
                                            <p><i class="fa fa-map-marker"></i> Meeting is on 6:00am. Check your schedule to see detail.</p>
                                        </div>
                                        <div class="mapcontainer">
                                            <div class="map">
                                                <span>Alternative content for the map</span>
                                            </div>
                                            <div class="plotLegend"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin res-mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">5 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn timeline-adminpro-bd-ct">
                                    <div class="timeline-content">
                                        <h3>Managing Director at Leather Export</h3>
                                        <p>Hope you all are doing good. We are one of manufacturer and supplier of Cow Crust and finished leather from USA. Please feel free to contact us if you have any queries.</p>
                                        <div class="admin-timeline-graph">
                                            <span data-diameter="40" class="updating-chart">2,5,9,6,5,9,7,3,5,2,5,3,9,6,5,8,7,8,5,2</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin res-mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">10 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn">
                                    <div class="timeline-content">
                                        <h3>Founder/CEO  at Intcs, Inc.</h3>
                                        <p>Yes, millennials are changing the way. This week on Radiate we're highlighting benefits and challenges of working across generator and cultures. Watch the newest Radiate Expert to join us.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin res-mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">13 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn">
                                    <div class="timeline-content">
                                        <h3>Meeting Fixed Blue</h3>
                                        <p>Toys are watching you so is your car and your vacuum cleaner Here are 39 ways your privacy is being compromised, and 8 guides to protecting your digital self.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin res-mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">20 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn">
                                    <div class="timeline-content">
                                        <h3>USA Institute of Management</h3>
                                        <p>The biggest Challenge which I have been use to Win is to in-house raw materials on time based on customer demand forecast and production capacity concentrating Seasonal impacts and considering foreign suppliers's cultural holidays, and Port congestion.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin res-mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">30 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn">
                                    <div class="timeline-content">
                                        <h3>Meeting Fixed</h3>
                                        <p>Conference on the sales results for the previous year. Monica please examine sales trends in marketing and products. Below please find the current status of the sale.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row timelinewrap-admin">
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12 timeline-date-time-bd">
                                    <div class="icon-date-timeline">
                                        <i class="fa fa-briefcase"></i>
                                        <p>6:00 am</p>
                                        <p class="timeline-hr-cl">34 Min ago</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 timeline-adminpro-cn">
                                    <div class="timeline-content">
                                        <h3>Meeting Fixed</h3>
                                        <p>My objective is to pursue a career leading company where I would gain experience while adding value to the business of the organization.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30 res-mg-t-30">
                        <div class="income-title">
                            <div class="main-income-head">
                                <h2>Pending RFQ</h2>
                                <div class="main-income-phara">
                                    <p>Monthly</p>
                                </div>
                            </div>
                        </div>
                        <div class="income-dashone-pro">
                            <div class="income-rate-total">
                                <div class="price-adminpro-rate">
                                    <h3><span></span><span class="counter"><?php echo $count_rfq;?></span></h3>
                                </div>
                                <div class="price-graph">
                                    <span id="sparkline1"></span>
                                </div>
                            </div>
                            <div class="income-range">
                                <p>Total</p>
                                <span class="income-percentange">98% <i class="fa fa-bolt"></i></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="income-dashone-total orders-monthly shadow-reset nt-mg-b-30">
                        <div class="income-title">
                            <div class="main-income-head">
                                <h2>Pending AOQ</h2>
                                <div class="main-income-phara order-cl">
                                    <p>Annual</p>
                                </div>
                            </div>
                        </div>
                        <div class="income-dashone-pro">
                            <div class="income-rate-total">
                                <div class="price-adminpro-rate">
                                    <h3><span class="counter"><?php echo $count_aoq;?></span></h3>
                                </div>
                                <div class="price-graph">
                                    <span id="sparkline6"></span>
                                </div>
                            </div>
                            <div class="income-range order-cl">
                                <p>Total</p>
                                <span class="income-percentange">66% <i class="fa fa-level-up"></i></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="income-dashone-total visitor-monthly shadow-reset nt-mg-b-30">
                        <div class="income-title">
                            <div class="main-income-head">
                                <h2>Pending PO</h2>
                                <div class="main-income-phara visitor-cl">
                                    <p>Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="income-dashone-pro">
                            <div class="income-rate-total">
                                <div class="price-adminpro-rate">
                                    <h3><span class="counter"><?php echo $count_aoq;?></span></h3>
                                </div>
                                <div class="price-graph">
                                    <span id="sparkline2"></span>
                                </div>
                            </div>
                            <div class="income-range visitor-cl">
                                <p>Total</p>
                                <span class="income-percentange">55% <i class="fa fa-level-up"></i></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="income-dashone-total user-monthly shadow-reset nt-mg-b-30">
                        <div class="income-title">
                            <div class="main-income-head">
                                <h2>User activity</h2>
                                <div class="main-income-phara low-value-cl">
                                    <p>Low Value</p>
                                </div>
                            </div>
                        </div>
                        <div class="income-dashone-pro">
                            <div class="income-rate-total">
                                <div class="price-adminpro-rate">
                                    <h3><span class="counter">88,200</span></h3>
                                </div>
                                <div class="price-graph">
                                    <span id="sparkline5"></span>
                                </div>
                            </div>
                            <div class="income-range low-value-cl">
                                <p>In first month</p>
                                <span class="income-percentange">33% <i class="fa fa-level-down"></i></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- stockprice, feed area end-->
    <!-- Data table area Start-->
    <!-- <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>Projects Data Table</h1>
                                <div class="sparkline8-outline-icon">
                                    <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                    <span><i class="fa fa-wrench"></i></span>
                                    <span class="sparkline8-collapse-close"><i class="fa fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="state" data-checkbox="true"></th>
                                            <th data-field="id">ID</th>
                                            <th data-field="name" data-editable="true">Project</th>
                                            <th data-field="email" data-editable="true">Email</th>
                                            <th data-field="phone" data-editable="true">Phone</th>
                                            <th data-field="company" data-editable="true">Company</th>
                                            <th data-field="complete">Completed</th>
                                            <th data-field="task" data-editable="true">Task</th>
                                            <th data-field="date" data-editable="true">Date</th>
                                            <th data-field="price" data-editable="true">Price</th>
                                            <th data-field="action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>1</td>
                                            <td>Web Development</td>
                                            <td>admin@uttara.com</td>
                                            <td>+8801962067309</td>
                                            <td>Aber Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">1/6</span>
                                            </td>
                                            <td>10%</td>
                                            <td>Jul 14, 2018</td>
                                            <td>$5455</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>2</td>
                                            <td>Graphic Design</td>
                                            <td>fox@itpark.com</td>
                                            <td>+8801762067304</td>
                                            <td>Abitibi Inc.</td>
                                            <td class="datatable-ct"><span class="pie">230/360</span>
                                            </td>
                                            <td>70%</td>
                                            <td>fab 2, 2018</td>
                                            <td>$8756</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>3</td>
                                            <td>Software Development</td>
                                            <td>gumre@hash.com</td>
                                            <td>+8801862067308</td>
                                            <td>Acambis plc</td>
                                            <td class="datatable-ct"><span class="pie">0.42/1.461</span>
                                            </td>
                                            <td>5%</td>
                                            <td>Seb 5, 2018</td>
                                            <td>$9875</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>4</td>
                                            <td>Woocommerce</td>
                                            <td>kyum@frok.com</td>
                                            <td>+8801962066547</td>
                                            <td>ACLN Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Oct 10, 2018</td>
                                            <td>$3254</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>5</td>
                                            <td>Joomla</td>
                                            <td>jams@game.com</td>
                                            <td>+8801962098745</td>
                                            <td>ACS-Tech Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">200,133</span>
                                            </td>
                                            <td>80%</td>
                                            <td>Nov 20, 2018</td>
                                            <td>$58745</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>6</td>
                                            <td>Wordpress</td>
                                            <td>flat@yem.com</td>
                                            <td>+8801962254781</td>
                                            <td>ActFits.com Inc.</td>
                                            <td class="datatable-ct"><span class="pie">0.42,1.051</span>
                                            </td>
                                            <td>30%</td>
                                            <td>Aug 25, 2018</td>
                                            <td>$789879</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>7</td>
                                            <td>Ecommerce</td>
                                            <td>hasan@wpm.com</td>
                                            <td>+8801962254863</td>
                                            <td>ActivCard S.A.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>July 17, 2018</td>
                                            <td>$21424</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>8</td>
                                            <td>Android Apps</td>
                                            <td>ATM@devep.com</td>
                                            <td>+8801962875469</td>
                                            <td>Adecco S.A.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>June 11, 2018</td>
                                            <td>$78978</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>9</td>
                                            <td>Prestashop</td>
                                            <td>presta@Prest.com</td>
                                            <td>+8801962067524</td>
                                            <td>AEGON N.V.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>May 9, 2018</td>
                                            <td>$45645</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>10</td>
                                            <td>Game Development</td>
                                            <td>Dev@game.com</td>
                                            <td>+8801962067457</td>
                                            <td>Aerco Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>April 5, 2018</td>
                                            <td>$4564545</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>11</td>
                                            <td>Angular Js</td>
                                            <td>gular@angular.com</td>
                                            <td>+8801962067124</td>
                                            <td>Agrium Inc.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Dec 1, 2018</td>
                                            <td>$645455</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>12</td>
                                            <td>Opencart</td>
                                            <td>open@cart.com</td>
                                            <td>+8801962067587</td>
                                            <td>Air Canada</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Jan 6, 2018</td>
                                            <td>$78978</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>13</td>
                                            <td>Education</td>
                                            <td>john@example.com</td>
                                            <td>+8801962067471</td>
                                            <td>Alcan Inc.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Feb 6, 2016</td>
                                            <td>$456456</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>14</td>
                                            <td>Construction</td>
                                            <td>mary@example.com</td>
                                            <td>+8801962012457</td>
                                            <td>Alcatel</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Jan 6, 2016</td>
                                            <td>$87978</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>15</td>
                                            <td>Real Estate</td>
                                            <td>july@example.com</td>
                                            <td>+8801962067309</td>
                                            <td>Alstom</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Dec 1, 2016</td>
                                            <td>$454554</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>16</td>
                                            <td>Personal Regume</td>
                                            <td>john@example.com</td>
                                            <td>+8801962067306</td>
                                            <td>Altarex Corp.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>May 9, 2016</td>
                                            <td>$564555</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>17</td>
                                            <td>Admin Template</td>
                                            <td>mary@example.com</td>
                                            <td>+8801962067305</td>
                                            <td>Alvarion Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>June 11, 2016</td>
                                            <td>$454565</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>18</td>
                                            <td>FrontEnd</td>
                                            <td>july@example.com</td>
                                            <td>+8801962067304</td>
                                            <td>Amcor Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>May 9, 2015</td>
                                            <td>$456546</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>19</td>
                                            <td>Backend</td>
                                            <td>john@range.com</td>
                                            <td>+8801962067303</td>
                                            <td>Amdocs Ltd.</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Feb 9, 2014</td>
                                            <td>$564554</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>20</td>
                                            <td>Java Advance</td>
                                            <td>lamon@ghs.com</td>
                                            <td>+8801962067302</td>
                                            <td>Amersham plc</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>July 6, 2014</td>
                                            <td>$789889</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>21</td>
                                            <td>Jquery Advance</td>
                                            <td>hasad@uth.com</td>
                                            <td>+8801962067301</td>
                                            <td>Amvescap plc</td>
                                            <td class="datatable-ct"><span class="pie">2,7</span>
                                            </td>
                                            <td>15%</td>
                                            <td>Jun 6, 2013</td>
                                            <td>$4565656</td>
                                            <td class="datatable-ct"><i class="fa fa-check"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Data table area End-->