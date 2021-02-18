
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Update Enduse</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_enduse">
                                                    <div class="modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Enduse:</p>
                                                            <?php foreach($enduse AS $e){ ?>
                                                            <input type="text" name="enduse" class="form-control" value = "<?php echo $e->enduse_name?>">
                                                            <?php } ?>
                                                            <input type="hidden" name="enduse_id" value = "<?php echo $id; ?>">
                                                        </div>
                                                        <center>
                                                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
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