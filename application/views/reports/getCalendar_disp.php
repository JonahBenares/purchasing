<div style="overflow-x: scroll">
    <table class="table table-bordered">
        <tr>
            <td>PR No.</td>
            <td>Purpose</td>
            <td>Enduse</td>
            <td>Requestor</td>
            <td>Item Desc</td>
            <td>Qty</td>
            <td>Uom</td>
            <td>Supplier</td>
            <td>Unit Price</td>
            <td>Estimated Price</td>
        </tr>
         <?php 
                if(!empty($purch)){ foreach($purch AS $pc){ 

             ?>
            <tr>
                <td><?php echo $pc['pr_no']; ?></td>
                <td><?php echo $pc['purpose']; ?></td>
                <td><?php echo $pc['enduse']; ?></td>
                <td><?php echo $pc['requestor']; ?></td>
                <td><?php echo $pc['item_description']; ?></td>
                <td><?php echo $pc['quantity']; ?></td>
                <td><?php echo $pc['uom']; ?></td>
                <td><?php echo $pc['supplier']; ?></td>
                <td><?php echo number_format($pc['unit_price'],2); ?></td>
                <td><?php echo number_format($pc['estimated_price'],2); ?></td>
            </tr>
            <?php  } ?> 
            <tr>
                <td colspan="5" align="right">Total Price</td>
                <td colspan="5" align="right"><?php echo number_format($pc['total_unit'],2); ?></td>
            </tr>
            <tr>
                <td colspan="5" align="right">Total Estimated Price</td>
                <td colspan="5" align="right"><?php echo number_format($pc['total_est'],2); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>  