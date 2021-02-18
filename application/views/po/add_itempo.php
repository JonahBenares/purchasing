<script type="text/javascript">
    function toggle_multi(source) {
      checkboxes_multi = document.getElementsByClassName('item_list');
      for(var i=0, n=checkboxes_multi.length;i<n;i++) {
        checkboxes_multi[i].checked = source.checked;
      }
    }
</script>
    <div class="admin-dashone-data-table-area m-t-15">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12" style="">
                    <div class="sparkline8-list shadow-reset">
                        <div style="height: 10px;    background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                        <div class="sparkline8-graph">
                            <h3>Add Item/s</h3>
                            <div class="datatable-dashv1-list custom-datatable-overright">  
                                <table class="table-bordered" width="100%">
                                    <tr>
                                        <td><input type="checkbox" class="form-control" onClick="toggle_multi(this)"></td>
                                        <td style="padding-left:5px">Quantity</td>
                                        <td style="padding-left:5px">Unit Price</td>
                                        <td style="padding-left:5px">Item Description</td>
                                    </tr>
                                    <?php foreach($items AS $it){ ?>
                                    <tr>
                                        <td><input type="checkbox" class="form-control item_list" name="reco[]" value="<?php echo $it['reco_id']; ?>"></td>
                                        <td style="padding-left:5px"><input type='number' name='quantity' style='width:80px' value="<?php echo $it['quantity']; ?>"></td>
                                        <td style="padding-left:5px"><?php echo number_format($it['price'],4); ?></td>
                                        <td style="padding-left:5px"><?php echo $it['offer'].", " . $it['item']. " " . $it['item_specs']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>                      
                        <div style="height: 10px; background: linear-gradient(to right, #ff9966 0%, #ff66cc 100%);}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>