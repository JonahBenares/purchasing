    <script src="<?php echo base_url(); ?>assets/js/jo.js"></script> 
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
       <script type="text/javascript">
$(document).on("click", ".cancelJOI", function () {
     var joi_id = $(this).attr("data-id");
     $("#joi_id").val(joi_id);

});

$(document).on("click", "#approve_rev", function () {
     var joi_id = $(this).attr("data-id");
     $("#jo_id1").val(joi_id);

});

</script>
    <div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="approveLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approved Revision <span class="fa fa-thumbs-o-up"></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>joi/approve_revision">
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="m-b-0">Approved by:</p>
                            <input type="text" name="approve_rev" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Approved Date:</p>
                            <input type="date" name="approve_date" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="joi_id" id="jo_id1" >
                        <input type='submit' value='Approve' class="btn btn-custon-three btn-primary btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="cancelJOI" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel JO</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>joi/cancel_joi">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling JO:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='joi_id' name='joi_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_jo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add JO
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>               
                </div>
                <form method='POST' action="<?php echo base_url(); ?>joi/create_joi">
                    <div class="modal-body">
                        <div class="">
                            <div class="form-group">
                                <p class="m-b-0">Date:</p>
                                <input type="date" name="joi_date" value = "<?php echo date('Y-m-d'); ?>" style = "pointer-events: none;" class="form-control">
                            </div>
                            <div class="form-group btn-block m-b-5">
                                TO:
                                <select name='vendor' id='supplier' onchange="chooseSupplierJO()" class='form-control selectpicker' data-live-search="true">
                                <option value=''>-Select Vendor-</option>
                                <?php foreach($vendor AS $ven){ ?>
                                <option value='<?php echo $ven->vendor_id; ?>'><?php echo $ven->vendor_name; ?></option>
                            <?php } ?>
                            </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><p  id='address'>Address</p></div>
                                 <div class="col-md-6"><p id='phone'>Contact Number</p></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class = "col-md-6">
                                    <div class="form-group btn-block">
                                        JO No.:
                                        <select name="jo_no" id='jo_no' onchange="chooseJO()" style = 'width:100%;' class="form-control select2"></select>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group btn-block">
                                       <?php echo JO_NAME;?> JO No.:
                                        <input type="Text" name="cenjo_no" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group btn-block">
                                        Date Needed:
                                        <input type="date" name="date_needed" class="form-control">
                                    </div>
                                    <div class="form-group btn-block">
                                        Date Prepared:
                                        <input type="date" name="date_prepared" id="date_prepared" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group btn-block">
                                       Start of Work:
                                        <input type="date" name="work_start" class="form-control">
                                    </div>
                                    <div class="form-group btn-block">
                                       Completion of Work:
                                        <input type="date" name="work_completion" id="work_completion" class="form-control">
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group btn-block">
                                Project Title/Description:
                                <textarea name="project_title" id="project_title" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="jor_aoq_id" id="jor_aoq_id">
                        <input type="submit" class="btn btn-primary btn-block" value="Proceed">                        
                    </div>
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                  
                </form>
            </div>
        </div>
    </div>
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
                                    <li><span class="bread-blod">JOI List</span>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1>JOI List</h1>
                                <small>JOB ORDER ISSUANCE</small> 
                                <div class="sparkline8-outline-icon">
                                    <button type="button" class="btn btn-custon-three btn-primary" data-toggle="modal" data-target="#add_jo">
                                        <span class="fa fa-plus p-l-0"></span> Add JO
                                    </button>
                                    <a href="<?php echo base_url(); ?>joi/served_jo" class="btn btn-custon-three btn-success"><span class="p-l-0 fa fa-check"></span> Delivered JOI</a> 
                                    <a href="<?php echo base_url(); ?>joi/incom_jodel" class="btn btn-custon-three btn-warning"><span class="p-l-0 fa fa-adjust"></span> Incomplete JOI Delivery</a> 
                                    <a href="<?php echo base_url(); ?>joi/cancelled_joi" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled JOI</a>
                                </div>                                
                            </div>
                        </div>                       
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>Date Prepared</th>
                                            <th>Date Needed</th>
                                            <th>CENJO #/JO #</th>
                                            <th>Project Title</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
                                            <th>Mode of Purchase</th>
                                            <th><center><span class="fa fa-bars"></span></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($head)){ foreach($head AS $h){ ?>
                                        <tr>
                                            <td><?php echo $h['date_prepared'];?></td>
                                            <td><?php echo $h['date_needed'];?></td>
                                            <td>
                                                <a class="btn-link txt-primary" onclick="viewHistoryjoi('<?php echo base_url(); ?>','<?php echo $h['joi_id']; ?>','<?php echo $h['cenpri_jo_no']; ?>','<?php echo $h['joi_no']; ?>')"><?php echo $h['cenpri_jo_no'] . "/".$h['joi_no'] ."-".COMPANY. (($h['revision_no']!=0) ? ".r".$h['revision_no'] : "");?></a>
                                            </td>
                                            <td><?php echo $h['project_title']; ?></td>
                                            <td><?php echo $h['vendor']; ?></td>
                                            <td>
                                            <?php 

                                            if($h['revised']==1) {
                                                echo '<span class="label label-warning">Request for Revision</span>';
                                            } else {
                                                if($h['served']==0 && $h['saved']==1) {
                                                    echo '<span class="label label-warning">PO Issued</span>';
                                                } else if($h['served']==1) {
                                                  echo '<span class="label label-success">Delivered</span>'; 
                                                } 

                                                 if($h['draft']==1) {
                                                  echo '<span class="label label-info">Draft</span>'; 
                                                }
                                            } ?></td>
                                            <td><?php
                                                if($h['joi_type']==0){
                                                    echo "Purchase Request";
                                                } else if($h['joi_type']==1){
                                                    echo "Direct Purchase";
                                                } else if($h['joi_type']==2){
                                                    echo "Repeat Order";
                                                }
                                            ?>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php if($h['saved']==1){ ?> 
                                                    <a href="" class="btn btn-custon-three btn-success btn-xs deliverjoi" title='Deliver JOI' onclick="deliver_jo('<?php echo base_url(); ?>','<?php echo $h['joi_id']?>','<?php echo $h['joi_dr_id']?>')">
                                                        <span class="fa fa-truck"></span>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if($h['saved']==0 && $h['joi_type']==0 && $h['revised']==0){ ?>
                                                    <a href="<?php echo base_url(); ?>joi/jo_issuance/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else if($h['saved']==0 && $h['joi_type']==0 && $h['draft']==1 && $h['revised']==0){ ?>
                                                    <a href="<?php echo base_url(); ?>joi/jo_issuance_draft/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else if($h['saved']==0 && $h['draft']==0  && $h['joi_type']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>jod/jo_direct/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs" title='View'>
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else if($h['saved']==1 && $h['joi_type']==0 && $h['revised']==1){ ?>
                                                    <a href="<?php echo base_url(); ?>joi/jo_issuance_rev/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php }else if($h['saved']==1 && $h['joi_type']==0 && $h['revised']==0) { ?>
                                                    <a href="<?php echo base_url(); ?>joi/jo_issuance_saved/<?php echo $h['joi_id']?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                    <?php } if($h['revised']==1){ ?>
                                                    <a class="btn btn-custon-three btn-info btn-xs approverev" title='Aprrove Revision' data-toggle="modal" id="approve_rev" data-target="#approve" data-id="<?php echo $h['joi_id']?>">
                                                        <span class="fa fa-thumbs-up"></span>
                                                    </a>
                                                    <?php } ?>
                                                    <a class="cancelJOI btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelJOI" data-id="<?php echo $h['joi_id']?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                </center>
                                            </td>
                                        </tr>
                                        <?php } }  ?>          
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2();
    </script>
    <!-- Data table area End-->