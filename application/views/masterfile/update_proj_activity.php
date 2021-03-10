
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Project Activity</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_proj_act">
                                                    <div class="modal-body-lowpad">
                                                        <?php foreach($proj_act AS $pa){ ?>
                                                            <div class="form-group">
                                                                <label>Project/Activity:</label>
                                                                <input type = "text" name="proj_activity" class="form-control"  value = "<?php echo $pa->proj_activity?>">
                                                            </div>
                                                        <div class="form-group">
                                                                <label>Remarks:</label>
                                                                <textarea type = "text" name="c_remarks" class="form-control" ><?php echo $pa->c_remarks?></textarea>
                                                            </div>                                                        
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class=" col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Target Start Date:</label>
                                                                    <input name="target_start_date" class="form-control" type="text" onfocus="(this.type='date')" id="target_start_date" value = "<?php echo $pa->target_start_date?>">
                                                                </div>
                                                                <div class=" col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Target Completion:</label>
                                                                    <input name="target_completion" class="form-control" type="text" onfocus="(this.type='date')" id="target_completion" value = "<?php echo $pa->target_completion?>">
                                                                </div>
                                                            </div>   
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Actual Start:</label>
                                                                    <input name="actual_start" class="form-control" type="text" onfocus="(this.type='date')" id="actual_start" value = "<?php echo $pa->actual_start?>">
                                                                </div>
                                                                <div class="col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Actual Completion:</label>
                                                                    <input  name="actual_completion" class="form-control" type="text" onfocus="(this.type='date')" id="actual_completion" value = "<?php echo $pa->actual_completion?>">
                                                                </div>
                                                            </div>   
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class=" col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Est. Total(Materials):</label>
                                                                    <input name="est_total_materials" class="form-control" type="text" id="est_total_materials" value = "<?php echo $pa->est_total_materials?>">
                                                                </div>
                                                                <div class=" col-xs-6 col-md-6 col-lg-6">
                                                                    <label>Duration:</label>
                                                                    <input type = "text" name="duration" class="form-control"  value = "<?php echo $pa->duration?>">
                                                                </div>
                                                            </div>   
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status:</label>
                                                            <select type="text" name="status" class="form-control">
                                                                <option value = "Active" <?php echo (($pa->status == 'Active') ? ' selected' : '');?>>Active</option>
                                                                <option value = "Inactive" <?php echo (($pa->status == 'Inactive') ? ' selected' : '');?>>Inactive</option>
                                                            </select>
                                                        </div>
                                                            <input type = "hidden" name = "proj_act_id" value="<?php echo $id; ?>">
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