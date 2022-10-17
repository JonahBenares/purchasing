</script>
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
                                    <li><span class="bread-blod">Completed PR List</span>
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
             <form name="myform" action="<?php echo base_url(); ?>aoq/" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd p-b-0" >
                                <div class="main-sparkline8-hd">
                                    <h1>Completed PR List</h1>
                                    <small>PURCHASE REQUEST</small> 
                                </div>
                            </div>                       
                            <div class="sparkline8-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>PR NO</th>
                                                <th>Date Prepared</th>
                                                <th>Date Upload</th>
                                                <th>Department</th>
                                                <th>Urgency Number</th>
                                                <th>Requestor</th>
                                                <th>Completed By</th>
                                               
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php 
                                            foreach($pr_completed AS $pc){ ?>                                      
                                            <tr>
                                                <td><?php echo $pc['pr_no']."-".COMPANY;?></td>
                                                <td><?php echo $pc['date_prepared'];?></td>
                                                <td><?php echo date("Y-m-d",strtotime($pc['date_imported']));?></td>
                                                <td><?php echo $pc['department'];?></td>
                                                <td ><center><?php echo $pc['urgency'];?></center></td>
                                                <td><?php echo $pc['requestor'];?></td>
                                                <td><?php echo $pc['completed_by'] . "/" .$pc['date_completed'] ; ?></td>
                                                <td>
                                                    <center>
                                                        <a href="<?php echo base_url(); ?>pr/purchase_request/<?php echo $pc['pr_id']?>" class="btn btn-custon-three btn-warning btn-xs">
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