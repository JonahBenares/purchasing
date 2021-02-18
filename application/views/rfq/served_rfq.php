
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
                                    <li><a href="<?php echo base_url(); ?>masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>rfq/rfq_list">RFQ List</a><span class="bread-slash">/</span></li>
                                    <li><span class="bread-blod">RFQ List</span></li>
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
            <form name="myform" method="post">
                <div class="row">
                    <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd" style=" background: #44c372">
                            <div class="main-sparkline8-hd" >
                                <h1 class="text-white">SERVED RFQ List</h1>
                                <small class="text-white">REQUEST FOR QUOTATION</small> 
                                <div class="sparkline8-outline-icon">
                                    <h2><span class="fa fa-archive"></span></h2>
                                </div>
                            </div>
                        </div>
                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                      <tr>
                                           
                                            <th width="13%">RFQ #</th>
                                            <th width="10%">PR #</th>
                                            <th>Vendor</th>
                                            <th width="10%">RFQ Date</th>
                                            <th width="25%">Items</th>
                                            <th width="10%">Notes</th> 
                                            <th width="15%"><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  <?php
                                        if(!empty($head)){
                                         foreach($head AS $h){ ?>
                                        
                                        <tr>
                                            
                                            <td><?php echo $h['rfq_no']."-".COMPANY; ?></td>
                                            <td><?php echo $h['pr_no']."-".COMPANY; ?></td>
                                            <td><?php echo $h['vendor']; ?></td>
                                           
                                            <td>
                                                <span style='text-align: left;'> <?php echo date('m.j.y', strtotime($h['rfq_date'])); ?></span>
                                            </td>
                                            <td style='font-size: 12px'>
                                                <?php foreach($items AS $it){ 
                                                    if($h['rfq_id']==$it['rfq_id']){
                                                        echo "- ". $it['item'] . "<br>";
                                                    } 
                                                 } ?>
                                            </td>
                                            <td><small><?php echo $h['notes']; ?></small></td>
                                            <td>
                                                <center>
                                                      <a href="<?php echo base_url(); ?>rfq/rfq_outgoing/<?php echo $h['rfq_id']; ?>" target='_blank' class="btn btn-custon-three btn-warning btn-xs" title="View RFQ Complete">
                                                        <span class="fa fa-eye"></span>
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
                </div>
            </form>
        </div>
    </div>
    <!-- Data table area End-->