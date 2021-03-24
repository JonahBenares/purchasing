    <div class="breadcome-area mg-b-10 small-dn">
       <br>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <form method='POST' action="<?php echo base_url(); ?>jor/search_vendor">
                                    <!-- <div style="padding:10px; background: #ffd9cead">
                                        <table width="100%">
                                            <tr>
                                                <td width="33%">Due Date:</td>
                                                <td width="1%"></td>
                                                <td width="33%">Noted by:</td>
                                                <td width="1%"></td>
                                                <td width="33%">Approved by:</td>
                                            </tr>
                                            <tr>
                                                <td width="33%"><input type="date" class="form-control" name="due_date"></td>
                                                <td width="1%"></td>
                                                <td width="33%">
                                                    <select class="form-control" name="noted_by">
                                                        <option value = "">--Select Noted By--</option>
                                                        <?php foreach($employees AS $e){ ?>
                                                        <option value = '<?php echo $e->employee_id; ?>'><?php echo $e->employee_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%"></td>
                                                <td width="33%">
                                                    <select class="form-control" name="approved_by">
                                                        <option value = "">--Select Approved By--</option>
                                                        <?php foreach($employees AS $e){ ?>
                                                        <option value = '<?php echo $e->employee_id; ?>'><?php echo $e->employee_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <br> -->
                                    <table width="100%">
                                        <tr>
                                            <td width="20%"><label>Choose Category:</label></td>
                                            <td width="60%"><input type='text' name='category' class='form-control '></td>
                                            <td width="20%"><input type='submit' value='Search' class="btn btn-success btn-block"></td>
                                        </tr>
                                    </table>
                                    <input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
                                    <input type='hidden' name='group' value='<?php echo $group; ?>'>
                                </form>
                                <br>
                                <form method='POST' action="<?php echo base_url(); ?>jor/insert_vendor">
                               
                                <table width="100%">
                                    <tr>
                                        <td width="20%">Searched Category:</td>
                                        <td><h3><b><?php echo $category; ?></b></h3></td>
                                    </tr>
                                </table>
                                <br>
                                Vendor List
                                <table class="table table-bordered">     

                                <?php foreach($vendor AS $v){ ?>                                  
                                    <tr>
                                        <td width="10%"><input type="checkbox" name="vendor_id[]" value="<?php echo $v->vendor_id; ?>" class="form-control"></td>
                                        <td width="90%"><?php echo $v->vendor_name; ?></td>
                                    </tr>
                                <?php } ?>
                                </table>
                                 <div style="padding:10px; background: #ffd9cead">
                                    <table width="100%">
                                        <tr>
                                            <td width="1%"></td>
                                            <td width="33%">Noted by:</td>
                                            <td width="1%"></td>
                                            <td width="33%">Approved by:</td>
                                        </tr>
                                        <tr>
                                            <td width="1%"></td>
                                            <td width="33%">
                                                <select class="form-control" name="noted_by">
                                                    <option value = "">--Select Noted By--</option>
                                                    <?php foreach($employees AS $e){ ?>
                                                    <option value = '<?php echo $e->employee_id; ?>' <?php echo (($noted_by == $e->employee_id) ? ' selected' : '');?>><?php echo $e->employee_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%"></td>
                                            <td width="33%">
                                                <select class="form-control" name="approved_by">
                                                    <option value = "">--Select Approved By--</option>
                                                    <?php foreach($employees AS $e){ ?>
                                                    <option value = '<?php echo $e->employee_id; ?>' <?php echo (($approved_by == $e->employee_id) ? ' selected' : '');?>><?php echo $e->employee_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                                <center><input type='submit' class="btn btn-primary btn-md btn-block" value='Save'></center>  
                                <input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
                                <input type='hidden' name='group' value='<?php echo $group; ?>'>   
                                </form>                         
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data table area End-->