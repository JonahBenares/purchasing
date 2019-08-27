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
                                <small>PURCHASE REQUEST List</small> 
                                <div class="sparkline8-outline-icon">
                                </div>
                            </div>
                        </div>  
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                
                                    <table class="table table-bordered" >
                                        <tr>
                                            <th>PR No.</th>
                                            <th>
                                                <table  width="100%">
                                                    <tr>
                                                        <td>Group</td>
                                                        <td>Item</td>
                                                        <td>Vendor</td>
                                                    </tr>
                                                </table>
                                            </th>
                                            
                                        </tr>
                                    <?php if(!empty($head)){ foreach($head as $h){ ?>
                                        <form method="POST" action="<?php echo base_url(); ?>pr/create_rfq_group">
                                        <tr>
                                            <td width="11%"><?php echo $h['pr_no']; ?></td>
                                            <td style="padding: 0px!important">
                                                <table class="table-bordered" width="100%">
                                                  
                                                    <tr>
                                                        <td width="15%"><a href="" ></a><h3 class="m-b-0"><b><?php echo 'Group ' . $h['group']; ?></b></h3></td>
                                                        <td width="30%"><?php echo $h['item']; ?></td>
                                                        <td width="30%"><?php echo $h['vendor']; ?></td>
                                                        <td width="15%">
                                                        <?php if(empty($h['vendor'])){ ?>
                                                            <a href="" onclick="choose_vendor('<?php echo base_url(); ?>', '<?php echo $h['group']; ?>','<?php echo $h['pr_id']; ?>')" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                        <?php } else { ?>
                                                            <input type='hidden' name='pr_id' value='<?php echo $h['pr_id']; ?>'>
                                                            <input type='hidden' name='group' value='<?php echo $h['group']; ?>'>
                                                            <input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                                        <?php } ?>
                                                        <center>
                                                            <div class="btn-group">
                                                                <a href="" onclick="choose_vendor('<?php echo base_url(); ?>', '<?php echo $gr['group']; ?>','<?php echo $pr_id; ?>')" class="btn btn-info btn-md ">DP</a>
                                                                <a href="" onclick="choose_vendor('<?php echo base_url(); ?>', '<?php echo $gr['group']; ?>','<?php echo $pr_id; ?>')" class="btn btn-success btn-md ">RO</a>
                                                            </div>
                                                        </center>
                                                        </td>
                                                    </tr>
                                              
                                                </table>
                                            </td>
                                        </tr> 
                                         </form>
                                        <?php } } ?>
                                        <!-- <tr>
                                            <td width="15%"><a href="" ></a><h3 class="m-b-0"><b>asdasdasd</b></h3></td>
                                            <td width="32%">asdasd</td>
                                            <td width="32%">asdasd</td>
                                            <td width="10%">
                                                <a href="" onclick="choose_vendor()" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td width="15%"><a href="" ></a><h3 class="m-b-0"><b>asdasdasd</b></h3></td>
                                            <td width="32%">asdasd</td>
                                            <td width="32%">asdasd</td>
                                            <td width="10%">
                                                <a href="" onclick="choose_vendor()" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%"><a href="" ></a><h3 class="m-b-0"><b>asdasdasd</b></h3></td>
                                            <td width="32%">asdasd</td>
                                            <td width="32%">asdasd</td>
                                            <td width="10%">
                                                <a href="" onclick="choose_vendor()" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                            </td>
                                        </tr>            -->                    
                                    </table>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->