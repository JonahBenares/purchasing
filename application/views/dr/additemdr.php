    <script src="<?php echo base_url(); ?>assets/js/dr.js"></script> 
    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
        .td{
            padding: 0px!important;            
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: left">
                            <form method='POST' action="<?php echo base_url(); ?>dr/add_dr_item">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="form-group">
                                    <h5 class="nomarg">Supplier:</h5>

                                    <h5 class="nomarg">
                                         <select name='supplier' id='supplier' class="form-control"  onchange='chooseSupplier()'>
                                            <option value='' selected>-Select Supplier-</option>
                                             <?php foreach($supplier AS $sup){ ?>
                                                <option value="<?php echo $sup->vendor_id; ?>"><?php echo $sup->vendor_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </h5>
                                </div>
                                <div class="form-group">
                                    <h5 class="nomarg">Item Description:</h5>

                                    <h5 class="nomarg"><b>
                                        <select name='items' id='items' class="form-control">
                                        </select>
                                    </b></h5>
                                </div>                            
                                <div class="form-group">
                                    <h5 class="nomarg">Delivered:</h5>
                                    <h5 class="nomarg"><b>
                                        <input type="number" name="delivered" class="form-control">
                                    </b></h5>                                  
                                </div>                            
                                <div class="form-group">
                                    <h5 class="nomarg">Remarks:</h5>
                                    <h5 class="nomarg">
                                        <b>
                                        <textarea type="text" name="remarks" class="form-control"></textarea>
                                        </b>
                                    </h5>                                   
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" value="Save changes">
                                <input type="hidden" name="dr_id" value="<?php echo $dr_id; ?>">
                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                            </div>
                        </form>
                        </div>   
                        <div class="hr-bold"></div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>