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
                                    <li><a href="<?php echo base_url(); ?>index.php/masterfile/dashboard">Home</a> <span class="bread-slash">/</span>
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
                                <form method="POST" action="<?php echo base_url(); ?>pr/">
                                    <table class="table table-bordered" >
                                        <!-- <?php 
                                            foreach($pending AS $it){  echo $it['count_group'];
                                        ?>
                                        <tr>
                                            <td rowspan="<?php echo $it['count_group'] ?>" width="11%"><?php //echo $it['pr'];?></td>
                                            <td width="15%"><a href="" ></a><h3 class="m-b-0"><b>Group <?php echo $it['group'];?></b></h3></td>
                                            <td width="32%">asdasd</td>
                                            <td width="32%">asdasd</td>
                                            <td width="10%">
                                                <a href="" onclick="choose_vendor()" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                            </td>
                                        </tr>
                                        <?php } ?>  -->    
                                        <?php foreach($head AS $h){ ?>
                                        <tr>
                                            <td width="11%"><?php echo $h['pr_no'];?></td>
                                            <td style="padding: 0px!important">
                                                <table class="table-bordered" width="100%">
                                                    <?php foreach($det AS $d){
                                                        if($h['pr_id']==$d['pr_id']){
                                                            $item='';
                                                            foreach($items AS $it){ 
                                                                if($d['pr_id']==$it['pr_id']){
                                                                    $item .="<b>- ".$it['item']."<br>";
                                                                }
                                                            }
                                                            $item = substr($item, 0, -2);
                                                     ?>
                                                    <tr>
                                                        <td width="15%"><a href="" ></a><h3 class="m-b-0"><b><?php echo $d['group'];?></b></h3></td>
                                                        <td width="32%"><?php echo $item; ?></td>
                                                        <td width="32%">asdasd</td>
                                                        <td width="10%">
                                                            <a href="" onclick="choose_vendor()" class="btn btn-warning btn-md btn-block">Choose Vendor</a>
                                                            <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Create RFQ' onclick="return confirm('Are you sure you want to create RFQ?')"></center>
                                                        </td>
                                                    </tr>
                                                <?php } } ?>
                                                </table>
                                            </td>
                                        </tr> 
                                        <?php } ?>    
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
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->