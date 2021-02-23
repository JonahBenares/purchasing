
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
                                                                <p class="m-b-0">Project / Activity:</p>
                                                                <input type = "text" name="proj_activity" class="form-control"  value = "<?php echo $pa->proj_activity?>">
                                                            </div>
                                                   <div class="form-group">
                                                            <p class="m-b-0">Status:</p>
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