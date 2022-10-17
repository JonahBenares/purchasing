<?php
$letters = range('A', 'Z');
$ci =& get_instance();
?>
<script type="text/javascript">
    $(document).on("click", ".cancelItem", function () {
         var items_id = $(this).data('id');
         var jor = document.getElementById("jor").value;
         $(".modal #items_id").val(items_id);
         $(".modal #jor").val(jor);
    });

    $(document).on("click", ".regroupItem", function () {
         var jor_items_id = $(this).data('id');
         $(".modal #jor_items_id").val(jor_items_id);
    });

     $(document).on("click", ".addVendor", function () {
         var group = $(this).data('group');
         var id = $(this).data('id');
         $(".modal #group").val(group);
         $(".modal #jor_items_id").val(id);
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
                                    <li><a href="<?php echo base_url(); ?>jor/jor_list">JOR List</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">JO Request</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="regroup_g" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-2">
                    <h4 class="modal-title">Regroup Item</h4>
                    <div class="modal-close-area modal-close-df" >
                        <a class="close" data-dismiss="modal" style="background: green" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>jor/regroup_item">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Regroup:</p>
                            <select class="form-control text-black" name='grouping'>
                                <option value='' selected="selected">-Select Group-</option>
                                <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                <option value='<?php echo $let; ?>'><?php echo $let; ?></option>
                                <?php } ?>
                            </select>
                            <input type = "hidden" name = "jor_items_id" id = "jor_items_id">
                             <input type='hidden' name='jor' id = "jor" value='<?php echo $jor_id; ?>'>
                        </div>
                        <center>              
                            <input type = "submit" class="btn btn-custon-three btn-success btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="cancelItem" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Cancel Item</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "<?php echo base_url();?>jor/cancel_item">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling Item:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type='hidden' name='jor' id = "jor" value='<?php echo $jor_id; ?>'>
                            <input type = "hidden" id='items_id' name='items_id' >                 
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Vendor
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method='POST' action='<?php echo base_url(); ?>jor/add_vendor_jorfq'>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="m-b-0">Vendor:</p>
                        <select class="form-control" name='vendor'>
                            <option value='' selected="selected">-Choose Vendor-</option>
                            <?php foreach($vendor AS $ven){ ?>
                                <option value='<?php echo $ven->vendor_id; ?>'><?php echo $ven->vendor_name; ?></option>
                            <?php } ?>
                        </select>                     
                    </div>
                    <div class="form-group">
                        <p class="m-b-0">Due Date:</p>
                        <input type="date" class="form-control" name="due_date">
                    </div>
                    <div class="form-group">
                        <p class="m-b-0">Noted by:</p>
                        <select class="form-control" name='noted'>
                             <option value='' selected="selected">-Choose Employee-</option>
                            <?php foreach($employee AS $emp){ ?>
                                <option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
                            <?php } ?>
                        </select> 
                    </div>
                    <div class="form-group">
                        <p class="m-b-0">Approved by:</p>
                        <select class="form-control" name='approved'>
                             <option value='' selected="selected">-Choose Employee-</option>
                            <?php foreach($employee AS $emp){ ?>
                                <option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
                            <?php } ?>
                        </select>  
                    </div>
                </div>
                <div class="modal-footer">
                    <input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
                    <input type='hidden' name='group' id='group' >
                    <input type='hidden' name='jor_items_id' id='jor_items_id' >
                <input type="submit" class="btn btn-primary btn-block" value="Create RFQ">
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <form method='POST' action='<?php echo base_url(); ?>jor/save_groupings'>
                                    <table class="table table-bordered">
                                        <?php 
                                            foreach($jo_head AS $jh){ 
                                                /*if($jh->jo_no!=''){
                                                    $jor_no = $jh->jo_no;
                                                }else if($jh->user_jo_no!=''){
                                                    $jor_no=$jh->user_jo_no;
                                                }*/
                                        ?>
                                        <tr>
                                            <td width="15%"><i>JO Request:</i></td>
                                            <td width="35%"><?php echo $jh->jo_request; ?></td>
                                            <td width="15%"><i>Duration:</i></td>
                                            <td width="35%"><?php echo $jh->duration; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i>Date Prepared:</i></td>
                                            <td><?php echo $jh->date_prepared; ?></td>
                                            <td><i>Completion Date:</i></td>
                                            <td><?php echo $jh->completion_date; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i>Department:</i></td>
                                            <td><?php echo $jh->department; ?></td>
                                             <td><i>Delivery Date:</i></td>
                                            <td><?php echo $jh->delivery_date; ?></td>
                                            <!-- <td><i>Processing Code:</i></td>                                            
                                            <td style="padding: 0px!important" class="bor-red">
                                                <select name = "process" class = "form-control">
                                                    <option value = "">--Select Processing Code--</option>
                                                </select>
                                            </td> -->
                                        </tr>
                                        <tr>
                                            <td><i>JO No.:</i></td>
                                            <td><?php echo $jh->jo_no."-".COMPANY; ?></td>
                                            <td><i><?php echo JO_NAME; ?> JO No.:</i></td>
                                            <td><?php echo $jh->user_jo_no; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i>Requested By:</i></td>
                                            <td><?php echo $jh->requested_by; ?></td>
                                            <td><i>Urgency:</i></td>
                                            <td><b class="text-red capital"><?php echo $jh->urgency; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><i>Purpose:</i></td>
                                            <td colspan="3"><b class="capital"><?php echo $jh->purpose; ?></b></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th width="50%">Scope Of Works</th>
                                                <th>Qty</th>
                                                <th>UOM.</th>
                                                <th>Unit Cost</th>
                                                <th>Total Cost</th>
                                                <th>Group</th>
                                                <th>Vendor</th>
                                                <?php if($cancelled==0){ ?>
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                                <?php } else { ?>
                                                <th>Cancelled By / Cancelled Date</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>     
                                        <tbody>
                                             <tr>
                                                 <td colspan='9' align="center"><b><?php echo $general_desc;?></b></td>
                                            </tr>
                                            <?php 
                                            $x=1; 
                                            if(!empty($jo_items)){
                                            foreach($jo_items AS $ji){ 
                                            ?>
                                            <?php if($ji['cancelled']==1){ ?>
                                            <tr class="tr-red">
                                                <td style="padding: 0px;height: 50px"><?php echo $ji['general_desc'];?></td>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $ji['scope_of_work'];?></td>
                                                <td><?php echo $ji['quantity']; ?></td>
                                                <td><?php echo $ji['uom']; ?></td>
                                                <td><?php echo number_format($ji['unit_cost'],2); ?></td>
                                                <td><?php echo number_format($ji['total_cost'],2); ?></td>
                                                <?php if($ji['vendor_id']==0){ ?>
                                                <td style="padding: 0px!important" class="bor-red">
                                                    <select class="form-control text-black"  name='group<?php echo $x; ?>'>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                                        <option value='<?php echo $let; ?>' <?php echo ($ji['grouping_id'] == $let) ? ' selected' : ''; ?>><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <?php }else { ?>
                                                <td align="center"><?php echo $ji['grouping_id']; ?></td>
                                                <td><?php echo $ji['vendor']; ?></td>
                                                <?php } ?>
                                                <td align="center"><?php echo $ji['cancelled_reason'] . " by ". $ji['cancelled_by']." /".date('m.d.y', strtotime($ji['cancelled_date']));?></td>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>

                                                <td><?php echo $x; ?></td>
                                                <td><textarea class="form-control" rows="10"><?php echo $ji['scope_of_work'];?></textarea></td>
                                                <td><?php echo $ji['quantity']; ?></td>
                                                <td><?php echo $ji['uom']; ?></td>
                                                <td><?php echo number_format($ji['unit_cost'],2); ?></td>
                                                <td><?php echo number_format($ji['total_cost'],2); ?></td>
                                                <?php if($ji['vendor_id']==0){ ?>
                                                <td style="padding: 0px!important" class="bor-red">
                                                    <select class="form-control" name='group<?php echo $x; ?>'>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                                        <option value='<?php echo $let; ?>' <?php echo ($ji['grouping_id'] == $let) ? ' selected' : ''; ?>><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <?php }else { ?>
                                                <td align="center"><?php echo $ji['grouping_id']; ?></td>
                                                <td><?php echo $ji['vendor']; ?></td>
                                                <?php } ?>
                                                <td align="center">
                                                <?php if($completed==0){ ?>
                                                    <?php if($saved==1){ ?>
                                                    <a href="" class="regroupItem btn btn-xs btn-success btn-custon-three" data-toggle="modal" data-target="#regroup_g" title="Regroup" data-group="" data-id="<?php echo $ji['jor_items_id']; ?>"><span class="fa fa-object-group"> </span></a>
                                                    <?php } ?>
                                                    <?php if($ji['grouping_id']!=''){ ?>
                                                    <a href="" class="addVendor btn btn-xs btn-warning btn-custon-three" data-toggle="modal" data-target="#exampleModal" title="Add Vendor" data-group="<?php echo $ji['grouping_id']; ?>" data-id="<?php echo $ji['jor_items_id']; ?>"><span class="fa fa-shopping-cart"> </span></a>
                                                    <?php } ?>
                                                    <a class="cancelItem btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelItem" data-id="<?php echo $ji['jor_items_id']; ?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                </td>
                                                 <?php } ?>
                                            </tr>
                                                <input type='hidden' name='jor_items_id<?php echo $x; ?>' value="<?php echo $ji['jor_items_id']; ?>">
                                            <?php $x++; } } } } ?>
                                        </tbody>                                   
                                    </table>
                                    <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                    <input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($jo_notes AS $jn){ 
                                                    if($cancelled==0){
                                            ?>
                                            <tr>
                                                <td style="padding: 0px"><textarea class="form-control"><?php echo $jn->notes;?></textarea></td>
                                            </tr>
                                            <?php } else{ ?>
                                             <tr class="tr-red">
                                                <td style="padding: 0px;height: 50px"><?php echo $jn->notes;?></td>
                                            </tr>
                                            <?php }  } ?>
                                        </tbody>
                                    </table>
                                    <center>
                                        <?php if($saved==0){ ?>
                                        <input type='submit' name='save_groupings' value='Save Groupings' class="btn btn-primary btn-md p-l-100 p-r-100">
                                        <?php } ?>
                                    </center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->