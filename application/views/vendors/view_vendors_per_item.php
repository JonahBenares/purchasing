    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <h4>Vendor Details</h4>
                                <table class="table-bordered" width="100%">
                                    <tr>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="2%"></td>
                                        <td width="3%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="2%"></td>
                                        <td width="3%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                        <td width="5%"></td>
                                    </tr>
                                    <?php foreach($vendors AS $v){ ?>
                                    <tr>
                                        <td colspan="3">Vendor Name:</td>
                                        <td colspan="8"><?php echo $v['vendor'];?></td>
                                        <td colspan="3">Phone Number:</td>
                                        <td colspan="8"><?php echo $v['phn_no'];?></td>                                        
                                    </tr>
                                    <tr>
                                        <td colspan="3">Address:</td>
                                        <td colspan="8"><?php echo $v['address'];?></td>
                                        <td colspan="3">Fax Number:</td>
                                        <td colspan="8"><?php echo $v['fax'];?></td>                                        
                                    </tr>
                                    <tr>
                                        <td colspan="3">Email Address:</td>
                                        <td colspan="8"><?php echo $v['email'];?></td>
                                        <td colspan="3">Contact Person:</td>
                                        <td colspan="8"><?php echo $v['contact_person'];?></td>                                        
                                    </tr>
                                    <tr>
                                        <td colspan="3">Terms:</td>
                                        <td colspan="8"><?php echo $v['terms'];?></td>
                                        <td colspan="3">Type:</td>
                                        <td colspan="8"><?php echo $v['type'];?></td>                                        
                                    </tr>
                                    <tr>
                                        <td colspan="3">Notes:</td>
                                        <td colspan="19"><?php echo $v['notes'];?></td>                                    
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>                      
                        <div class="hr-bold"></div>
                    </div>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-lg-12">
                    <button class="btn btn-custon-three btn-warning btn-block"><span class="fa fa-print"></span> Print</button>
                </div>
            </div>
        </div>
    </div>