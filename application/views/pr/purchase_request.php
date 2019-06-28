<script type="text/javascript">
    $(document).on("click", ".cancelItem", function () {
         var details_id = $(this).data('id');
         var pr = document.getElementById("pr").value;
         $(".modal #details_id").val(details_id);
         $(".modal #pr").val(pr);
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
                                    <li><a href="<?php echo base_url(); ?>index.php/masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
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
                                            <td><i>PR No. (User):</i></td>
                                            <td><?php echo $h->user_pr_no; ?></td>
                                            <td><i>New PR (Purchasing):</i></td>
                                            <?php if(empty($h->pr_no)){ ?>
                                                <td style="padding: 0px!important"><input type="text" class="form-control" name="new_pr" required></td>
                                            <?php } else { ?>
                                                <td><?php echo $h->pr_no; ?></td> 
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td><i>Enduse:</i></td>
                                            <td colspan="3"><b class="capital"><?php echo $h->enduse; ?> </b></td>
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
                                                <th>Item Description</th>
                                                <th>Date Needed</th>
                                                <th>Group</th>
                                                <th><center><span class="fa fa-bars"></span></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        $x=1;
                                        $letters = range('A', 'Z');
                                        foreach($details AS $det){ ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $det->quantity; ?></td>
                                                <td><?php echo $det->uom; ?></td>
                                                <td><?php echo $det->item_description; ?></td>
                                                <td><?php echo (!empty($det->date_needed) ? date('F j, Y', strtotime($det->date_needed)) : ''); ?></td>
                                                <?php if(empty($h->pr_no)){ ?>
                                                <td style="padding: 0px!important">
                                                    
                                                    <select class="form-control" name='group<?php echo $x; ?>' required>
                                                        <option value='' selected="selected">-Select Group-</option>
                                                        <?php foreach($letters AS $let){ ?>
                                                        <option value='<?php echo $let; ?>'><?php echo $let; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <?php }else { ?>
                                                <td align="center"><?php echo $det->grouping_id; ?></td>
                                                <?php } ?>
                                                <td align="center">
                                                    <a class="cancelItem btn btn-custon-three btn-danger btn-xs" data-toggle="modal" data-target="#cancelItem" data-id="<?php echo $det->pr_details_id; ?>"><span class="fa fa-ban" title="Cancel"></span></a>
                                                </td>
                                            </tr>
                                            <input type='hidden' name='pr_details_id<?php echo $x; ?>' value="<?php echo $det->pr_details_id; ?>">
                                        <?php $x++; } ?>
                                          
                                        </tbody>
                                    </table>
                                     <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                                    <input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
                                    <?php if(empty($h->pr_no)){ ?>
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