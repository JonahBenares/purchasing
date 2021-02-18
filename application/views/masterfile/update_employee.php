
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Employee</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_employee">
                                                    <div class="modal-body-lowpad">
                                                        <?php foreach($emp AS $e){ ?>
                                                            <div class="form-group">
                                                                <p class="m-b-0">Employee Name:</p>
                                                                <input type = "text" name="emp_name" class="form-control"  value = "<?php echo $e->employee_name?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <p class="m-b-0">Department:</p>
                                                                <select name="dept" class="form-control" cols="2">
                                                                    <option value = "">--Select Department--</option>
                                                                    <?php foreach($department AS $d){ ?>
                                                                    <option value = "<?php echo $d->department_id; ?>" <?php echo (($e->department_id == $d->department_id) ? ' selected' : '');?>><?php echo $d->department_name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <p class="m-b-0">Position:</p>
                                                                <input type="text" name="position" class="form-control" value = "<?php echo $e->position?>">
                                                            </div>
                                                            <input type = "hidden" name = "emp_id" value="<?php echo $id; ?>">
                                                            <!-- <div class="form-group">
                                                                <p class="m-b-0">Location:</p>
                                                                <input type="text" name="" class="form-control">
                                                            </div> -->
                                                        <?php } ?>
                                                        <center>
                                                            <input type = "submit" class="btn btn-custon-three btn-info btn-block" value = "Update">
                                                        </center>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>                      
                        <div style="height: 10px; background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>