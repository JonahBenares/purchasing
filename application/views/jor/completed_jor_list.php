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
                                    <li><span class="bread-blod">Completed JOR List</span>
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
             <form name="myform" action="<?php echo base_url(); ?>" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd p-b-0" >
                                <div class="main-sparkline8-hd">
                                    <h1>Completed JOR List</h1>
                                    <small>JOB ORDER REQUEST</small> 
                                </div>
                            </div>                       
                            <div class="sparkline8-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>JO NO</th>
                                                <th>Date Prepared</th>
                                                <th>Date Upload</th>
                                                <th>Department</th>
                                                <th>Urgency Number</th>
                                                <th>JO Request</th>
                                                <th>Completed By</th>
                                               
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php
                                                foreach($jor_completed AS $jc){ 
                                            ?>                                
                                            <tr>
                                                <td><?php echo $jc['jo_no']."-".COMPANY ." / ".$jc['user_jo_no'];?></td>
                                                <td><?php echo date("F d, Y",strtotime($jc['date_prepared']));?></td>
                                                <td><?php echo date("F d, Y",strtotime($jc['date_imported']));?></td>
                                                <td><?php echo $jc['department'];?></td>
                                                <td><center><?php echo $jc['urgency'];?></center></td>
                                                <td><?php echo $jc['jo_request'];?></td>
                                                <td><?php echo $jc['completed_by'] . "/" .$jc['date_completed'] ; ?></td>
                                                <td>
                                                    <center>
                                                        <a href="<?php echo base_url(); ?>jor/jor_request/<?php echo $jc['jor_id']?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                        </a>
                                                    </center>
                                                </td>
                                            </tr>   
                                            <?php } ?>             
                                        </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <!-- Data table area End-->