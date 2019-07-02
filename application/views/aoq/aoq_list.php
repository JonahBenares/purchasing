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
                                    <li><a href="<?php echo base_url(); ?>index.php/masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">AOQ List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
         <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd" style="padding-bottom: 0px">
                            <div class="main-sparkline8-hd" >
                                <h1>AOQ List</h1>
                                <small>ABSTRACT OF QUOTATION</small> 
                                <div class="sparkline8-outline-icon">
                                <a href="<?php echo base_url(); ?>index.php/aoq/served_aoq" class="btn btn-custon-three btn-success" ><span class="fa fa-archive p-l-0"></span> Served AOQ</a>
                                  <!--   <a class="btn btn-custon-three btn-primary" href=">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Create AOQ
                                    </a> -->
                                </div>
                            </div>
                        </div>
                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>AOQ Date</th>
                                            <th>PR#</th>
                                            <th>Supplier</th>
                                            <th>Department</th>
                                            <th>Enduse</th>
                                            <th>Requestor</th>
                                            <th>Date Needed</th>
                                            <th>Status</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    if(!empty($header)){
                                    foreach($header AS $head){ ?>
                                        <tr>
                                            <td><?php echo date('F j, Y', strtotime($head['aoq_date'])); ?></td>
                                            <td><?php echo $head['pr']; ?></td>
                                            <td><?php echo $head['supplier']; ?></td>
                                            <td><?php echo $head['department']; ?></td>
                                            <td><?php echo $head['enduse']; ?></td>
                                            <td><?php echo $head['requestor']; ?></td>
                                            <td><?php echo date('F j, Y', strtotime($head['date_needed'])); ?></td>
                                            <?php if($head['refer_mnl']=='1') { ?>
                                            <td><span class='label label-primary'> Refer To Manila </span></td>
                                            <?php }else { ?>
                                            <td>
                                                <?php  
                                                    if($head['saved'] == '1' && $head['completed'] =='0') { 
                                                        echo "<span class='label label-warning'> For TE </span>";
                                                    } else if($head['saved'] == '1' && $head['completed'] =='1'){
                                                        echo "<span class='label label-success'>Completed</span";
                                                    }
                                                ?>
                                            </td>
                                            <?php } ?>
                                            <td>
                                                <center>
                                                <?php if($head['rows']<=3){ ?>
                                                    <a href="<?php echo base_url(); ?>aoq/aoq_prnt/<?php echo $head['aoq_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                <?php } else if($head['rows']==4){ ?>
                                                    <a href="<?php echo base_url(); ?>aoq/aoq_prnt_four/<?php echo $head['aoq_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                <?php } else if($head['rows']==5){ ?>
                                                    <a href="<?php echo base_url(); ?>aoq/aoq_prnt_five/<?php echo $head['aoq_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" >
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                <?php } ?>
                                                    <a href="<?php echo base_url(); ?>aoq/refer_mnl/<?php echo $head['aoq_id']; ?>" class="btn btn-custon-three btn-primary btn-xs"  onclick="return confirm('Are you sure?')" title="Refer To MNL"><span class="fa fa-location-arrow"></span>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>aoq/update_served/<?php echo $head['aoq_id']; ?>" class="btn btn-custon-three btn-success btn-xs"  onclick="return confirm('Are you sure?')" title="Served"><span class="fa fa-archive"></span>
                                                    </a>
                                                </center>
                                            </td>
                                        </tr>  
                                        <?php } 
                                        } ?>                                   
                                    </tbody>
                                </table>

                            </div>
                           
                        </div>
                    </div>
                </div>
                 </form>
            </div>
        </div>
    </div>
    <!-- Data table area End-->