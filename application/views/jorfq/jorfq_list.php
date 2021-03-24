<script type="text/javascript">
    $( document ).ready(function() {
        $("#createAOQ").attr("disabled", true);
        var $checkboxes = $('input[type="checkbox"]');
        $checkboxes.change(function(){
            var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
            if(countCheckedCheckboxes  > 5){
               this.checked = false;
               alert('You can only choose up to 5 RFQs.');
            } 
             if(countCheckedCheckboxes  >= 1){
                $('#createAOQ').removeAttr("disabled");
             }
        });
     });

    $(document).on("click", "#addnotes_button", function () {
         var rfq_id = $(this).attr("data-id");
         $("#rfq_id1").val(rfq_id);

    });
    $(document).on("click", ".cancelRFQ", function () {
         var rfq_id = $(this).attr("data-id");
         $("#rfq_id").val(rfq_id);

    });
</script>


    <div id="cancelRFQ" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel RFQ</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>rfq/cancel_rfq">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling RFQ:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type = "hidden" id='rfq_id' name='rfq_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addnotes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Notes
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>rfq/add_notes">
                    <div class="modal-body">
                        <textarea rows="5" class="form-control" placeholder="..." name = "notes"></textarea>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="rfq_id" id="rfq_id1">
                        <button type="submit" class="btn btn-primary btn-block">Save changes</button>
                    </div>
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
                                    <li><a href="<?php echo base_url(); ?>">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">JO RFQ List</span>
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
            <form name="myform" action="<?php echo base_url(); ?>index.php/joaoq/add_aoq" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sparkline8-list shadow-reset">
                            <div class="sparkline8-hd p-b-0" >
                                <div class="main-sparkline8-hd">
                                    <h1>JO RFQ List</h1>
                                    <small>JOB ORDER - REQUEST FOR QUOTATION</small> 
                                    <div class="sparkline8-outline-icon">
                                    <input type='submit' id='createAOQ' class="btn btn-custon-three btn-primary" value='Create AOQ' >
                                    <a href="<?php echo base_url(); ?>jorfq/jorfq_served" class="btn btn-custon-three btn-success" ><span class="fa fa-archive p-l-0"></span> Served RFQ</a> 
                                    <a href="<?php echo base_url(); ?>jorfq/jorfq_cancelled" class="btn btn-custon-three btn-danger"><span class="p-l-0 fa fa-ban"></span> Cancelled RFQ</a>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="sparkline8-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <table class="table table-bordered table-hover" id="rfqtable">
                                        <thead>
                                            <tr>
                                                <th width="5%"><input type="checkbox" class="form-control" name="" onClick="toggle_multi(this)"></th>
                                                <th width="13%">RFQ #</th>
                                                <th width="10%">JOR #</th>
                                                <th>Vendor</th>
                                                <th width="10%">RFQ Date</th>
                                                <th width="25%">Scope of Work</th>
                                                <!-- <th width="10%">Notes</th>  -->
                                                <th width="15%"><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($head AS $h){ ?>
                                            <tr>
                                                <td>
                                                    <?php if($h['completed']==1){ ?>
                                                    <input type="checkbox" class="form-control rfq_list" name="rfq[]" value="<?php echo $h['jo_rfq_id']?>">
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $h['jo_rfq_no'];?></td>
                                                <td><?php echo $h['jo_no'];?></td>
                                                <td><?php echo $h['vendor'];?></td>                                           
                                                <td>
                                                    <span style='text-align: left;'> <?php echo date('m.j.y', strtotime($h['rfq_date'])); ?></span>
                                                </td>
                                                <td style='font-size: 12px'>
                                                    <?php foreach($items AS $it){ 
                                                        if($h['jo_rfq_id']==$it['jo_rfq_id']){
                                                            echo "- ". nl2br($it['scope_of_work']) . "<br>";
                                                        } 
                                                     } ?>
                                                </td>
                                                <!-- <td><small></small></td> -->
                                                <td>
                                                    <center>
                                                        <a href="<?php echo base_url(); ?>jorfq/jorfq_outgoing/<?php echo $h['jo_rfq_id']?>" target='_blank' class="btn btn-custon-three btn-warning btn-xs" title="View RFQ Complete">
                                                            <span class="fa fa-eye"></span>
                                                        </a>
                                                        <!-- <a class="btn btn-custon-three btn-secondary btn-xs reviseRFQ" title="Add Notes" data-toggle="modal" data-target="#addnotes" id="addnotes_button" data-id="">
                                                            <span class="fa fa-plus"></span>
                                                        </a> -->
                                                        <?php if($h['completed']==0){ ?>
                                                        <a href="<?php echo base_url(); ?>jorfq/complete_rfq/<?php echo $h['jo_rfq_id']?>" class="cancelRFQ btn btn-custon-three btn-info btn-xs"  onclick="return confirm('Are you sure?')"><span class="fa fa-check" title="Canvass Complete"></span>
                                                        </a>  
                                                        <?php } ?>                 
                                                        <a class="cancelRFQ btn btn-custon-three btn-danger btn-xs cancelRFQ" data-toggle="modal" data-target="#cancelRFQ" data-id=""><span class="fa fa-ban" title="Cancel"></span>
                                                        </a>
                                                        <a href="<?php echo base_url(); ?>rfq/serve_rfq/<?php echo $h['jo_rfq_id']?>" class="btn btn-custon-three btn-success btn-xs" onclick="return confirm('Are you sure this RFQ is already served?')" title="Served"><span class=" fa fa-archive"></span>
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