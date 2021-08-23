    <script src="<?php echo base_url(); ?>assets/js/jo.js"></script> 
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
                                    <li><a href="<?php echo base_url(); ?>/joi/joi_list">JOI list</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Incomplete JOI Delivery List</span>
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
            <form name="myform" action="<?php echo base_url(); ?>index.php/aoq/add_aoq" method="POST">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd" style="background: #ffbb33">
                                <div class="main-sparkline8-hd">
                                    <h1 class="text-white">Incomplete JOI Delivery</h1>
                                    <small class="text-white">JOB ORDER</small>
                                    <div class="sparkline8-outline-icon">
                                        <h2><span class="fa fa-adjust"></span></h2>
                                    </div>
                                </div>
                            </div>                       
                            <div class="sparkline8-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>JOI Date</th>
                                                <th>JOI #</th>
                                                <th>Supplier</th>
                                                <th>JOR #</th>
                                                <th>Mode of Purchase</th>
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php 
                                                if(!empty($header)){
                                                    foreach($header AS $head){ 
                                            ?>
                                            <tr>
                                                <td><?php echo date('F j, Y', strtotime($head['joi_date'])); ?></td>
                                               <!--  <td></td> -->
                                            <td>
                                                <a class="btn-link txt-primary" onclick="viewHistoryjoi('<?php echo base_url(); ?>','<?php echo $head['joi_id']; ?>','<?php echo $head['cenpri_jo_no']; ?>','<?php echo $head['joi_no']; ?>')"><?php echo $head['cenpri_jo_no'] . "/".$head['joi_no'] ."-".COMPANY. (($head['revision_no']!=0) ? ".r".$head['revision_no'] : "");?></a>
                                            </td>
                                                <td><?php echo $head['supplier']; ?></td>
                                                <td><?php echo $head['jo']."-".COMPANY; ?></td>
                                               
                                                <td><?php
                                                    if($head['joi_type']==0){
                                                        echo "Job Order Request";
                                                    } else if($head['joi_type']==1){
                                                        echo "Direct Purchase";
                                                    } else if($head['joi_type']==2){
                                                        echo "Repeat Order";
                                                    }
                                                ?></td>
                                                <td>
                                                    <center>                                                        
                                                       <?php if($head['unreceived_dr']==0){ ?> 
                                                            <?php if($head['saved']==1 && $head['jo_type']==0){ ?>
                                                                <a href="<?php echo base_url(); ?>joi/create_dr/<?php echo $head['joi_id']; ?>" onclick="confirm('Are you sure you want to create DR?');" class="btn btn-custon-three btn-warning btn-xs " title='Create DR'>
                                                                    <span class="fa fa-file"></span>
                                                                </a>
                                                            <?php }else{ ?>
                                                                <a href="<?php echo base_url(); ?>jod/create_dr/<?php echo $head['joi_id']; ?>" onclick="confirm('Are you sure you want to create DR?');" class="btn btn-custon-three btn-warning btn-xs " title='Create DR'>
                                                                    <span class="fa fa-file"></span>
                                                                </a>
                                                            <?php } ?>
                                                     <?php } else { ?>
                                                        <?php if($head['saved']==1 && $head['jo_type']==0){ ?>
                                                            <a href="" class="btn btn-custon-three btn-success btn-xs deliverpo" title='Deliver JO' onclick="deliver_jo('<?php echo base_url(); ?>','<?php echo $head['joi_id']?>','<?php echo $head['joi_dr_id']?>')">
                                                                <span class="fa fa-truck"></span>
                                                            </a>
                                                        <?php }else{ ?>
                                                            <a href="" class="btn btn-custon-three btn-success btn-xs deliverpo" title='Deliver JO' onclick="deliver_jod('<?php echo base_url(); ?>','<?php echo $head['joi_id']?>','<?php echo $head['joi_dr_id']?>')">
                                                                <span class="fa fa-truck"></span>
                                                            </a>
                                                        <?php } ?>
                                                    <?php } ?>
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
            </form>
        </div>
    </div>