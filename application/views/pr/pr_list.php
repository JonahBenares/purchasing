<script type="text/javascript">
    $(document).on("click", ".cancelPR", function () {
         var pr_id = $(this).data('id');
         $(".modal #pr_id").val(pr_id);
    });
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
                                    <li><span class="bread-blod">PR List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import_pr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel PR
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                            
                </div>
                <form method='POST' action='upload_excel_pr' enctype="multipart/form-data" target='_blank'>
                    <div class="modal-body">
                        <div class="form-group">
                            Browse your computer:
                            <input type="file" name="excelfile_pr" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary btn-block" value='Proceed'>
                        <!-- <a href="<?php echo base_url(); ?>index.php/pr/purchase_request"  class="btn btn-primary ">Proceed</a> -->
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div id="cancelPR" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel PR</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>pr/cancel_pr">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling PR:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type='hidden' name='pr_id' id = "pr_id">                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
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
                                    <h1>PR List</h1>
                                    <small>PURCHASE REQUEST</small> 
                                    <div class="sparkline8-outline-icon">
                                        <a type='button' class="btn btn-custon-three btn-primary"  data-toggle="modal" data-target="#import_pr"> 
                                            <span class="fa fa-plus p-l-0"></span> Add PR
                                        </a>
                                        <a href="<?php echo base_url(); ?>pr/pending_forrfq" class="btn btn-custon-three btn-warning"><span class="p-l-0 fa fa-clock-o"></span> Pending for RFQ</a>

                                        <a href="<?php echo base_url(); ?>pr/cancelled_pr" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled PR</a>
                                    </div>
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
                                               
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php foreach($pr_head AS $p){ ?>                                      
                                            <tr>
                                                <td><?php echo $p->pr_no."-".COMPANY;?></td>
                                                <td><?php echo $p->date_prepared;?></td>
                                                <td><?php echo date("Y-m-d",strtotime($p->date_imported));?></td>
                                                <td><?php echo $p->department;?></td>
                                                <td ><center><?php echo $p->urgency;?></center></td>
                                                <td><?php echo $p->requestor;?></td>
                                                <td>
                                                    <center>
                                                    <!--     <a href="<?php echo base_url(); ?>pr/pr_group/<?php echo $p->pr_id?>" title = "Add RFQ" class="btn btn-custon-three btn-info btn-xs">
                                                        <span class="fa fa-plus"></span>
                                                        </a> -->
                                                        <a href="<?php echo base_url(); ?>pr/purchase_request/<?php echo $p->pr_id?>" class="btn btn-custon-three btn-warning btn-xs">
                                                        <span class="fa fa-eye"></span>
                                                        </a>
                                                        <a class="cancelPR btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelPR" data-id="<?php echo $p->pr_id; ?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                        <!-- <a href="<?php echo base_url(); ?>index.php/pr/cancel_pr/<?php echo $p->pr_id?>" class="btn btn-custon-three btn-danger btn-xs">
                                                            <span class="p-l-0 fa fa-ban"></span>
                                                        </a> -->
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