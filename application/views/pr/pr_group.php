    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pr.js"></script>
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
                                    <li><a href="<?php echo base_url(); ?>index.php/pr/purchase_request">Purchase Request</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">PR Group</span>
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
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <form method="POST" action="<?php echo base_url(); ?>pr/create_rfq">
                                    <h4>PR no: <b><?php echo $pr_no."-".COMPANY; ?></b></h4>
                                    <table class="table table-bordered" >
                                        <tr>
                                            <th>Group</th>
                                            <th>Items</th>
                                            <th>Supplier</th>
                                            <th><small>Due Date, Noted by, Approved by</small></th>
                                            <th><span class="fa fa-bars"></span></th>
                                        </tr>
                                        <?php 
                                            foreach($group AS $gr){
                                         ?>
                                        <tr>
                                            <td width="15%"><a href="" ></a>
                                            <h3 class="m-b-0"><b>Group <?php echo $gr['group']; ?></b></h3>
                                            </td>
                                            <td width="17%">
                                                <?php foreach($items AS $it){ 
                                                    if($gr['group'] == $it['group_id']){
                                                        echo "-" . $it['item_desc'] . "<br>";
                                                    }
                                                } ?>
                                            </td>
                                            <td width="17%">                                                
                                                <?php 
                                                if(!empty($vendor)){
                                                     foreach($vendor AS $ven){ 
                                                        if($gr['group'] == $ven['group_id']){ 
                                                            //echo "<a href = '' class='btn btn-xs btn-danger'><span class='fa fa-times'></span></a> - " . $ven['vendor'] . "<br>";
                                                ?>
                                                    <a href = '<?php echo base_url(); ?>pr/delete_vendor/<?php echo $pr_id; ?>/<?php echo $ven['pr_vendors_id']; ?>' class='btn btn-xs btn-danger' onclick="confirmationDelete(this);return false;"><span class='fa fa-times'></span></a> - <?php echo $ven['vendor'] . "<br>" ?>
                                                <?php } } }  ?>
                                            </td>
                                            <td width="20%">
                                                <?php 
                                                 if(!empty($vendor_app)){
                                                    foreach($vendor_app AS $venap){ 
                                                        if($gr['group'] == $venap['group_id']){ 
                                                ?>
                                                <h6 class="nomarg">Due Date: <b class="txt-primary"><?php echo (!empty($venap['due_date']) ? date("F d, Y", strtotime($venap['due_date'])) : ""); ?></b></h6>
                                                <h6 class="nomarg">Noted by: <b class="txt-primary"><?php echo $venap['noted_by']; ?></b></h6>
                                                <h6 class="nomarg">Approved by: <b class="txt-primary"><?php echo $venap['approved_by']; ?></b></h6>
                                                <?php } } } ?>
                                            </td>
                                            <td width="10%"><a href="" onclick="choose_vendor('<?php echo base_url(); ?>', '<?php echo $gr['group']; ?>','<?php echo $pr_id; ?>')" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                
                                            </td>
                                        </tr>
                                        <?php } ?>
                                      
                                    </table>
                                    <input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
                                    
                                    <center><input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->