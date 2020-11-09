<script src="<?php echo base_url(); ?>assets/js/aoq.js"></script> 
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-6 col-lg-offset-3" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Create AOQ</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method='POST' action="<?php echo base_url(); ?>aoq/insert_aoq">
                                                    <div class="modal-body-lowpad">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <p class="m-b-0">Date:</p>
                                                                    <input type="date" name="aoq_date" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <p class="m-b-0">PR #:</p>
                                                                    <!-- <input type="text" name="pr" class="form-control"> -->
                                                                    <select name='pr' id='pr' class="form-control" onchange='choosePR()'>
                                                                        <option value='' selected>-Select PR Number-</option>
                                                                        <?php foreach($pr AS $p){ ?>
                                                                            <option value="<?php echo $p->pr_id; ?>">
                                                                            <?php echo $p->pr_no."-".COMPANY; ?>
                                                                            </option>
                                                                        <?php }  ?> 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group">
                                                            <p class="m-b-0">Department:</p>
                                                            <span id='department_name'></span>
                                                            <input type='hidden' name='department' id='department'>
                                                            <!-- <select name='department' class="form-control">
                                                                <option value='' selected>-Select Department-</option>
                                                                <?php foreach($department AS $dept){ ?>
                                                                    <option value="<?php echo $dept->department_id; ?>">
                                                                    <?php echo $dept->department_name; ?>
                                                                    </option>
                                                                <?php }  ?> 
                                                            </select> -->
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Enduse:</p>
                                                           <!--  <select name='enduse' class="form-control">
                                                                <option value='' selected>-Select End Use-</option>
                                                                <?php foreach($enduse AS $end){ ?>
                                                                    <option value="<?php echo $end->enduse_id; ?>">
                                                                    <?php echo $end->enduse_name; ?>
                                                                    </option>
                                                                <?php }  ?> 
                                                            </select> -->
                                                            <span id='enduse_name'></span>
                                                            <input type='hidden' name='enduse' id='enduse'>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Purpose:</p>
                                                           <!--  <select name='purpose' class="form-control">
                                                                <option value='' selected>-Select Purpose-</option>
                                                                <?php foreach($purpose AS $purp){ ?>
                                                                    <option value="<?php echo $purp->purpose_id; ?>">
                                                                    <?php echo $purp->purpose_name; ?>
                                                                    </option>
                                                                <?php }  ?> 
                                                            </select> -->
                                                            <span id='purpose_name'></span>
                                                            <input type='hidden' name='purpose' id='purpose'>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Date needed:</p>
                                                            <input type="date" name="date_needed" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Requested by:</p>
                                                           <!--  <select name='requested_by' class="form-control">
                                                                <option value='' selected>-Select Employee-</option>
                                                                <?php foreach($employee AS $emp){ ?>
                                                                    <option value="<?php echo $emp->employee_id; ?>">
                                                                    <?php echo $emp->employee_name; ?>
                                                                    </option>
                                                                <?php }  ?> 
                                                            </select> -->
                                                            <span id='requested_name'></span>
                                                            <input type='hidden' name='requested_by' id='requested_by'>
                                                        </div>
                                                       <!--  <div class="form-group">
                                                            <p class="m-b-0">Remarks:</p>
                                                            <textarea cols="3" name="remarks" class="form-control" ></textarea>
                                                        </div>-->
                                                        <center>
                                                            <input type='submit' name='submit' value='Proceed' class="btn btn-custon-three btn-primary btn-block">
                                                           
                                                        </center> 
                                                    </div>
                                                   <?php
                                                    foreach($rfq as $r)
                                                    {
                                                      echo '<input type="hidden" name="rfq[]" value="'. $r. '">';
                                                    }
                                                  ?>
                                                  <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
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