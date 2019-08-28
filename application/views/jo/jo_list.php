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
                                    <li><a href="<?php echo base_url(); ?>masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">JO List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_jo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add JO
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>               
                </div>
                <form method='POST' action="<?php echo base_url(); ?>jo/create_jo">
                    <div class="modal-body">
                        <div class="">
                            <div class="form-group btn-block m-b-5">
                                TO:
                                <select name='vendor' id='vendor' onchange="chooseVendor()" class='form-control'>
                                <option value=''>-Select Vendor-</option>
                                 <?php foreach($vendor AS $sup){ ?>
                                    <option value="<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                                <?php } ?>        
                            </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><p  id='address'>Address</p></div>
                                 <div class="col-md-6"><p id='phone'>Contact Number</p></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group btn-block">
                                        Date Prepared:
                                        <input type="date" name="date_prepared" id="date_prepared" class="form-control" onchange="getJO()" >
                                    </div>
                                    <div class="form-group btn-block">
                                       Start of Work:
                                        <input type="date" name="work_start" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group btn-block">
                                       Completion of Work:
                                        <input type="date" name="work_completion" class="form-control">
                                    </div>
                                    <div class="form-group btn-block">
                                       CENPRI JO No.:
                                        <input type="Text" name="cenjo_no" class="form-control">
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group btn-block">
                                JO No.:
                                <input type="Text" name="jo_no" id="jo_no"  class="form-control" readonly="readonly">
                            </div>
                            <div class="form-group btn-block">
                                Project Title/Description:
                                <textarea name="project_title" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary btn-block" value="Proceed">                        
                    </div>
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                  
                </form>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">        
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1>JO List</h1>
                                <small>JOB ORDER</small> 
                                <div class="sparkline8-outline-icon">
                                    <button type="button" class="btn btn-custon-three btn-primary" data-toggle="modal" data-target="#add_jo">
                                        <span class="fa fa-plus p-l-0"></span> Add JO
                                    </button>
                                </div>                                
                            </div>
                        </div>                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Date Prepared</th>
                                            <th>CENJO #/JO #</th>
                                            <th>Project Title</th>
                                            <th>Supplier</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($head AS $h){ ?>
                                        <tr>
                                            <td><?php echo date('F j, Y', strtotime($h['date'])); ?></td>
                                            <td><?php echo $h['cenjo_no'] . "/".$h['jo_no']; ?></td>
                                            <td><?php echo $h['project_title']; ?></td>
                                            <td><?php echo $h['vendor']; ?></td>
                                            <td>
                                                <center>
                                                    <a href="<?php echo base_url(); ?>jo/job_order_saved/<?php echo $h['jo_id']; ?>" class="btn btn-custon-three btn-warning btn-xs" target='_blank'>
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
        </div>
    </div>
    <!-- Data table area End-->