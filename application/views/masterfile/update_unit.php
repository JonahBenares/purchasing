
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Unit</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_unit">
                                                    <div class="modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Unit:</p>
                                                            <?php foreach($unit AS $d){ ?>
                                                            <input type="text" name="unit" class="form-control" value = "<?php echo $d->unit_name?>">
                                                            <?php } ?>
                                                            <input type = "hidden" name = "unit_id" value="<?php echo $id; ?>">
                                                        </div>
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