    <script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
    <style type="text/css">
        html, body.materialdesign {
            background: #2d2c2c;
        }
        .emphasis{
            /*border-bottom: 1px solid red!important;*/
            background-color: #ffe5e5!important;
        }
    </style>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="hr-bold"></div>
                        <div class="sparkline8-graph" style="text-align: left">
                        <form method='POST' action="<?php echo base_url(); ?>po/add_repeatPO"
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <h4 class="">PO No: </h4>
                                <select class="form-control" style="width: 25%" name='po' id='po' onchange="generatePO('');">
                                    <option value="" selected="">-Select PO-</option>
                                </select>   
                                <h4>PO                                                      
                                <table class="table-bordered" width="100%" style="margin-top: 20px">
                                    <thead>
                                        <tr>
                                            <th width="10%">Qty</th>
                                            <th width="10%">UOM</th>
                                            <th width="50%">Description</th>
                                            <th width="10%">Price</th>
                                            <th width="10%">Total</th>
                                            <th width="10%">PR No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="padding: 0px!important"><input type="text" name="quantity" id="quantity" value="" onblur='changePrice(<?php echo $x; ?>,0)'  onkeypress="return isNumberKey(this, event)" class="form-control emphasis" style='border:0px'></td>
                                            <td></td>
                                            <td><span style='color:red'></span>,</td>
                                            <td style="padding: 0px!important"><input type="text" name="price" id="price" value=""  onkeypress="return isNumberKey(this, event)" class="form-control" onblur='changePrice()' style='border:0px' readonly></td>
                                            <td style="padding: 0px!important">
                                                <input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="" style='text-align:right; border:0px' readonly></span>
                                            <td></td>    
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary btn-block">Save</button>
                            </div>
                        </div>   
                        <div class="hr-bold"> </div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>