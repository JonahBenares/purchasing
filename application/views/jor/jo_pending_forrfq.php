    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pr.js"></script>
    <script type="text/javascript">
        $(document).on("click", "#updateDP_button", function () {
            var pr_ids= $(this).attr("data-id");
            var group_id = $(this).attr("data-trigger");
            $("#pr_ids").val(pr_ids);
            $("#group_id").val(group_id);
        });

        $(document).on("click", "#updateRO_button", function () {
            var pr_idro= $(this).attr("data-id");
            var group_idro = $(this).attr("data-trigger");
            $("#pr_idro").val(pr_idro);
            $("#group_idro").val(group_idro);
        });

        $(document).on("click", "#RfqSend", function () {
            var pr_id= $(this).attr("data-id");
            var group = $(this).attr("data-group");
            $("#pr_id").val(pr_id);
            $("#group").val(group);
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
                                    <li><a href="<?php echo base_url(); ?>index.php/jor/jor_list">JOR List</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Pending for RFQ</span>
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
                                <h1>Pending For RFQ</h1>
                                <small>JOB ORDER REQUEST List</small> 
                                <div class="sparkline8-outline-icon">
                                </div>
                            </div>
                        </div>  
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">                         
                                <!-- <form method="POST" action="<?php echo base_url(); ?>pr/create_rfq_group"> -->
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-resizable="true" data-toolbar="#toolbar" >
                                        <thead>
                                            <th ><span class="btn-block">JOR No.</span></th>
                                            <th width="10%"></th>                                        
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="btn btn-block"></span></td>
                                                <td>
                                                    <table>                                                  
                                                        <tr>
                                                            <td width="15%"><a href="" ></a><h3 class="m-b-0"><b></b></h3></td>
                                                            <td width="30%"></td>
                                                            <td width="30%"></td>
                                                            <td width="15%"> 
                                                            <center>
                                                                <div class="btn-group">
                                                                    <a href="" onclick="" class="btn btn-warning btn-sm " title="Choose Vendor">CV</a>
                                                                    <a class="btn btn-primary btn-sm" id = "RfqSend" data-toggle="modal" data-target="#modalRfq" data-id = "" data-group= "">RFQ</a>
                                                                    <a class="btn btn-info btn-sm" title="Direct Purchase" id="updateDP_button" data-id="" data-trigger="" data-toggle="modal" data-target="#directpurch">DP</a>
                                                                    <a href=""  data-toggle="modal" id="updateRO_button" data-id="" data-trigger="" data-target="#repord" class="btn btn-success btn-sm" title="Repeat Order">RO</a>
                                                                </div>
                                                            </center>
                                                            </td>
                                                        </tr>                                              
                                                    </table>
                                                </td>
                                            </tr> 
                                        </tbody>             
                                    </table>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->
    <div class="modal fade" id="modalRfq" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirmation
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> -->
                    </h5>
                    
                </div>
                <form method="POST" action="<?php echo base_url(); ?>pr/create_rfq_group">
                    <div class="modal-body">
                        Are you sure you want to create RFQ?
                    </div> 
                    <input type='hidden' name='pr_id' id="pr_id">
                    <input type='hidden' name='group' id="group">
                    <div class="modal-footer">
                        <center>
                            <input type="submit" class="btn btn-danger" data-dismiss="modal" aria-label="Close" value="Cancel">
                            <input type="submit" name="submit" class="btn btn-primary" value="Ok">
                        <center>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="directpurch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Direct Purchase
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>
                    
                </div>
                <form method="POST" action = "<?php echo base_url();?>pr/redirect_pod">
                    <div class="modal-body">
                        Date:
                        <input type="date" name="po_date" value = "<?php echo date('Y-m-d'); ?>" style = "pointer-events: none;" class="form-control" >
                        <br>
                        Vendor:
                        <select class="form-control selectpicker" name = "vendor" data-show-subtext="true" data-live-search="true">
                            <option value = ''>--Select Supplier--</option>
                            <?php foreach($supplier AS $sup){ ?>
                            <option value = "<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                            <?php } ?>
                        </select>
                    </div> 
                    <input type="hidden" name="pr_ids" id="pr_ids">
                    <input type="hidden" name="group_id" id="group_id">
                    <div class="modal-footer">
                        <input type="submit" name="submit" class="btn btn-primary btn-block" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="repord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Repeat Order
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method="POST" action = "<?php echo base_url();?>pr/create_reorderpo">
                    <div class="modal-body">
                        Date:
                        <input type="date" name="po_date" value = "<?php echo date('Y-m-d'); ?>" style = "pointer-events: none;" class="form-control">
                        <br>
                        Vendor:
                        <select class="form-control selectpicker" name = "supplier" data-show-subtext="true" data-live-search="true">
                            <option value = ''>--Select Supplier--</option>
                            <?php foreach($supplier AS $sup){ ?>
                            <option value = "<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                            <?php } ?>
                        </select>
                         Notes:
                        <textarea class="form-control" name = "notes" rows="5"></textarea>
                        <br>
                    </div> 
                    <input type="hidden" name="pr_idro" id="pr_idro">
                    <input type="hidden" name="group_idro" id="group_idro">
                    <div class="modal-footer">
                        <input type="submit" name="submit" class="btn btn-primary btn-block" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>