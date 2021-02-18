<?php $letters = range('A', 'Z');
$ci =& get_instance();
?>

<script type="text/javascript">
    $(document).on("click", ".cancelItem", function () {
         var details_id = $(this).data('id');
         var pr = document.getElementById("pr").value;
         $(".modal #details_id").val(details_id);
         $(".modal #pr").val(pr);
    });

    $(document).on("click", ".regroupItem", function () {
         var pr_det_id = $(this).data('id');
         $(".modal #pr_det_id").val(pr_det_id);
    });

     $(document).on("click", ".addVendor", function () {
         var group = $(this).data('group');
         var id = $(this).data('id');
         $(".modal #group").val(group);
         $(".modal #pr_details_id").val(id);
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
                                    <li><a href="<?php echo base_url(); ?>index.php/pr/pr_list">PR List</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Purchase Request</span>
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
                <form method="POST" action = "<?php echo base_url();?>pr/regroup_item">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Regroup:</p>
                            <select class="form-control text-black" name='grouping'>
                                <option value='' selected="selected">-Select Group-</option>
                                <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                <option value='<?php echo $let; ?>'><?php echo $let; ?></option>
                                <?php } ?>
                            </select>
                            <input type = "hidden" name = "pr_det_id" id = "pr_det_id">
                             <input type='hidden' name='pr' id = "pr" value='<?php echo $pr_id; ?>'>
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
                <form method="POST" action = "<?php echo base_url();?>pr/cancel_item">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Reason for Cancelling Item:</p>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                        <center>       
                            <input type='hidden' name='pr' id = "pr" value='<?php echo $pr_id; ?>'>
                            <input type = "hidden" id='details_id' name='details_id' >                 
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
                <form method='POST' action='<?php echo base_url(); ?>pr/add_vendor_rfq'>
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
                    <input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
                    <input type='hidden' name='group' id='group' >
                    <input type='hidden' name='pr_details_id' id='pr_details_id' >
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
                                <form method='POST' action='<?php echo base_url(); ?>pr/save_groupings'>
                                    <?php foreach($head AS $h){ ?>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="15%"><i>Purchase Request:</i></td>
                                            <td width="35%"><?php echo $h->purchase_request; ?></td>
                                            <td width="15%"><i>Department:</i></td>
                                            <td width="35%"><?php echo $h->department; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i>Date Prepared:</i></td>
                                            <td><?php echo date('F j, Y', strtotime($h->date_prepared)); ?></td>
                                            <td><i>Urgency:</i></td>
                                            <td><b class="text-red capital"><?php echo $h->urgency; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><i>PR No.:</i></td>
                                            <td><?php echo $h->pr_no."-".COMPANY; ?></td>
                                            <td><i>Processing Code:</i></td>
                                            <?php if($saved==0){ ?>
                                            <td style="padding: 0px!important" class="bor-red">
                                                <select name = "process" class = "form-control">
                                                    <option value = "">--Select Processing Code--</option>
                                                    <?php 
                                                        $code = range('X', 'Z'); 
                                                        foreach($code AS $c){
                                                    ?>
                                                    <option value = "<?php echo $c; ?>"><?php echo $c; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <?php }else { ?>
                                            <td><?php echo $h->processing_code; ?></td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td><i>Enduse:</i></td>
                                            <td colspan="3"><b class="capital"><?php echo $h->enduse; ?> </b></td>
                                           <!--  <td><i>WH Stock:</i></td>
                                            <td colspan="1"><b class="capital"><?php echo $h->wh_stocks; ?></b></td> -->
                                           
                                        </tr>
                                        <tr>
                                            <td><i>Purpose:</i></td>
                                            <td colspan="3"><b class="capital"><?php echo $h->purpose; ?></b></td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Qty</th>
                                                <th>UOM</th>
                                                <th>PN No.</th>
                                                <th>Item Description</th>
                                                <th>WH Stocks</th>
                                                <th>Date Needed</th>
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
                                        <?php 
                                            $x=1;
                                            if(!empty($details)){
                                            foreach($details AS $det){ 
                                        ?>
                                        <?php if($det['cancelled']==1){ ?>
                                            <tr class="tr-red">
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $det['quantity']; ?></td>
                                                <td><?php echo $det['uom']; ?></td>
                                                <td><?php echo $det['pn_no']; ?></td>
                                                <td><?php echo $det['item_description']; ?></td>
                                                <td><?php echo $det['wh_stocks']; ?></td>
                                                <td><?php echo (!empty($det['date_needed']) ? date('F j, Y', strtotime($det['date_needed'])) : ''); ?></td>
                                                <?php if(empty($h->pr_no)){ ?>
                                                <td style="padding: 0px!important" class="bor-red">
                                                    <select class="form-control text-black" name='group<?php echo $x; ?>' required>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                                        <option value='<?php echo $let; ?>' <?php echo ($det['grouping_id'] == $let) ? ' selected' : ''; ?>><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <?php }else { ?>
                                                <td align="center"><?php echo $det['grouping_id']; ?></td>
                                                <td><?php echo $det['vendor']; ?></td>
                                                <?php } ?>
                                                <td align="center"><?php echo $det['cancelled_reason'] . " by ". $det['cancelled_by']." /".date('m.d.y', strtotime($det['cancelled_date']));?></td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $det['quantity']; ?></td>
                                                <td><?php echo $det['uom']; ?></td>
                                                 <td><?php echo $det['pn_no']; ?></td>
                                                <td><?php echo $det['item_description']; ?></td>
                                                <td><?php echo $det['wh_stocks']; ?></td>
                                                <td><?php echo (!empty($det['date_needed']) ? date('F j, Y', strtotime($det['date_needed'])) : ''); ?></td>
                                                <?php if($saved==0){ ?>
                                                <td style="padding: 0px!important" class="bor-red">
                                                    <select class="form-control" name='group<?php echo $x; ?>' required>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                                        <option value='<?php echo $let; ?>' <?php echo ($det['grouping_id'] == $let) ? ' selected' : ''; ?>><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td></td>
                                                <?php }else { ?>
                                                <td align="center"><?php echo $det['grouping_id']; ?></td>
                                                <td><?php echo $det['vendor']; ?></td>
                                                <?php } ?>
                                                <td align="center">
                                                    <a href="" class="regroupItem btn btn-xs btn-success btn-custon-three" data-toggle="modal" data-target="#regroup_g" title="Regroup" data-group="" data-id="<?php echo $det['pr_details_id']; ?>"><span class="fa fa-object-group"> </span></a>
                                                    <?php if($det['grouping_id']!=''){ ?>
                                                    <a href="" class="addVendor btn btn-xs btn-warning btn-custon-three" data-toggle="modal" data-target="#exampleModal" title="Add Vendor" data-group="<?php echo $det['grouping_id']; ?>" data-id="<?php echo $det['pr_details_id']; ?>"><span class="fa fa-shopping-cart"> </span></a>
                                                    <?php } ?>
                                                    <a class="cancelItem btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelItem" data-id="<?php echo $det['pr_details_id']; ?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                            <input type='hidden' name='pr_details_id<?php echo $x; ?>' value="<?php echo $det['pr_details_id']; ?>">
                                        <?php $x++; } } ?>
                                          
                                        </tbody>
                                    </table>
                                     <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                    <input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
                                    <?php if($saved==0){ ?>
                                    <center><input type='submit' name='save_groupings' value='Save Groupings' class="btn btn-primary btn-md p-l-100 p-r-100"></center>
                                    <?php } ?>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->