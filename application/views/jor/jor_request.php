<?php
$letters = range('A', 'Z');
$ci =& get_instance();
?>

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
                <form method="POST" action = "<?php echo base_url();?>pr/regroup_item">
                    <div class="modal-body-lowpad">
                        <div class="form-group">
                            <p class="m-b-0">Regroup:</p>
                            <select class="form-control text-black" name='grouping'>
                                <option value='' selected="selected">-Select Group-</option>
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
                    </div>
                    <div class="form-group">
                        <p class="m-b-0">Approved by:</p>
                        <select class="form-control" name='approved'>
                             <option value='' selected="selected">-Choose Employee-</option>
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
                                <form method='POST' action='<?php echo base_url(); ?>jor/save_groupings'>
                                    <table class="table table-bordered">
                                        <?php 
                                            foreach($jo_head AS $jh){ 
                                                if($jh->jo_no!=''){
                                                    $jor_no = $jh->jo_no;
                                                }else if($jh->user_jo_no!=''){
                                                    $jor_no=$jh->user_jo_no;
                                                }
                                        ?>
                                        <tr>
                                            <td width="15%"><i>JO Request:</i></td>
                                            <td width="35%"><?php echo $jh->jo_request; ?></td>
                                            <td width="15%"><i>Department:</i></td>
                                            <td width="35%"><?php echo $jh->department; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i>Date Prepared:</i></td>
                                            <td><?php echo $jh->date_prepared; ?></td>
                                            <td><i>Urgency:</i></td>
                                            <td><b class="text-red capital"><?php echo $jh->urgency; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><i>JO No.:</i></td>
                                            <td><?php echo $jor_no; ?></td>
                                            <!-- <td><i>Processing Code:</i></td>                                            
                                            <td style="padding: 0px!important" class="bor-red">
                                                <select name = "process" class = "form-control">
                                                    <option value = "">--Select Processing Code--</option>
                                                </select>
                                            </td> -->
                                            <td></td>
                                        </tr>
                                        <!-- <tr>
                                            <td><i>Enduse:</i></td>
                                            <td colspan="3"><b class="capital"></b></td>
                                            <td><i>WH Stock:</i></td>
                                            <td colspan="1"><b class="capital"><?php echo $h->wh_stocks; ?></b></td>
                                        </tr> -->
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
                                        </thead>     
                                        <tbody>
                                            <?php $x=1; foreach($jo_items AS $ji){ ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><textarea class="form-control" rows="10"><?php echo $ji['scope_of_work'];?></textarea></td>
                                                <td><?php echo $ji['quantity']; ?></td>
                                                <td><?php echo $ji['uom']; ?></td>
                                                <td><?php echo $ji['unit_cost']; ?></td>
                                                <td><?php echo $ji['total_cost']; ?></td>
                                                <td style="padding: 0px!important" class="bor-red">
                                                    <select class="form-control" name='group<?php echo $x; ?>' required>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($ci->createColumnsArray('ZZ') AS $let){ ?>
                                                        <option value='<?php echo $let; ?>' <?php echo ($ji['grouping_id'] == $let) ? ' selected' : ''; ?>><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <input type='hidden' name='jor_items_id<?php echo $x; ?>' value="<?php echo $ji['jor_items_id']; ?>">
                                            </tr>
                                            <?php $x++; } ?>
                                        </tbody>                                   
                                    </table>
                                    <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                    <input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
                                    <?php } ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($jo_notes AS $jn){ ?>
                                            <tr>
                                                <td style="padding: 0px"><textarea class="form-control"><?php echo $jn->notes;?></textarea></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <center>
                                        <input type='submit' name='save_groupings' value='Save Groupings' class="btn btn-primary btn-md p-l-100 p-r-100">
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