     <script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
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
                                    <li><a href="<?php echo base_url(); ?>jorfq/jorfq_list">JO RFQ List </a> <span class="bread-slash">/</span></li>
                                    <li><span class="bread-blod">Cancelled JO RFQ List</span></li>
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
                        <div class="sparkline8-hd" style="background: #ff6262">
                            <div class="main-sparkline8-hd">
                                <h1 class="text-white">CANCELLED JO RFQ List</h1>
                                <small class="text-white">JO REQUEST FOR QUOTATION</small>
                                <div class="sparkline8-outline-icon">
                                    <h2><span class="fa fa-ban"></span></h2>
                                </div>
                            </div>
                        </div>
                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                               <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                     <thead>
                                      <tr>
                                           
                                            <th width="13%">RFQ #</th>
                                            <th width="10%">JOR #</th>
                                            <th>Vendor</th>
                                            <th width="10%">RFQ Date</th>
                                            <th width="25%">Items</th>
                                            <th width="10%">Notes</th> 
                                            <th width="1%"><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>                                            
                                            <td></td>
                                            <td></td>
                                            <td></td>                                           
                                            <td>
                                                <span style='text-align: left;'></span>
                                            </td>
                                            <td style='font-size: 12px'></td>
                                            <td><small></small></td>
                                            <td>
                                                <center>
                                                      <a href="<?php echo base_url(); ?>rfq/rfq_outgoing/" target='_blank' class="btn btn-custon-three btn-warning btn-xs" title="View RFQ Complete">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                   
                                                </center>
                                            </td>
                                        </tr>                                  
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