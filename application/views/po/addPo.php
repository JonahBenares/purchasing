<script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
<link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
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
                    <form method='POST' action="<?php echo base_url(); ?>po/add_repeatPO">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <h4 class="">PO No: </h4>
                            <div class="row">
                                <div class = "col-md-3">
                                    <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" style="width: 25%" name='po' id='po' onchange="generatePO('<?php echo base_url(); ?>','<?php echo $vendor_id; ?>','<?php echo $po_id; ?>','<?php echo $pr_id; ?>','<?php echo $group_id; ?>',this.value);">
                                        <option value="" selected="">-Select PO-</option>
                                        <?php foreach($head As $h){ ?>
                                            <option value="<?php echo $h->po_id; ?>"><?php echo $h->po_no."-".COMPANY; ?></option>
                                        <?php } ?>
                                    </select>   
                                </div>
                            </div>
                            <h4>PO                                                      
                            <table class="table-bordered" width="100%" style="margin-top: 20px">
                                <thead>
                                    <tr>
                                         <th width="15%"></th>
                                        <th width="10%">Qty</th>
                                        <th width='5%'>PR Qty</th>
                                        <th width="10%">UOM</th>
                                        <th width="35%">Description</th>
                                        <th width="10%">Price</th>
                                        <th width="10%">Total</th>
                                        <th width="10%">PR No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $x=1;
                                    if(!empty($items)){
                                     foreach($items AS $i){ ?>
                                    
                                    <tr>
                                          <td style="padding: 0px!important"><select class="form-control" id = "pr_details_id<?php echo $x; ?>" name='pr_details_id<?php echo $x; ?>' onchange='getqty(this,<?php echo $x; ?>)'>
                                            <option value='' selected>-Choose Item-</option>
                                            <?php foreach($pr_det AS $det){ ?>
                                                <option value="<?php echo $det['pr_details_id']; ?>"><?php echo $det['item_description']; ?></option>
                                                 <!-- <input type='hidden' id='qty<?php echo $x; ?>' value="<?php echo $det['quantity']; ?>">  -->
                                            <?php } ?>

                                        </select></td>
                                        <td style="padding: 0px!important"><input type="text" name="quantity<?php echo $x; ?>" id="quantity<?php echo $x; ?>" onblur="check_prdet(<?php echo $x; ?>);" onkeyup='changePrice(<?php echo $x; ?>,0)'  onkeypress="return isNumberKey(this, event)" class="form-control emphasis" style='border:0px'></td>
                                        <td><input type='text' disabled name='qty<?php echo $x; ?>' id='qty<?php echo $x; ?>' style='width:80px'> </td>
                                        <td><?php echo $i['uom']; ?></td>
                                        <td><?php echo $i['offer']; ?></td>
                                        <td style="padding: 0px!important"><input type="text" name="price<?php echo $x; ?>" id="price<?php echo $x; ?>" value="<?php echo $i['price']; ?>"  onkeypress="return isNumberKey(this, event)" class="form-control" onkeyup='changePrice(<?php echo $x; ?>,0)' style='border:0px' readonly></td>
                                        <td style="padding: 0px!important">
                                            <input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' style='text-align:right; border:0px' readonly></span>
                                        <td><?php echo $i['pr_no']; ?></td>    
                                    </tr>
                                    <input type='hidden' name='po_items_id<?php echo $x; ?>' value="<?php echo $i['item_id']; ?>"> 
                                    <input type='hidden' name='pr_id' value="<?php echo $pr_id; ?>">
                                    <input type='hidden' name='group_id' value="<?php echo $group_id; ?>"> 
                                    
                                   <!--<input type='text' name='pr_details_id<?php echo $x; ?>' value="<?php echo $i['pr_details_id']; ?>">-->
                                    <?php $x++; } 
                                } $counter = $x-1; ?>
                              <!--   <?php $y=1; foreach($pr_det AS $p){ ?>
                                       <input type='text' name='pr_details_id<?php echo $y; ?>' value="<?php echo $p['pr_details_id']; ?>">
                                <?php $y++; } ?> -->
                                </tbody>
                            </table>
                             <input type='hidden' name='old_po' value="<?php echo $old_po; ?>"> 
                             <input type='hidden' name='vendor_id' value="<?php echo $vendor_id; ?>"> 
                            <input type='hidden' name='po_id' value="<?php echo $po_id; ?>">   
                            <input type='hidden' name='count_item' value="<?php echo $x; ?>">
                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                            <input type='submit' class="btn btn-primary btn-block" id="save" value='Save'>
                        </div>
                    </div>   
                </form>
                    <div class="hr-bold"> </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  function getqty(id, count){
    var loc= document.getElementById("baseurl").value;
    var pr_details_id = id.value;
    
      $.ajax({
            type: 'POST',
            url: loc+'po/quantity_of_pr',
            data: 'id='+pr_details_id,
            success: function(data){
             
                document.getElementById("qty"+count).value =  data;
           }
     }); 
  }

    function check_prdet(count){
        var pr_details_id = document.getElementById("pr_details_id"+count).value;
        var qty = parseFloat(document.getElementById("quantity"+count).value);
        if(qty!='' && pr_details_id==''){
            alert('Please select item in the dropdown');
        }

        var pr_qty = parseFloat(document.getElementById("qty"+count).value);
        if(qty>pr_qty){
            alert("PR quantity is less than your PO quantity!");
            $("#save").hide();
        }else{
            $("#save").show();
        }
    }


    /*$(document).on("click", "#submit", function () {
        var count_item = document.getElementById("count_item").value;
        for(x=1;x<=count_item;x++){
            var pr_details_id = document.getElementById("pr_details_id"+x).value;
            var qty = document.getElementById("quantity"+x).value;
            if(qty!='' && pr_details_id==''){
                alert('Please select item in the dropdown');
            }else if(qty!='' && pr_details_id!=''){
                var data = $("#addpo").serialize();
                var loc= document.getElementById("baseurl").value;
                var redirect=loc+'po/add_repeatPO';
                $.ajax({
                    type: "POST",
                    url: redirect,
                    data: data,
                    success: function(output){
                        //alert(output);
                        window.onunload = refreshParent;
                        function refreshParent() {
                            window.opener.location.reload();
                        }
                        window.close();
                    }
                });
            }
        }
    });*/
    $('.select2').select2();
</script>