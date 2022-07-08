  	<script src="<?php echo base_url(); ?>assets/js/jo.js"></script> 
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Procurement System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon
    		============================================ -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/message/logo4.ico">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mixins.css">
	</head>
	<script type="text/javascript">
		$(document).on("click", "#addnotes_button", function () {
		     var rfd_id = $(this).attr("data-id");
		     $("#rfd_id").val(rfd_id);

		});
	</script>
  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .pad{
        	padding:0px 250px 0px 250px
        }
        .cancel{
        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
        }	
        .table-bordered>tbody>tr>td, 
        .table-bordered>tbody>tr>th, 
        .table-bordered>tfoot>tr>td, 
        .table-bordered>tfoot>tr>th, 
        .table-bordered>thead>tr>td, 
        .table-bordered>thead>tr>th,
        .all-border
        {
		    border: 1px solid #000!important;
		}
		.f13{
			font-size:13px!important;
		}
		.bor-btm{
			border-bottom: 1px solid #000;
		}
		.bor-top{
			border-top: 1px solid #000;
		}
		.bor-right{
			border-right: 1px solid #000;
		}
		.sel-des{
			border: 0px!important;
		}
		@media print{
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
	        }
			.pad{
        	padding:0px 0px 0px 0px
        	}
			#prnt_btn{
				display: none;
			}
			.emphasis{
				border: 0px solid #fff!important;
			}
			.text-red{
				color: red!important;
			}
			.bor-right{
				border-right: 1px solid #000;
			}
			.cancel{
	        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
	        	background-repeat:no-repeat!important;
	        	background-size: contain!important;
	        	background-position: center center!important;
	        }
		}
		.text-white{
			color: #fff;
		}
		.select-des{			
		    -webkit-appearance: none;
		    border: 0px;
		}
	
		.emphasis{
			border-bottom: 2px solid red;
		}
		.text-red{
			color: red;
		}
		.nomarg{
			margin: 0px 2px 0px 2px;
		}
    </style>
    
    <div  class="pad">
    	<!-- <?php if($rows_rfd ==0){
    		$url = base_url().'joi/save_joi_rfd';
    	} else if($rows_rfd!=0 && $saved==0){
    		$url = base_url().'joi/update_rfd';
    	} ?> -->
    	<form method='POST' action='<?php echo base_url(); ?>joi/save_joi_rfd'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<input type='submit' id = "submit" class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">
					</div>
					<p class="text-white">Instructions: When printing REQUEST FOR DISBURSEMENT make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" class=""> <!-- <?php  if($cancelled==1){ echo 'cancel'; }?> add class cancel -->
		    	<table class="table-borsdered" width="100%" style="border:1px solid #000">
		    		<tr>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    			<td width="5%"><br></td>
		    		</tr>		    		
		    		<tr>
		    			<td colspan="20">
		    				<center>
			    				<h4 class="company-st company-h-st" >
				    				<img class="logo-st" width="120px" src="<?php echo base_url().LOGO;?>">
				    				<b><?php echo COMPANY_NAME;?></b>
				    			</h4>
			    			</center>
		    			</td>
		    		</tr>
		    		<tr><td colspan="20" align="center"><h5><b>REQUEST FOR DISBURSEMENT</b></h5></td></tr>
		    		<!-- <tr><td class="f13" colspan="20" align="center"><br></td></tr> -->
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Company:</b></td>
		    			<td colspan="9" class="bor-btm">
		    				<input type="text" style="width:100%" name="company" autocomplete="off"></td>
		    			<td colspan="3" align="right"><b class="nomarg">APV No.:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<input type="text" style="width:100%" name="apv_no" autocomplete="off"></td>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Pay To:</b></td>
		    			<td colspan="9" class="bor-btm"><b class="nomarg"><?php echo $vendor; ?></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Date:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<input type="date" style="width:100%" name="rfd_date" ></td>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Check Name:</b></td>
		    			<td colspan="9" class="bor-btm">
		    					<input type="text" style="width:100%" name="check_name" value="" autocomplete="off"></td>
	    				</td>
		    			<td colspan="3" align="right"><b class="nomarg">Due Date:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<input type="date" style="width:100%" name="due_date" ></td>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td class="bor-btm" align="center">
		    					<input type="radio"  name="cash" value='1'></td>
		    			</td>
		    			<td><b class="nomarg">Cash</b></td>
		    			<td class="bor-btm" align="center">
		    					<input type="radio" name="cash" value='2'></td>
		    			<td><b class="nomarg">Check</b></td>
		    			<td></td>
		    			<td colspan="2"><b class="nomarg">Bank / no.</b></td>
		    			<td colspan="4" class="bor-btm">
		    					<input type="text" style="width:100%" name="bank_no" autocomplete="off"></td>
		    			<td colspan="3" align="right"><b class="nomarg">Check Due:</b></td>
		    			<td colspan="5" class="bor-btm">
		    					<input type="date" style="width:100%" name="check_due" ></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="all-border" align="center" colspan="17"><b class="nomarg">Explanation</b></td>
		    			<td class="all-border" align="center" colspan="3"><b class="nomarg">Remarks</b></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right"><br></td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right"><b class="nomarg">Payment for:</b></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
					<tr>
                        <td align="left" colspan="17" class="bor-right"><b><?php echo $general_desc; ?></b></td>
                        <td align="right" colspan="3"></td>
                    </tr>
                    <!--ITEMS-->
    				<?php
			    		$subtotal=array();
			    		//$materials_subtotal=array();
			    		if(!empty($items)){
			    		foreach($items AS $i){ 
			    			$subtotal[] = $i['total'];
			    			//$materials_subtotal[] = $i['materials_amount'];
		    		?>
		    		<tr>
		    			<td align="left" colspan="12" ><?php echo " - ".nl2br($i['offer'])."<br><br>"; ?></td>
		    			<td align="right" colspan="1"><?php echo $i['quantity']; ?></td>
		    			<td align="right" colspan="2"><?php echo $i['uom']; ?></td>
		    			<td align="right" colspan="2" class="bor-right"><?php echo number_format($i['price'],2); ?></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><?php echo number_format($i['total'],2); ?></span>
		    			</td>
		    		</tr>
		    		<?php } } else { $subtotal=array(); } ?>
		    		<!--ITEMS-->
		    		<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    		<tr>
						<td align="left" class="bor-right" colspan="17"><b>Materials:</b></td>
					</tr>
					<?php } ?>
		    		<!--MATERIALS-->
    				<?php
			    		//$subtotal=array();
			    		$materials_subtotal=array();
			    		if(!empty($items)){
			    		foreach($items AS $i){ 
			    			//$subtotal[] = $i['total'];
			    			$materials_subtotal[] = $i['materials_amount'];
			    			if($i['materials_offer']!='' && $i['materials_qty']!=0){
		    		?>
		    		<tr>
		    			<td align="left" colspan="12" ><?php echo " - ".nl2br($i['materials_offer'])."<br><br>"; ?></td>
		    			<td align="right" colspan="1"><?php echo $i['materials_qty']; ?></td>
		    			<td align="right" colspan="2"><?php echo $i['materials_unit']; ?></td>
		    			<td align="right" colspan="2" class="bor-right"><?php echo number_format($i['materials_unitprice'],2); ?></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><?php echo number_format($i['materials_amount'],2); ?></span>
		    			</td>
		    		</tr>
		    		<?php } } } else { $subtotal=array(); $materials_subtotal=array(); } ?>
		    		<!--MATERIALS-->
		    		<tr>
		    			<td align="left" colspan="7" ><?php echo $cenpri_jo_no."/".$joi_no."-".COMPANY; ?></td>
		    			<td align="right" colspan="10" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="7" ></td>
		    			<td align="right" colspan="10" class="bor-right"><br><br><br></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<?php 

		    			//$nettotal = (array_sum($subtotal) + array_sum($materials_subtotal) + $shipping+$packing+$vatt) - $discount;
		    			
		    			$percent=$vat_percent/100;
	    				$total=array_sum($subtotal) + array_sum($materials_subtotal);
						$sumvat=($total*$percent); 
		    			/*$nettotal = ($total+$shipping+$packing+$sumvat);
		    			$stotal = (array_sum($subtotal) + $shipping+$packing+$sumvat);*/
		    			$nettotal = ($total+$shipping+$packing);
		    			$stotal = (array_sum($subtotal) + $shipping+$packing);
		    			$mattotal = array_sum($materials_subtotal)+ $shipping+$packing;
		    			//$mattotal = (array_sum($materials_subtotal) + $shipping+$packing+$vatt);
		    		?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Labor SubTotal:</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format(array_sum($subtotal),2); ?></span>
		    			</td>
		    		</tr>
		    		<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Materials SubTotal:</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format(array_sum($materials_subtotal),2); ?></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		</tr>
		    		<!-- <tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo "Less Discount"; ?></b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format($discount,2); ?></span>
		    			</td>
		    		</tr> -->
		    		</tr>
		    			<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo "Net: "; ?></b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><?php echo number_format($nettotal,2); ?></span>
		    			</td>
		    		</tr>
		    		<?php 
		    		$percent=$ewt/100;
		    		$materials_percent=1/100;
		    		if($vat==1){
		    			$less= ($stotal/1.12)*$percent;
		    			$materials_less= ($mattotal/1.12)*$materials_percent;
		    			$gtotal = $stotal-$less;
		    			$mtotal = $mattotal-$materials_less;
		    			$totalamt=($gtotal + $mtotal + $sumvat) - $discount;
		    		} else {
		    			$less= $stotal*$percent;
		    			$materials_less= $mattotal*$materials_percent;
		    			$gtotal = $stotal-$less;
		    			$mtotal = $mattotal-$materials_less;
		    			$totalamt=($gtotal + $mtotal + $sumvat) - $discount;
		    		} ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo number_format($ewt); ?>% Labor EWT</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format($less,2); ?></span>
		    			</td>
		    		</tr>
		    		<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo '1'; ?>% Materials EWT</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format($materials_less,2); ?></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Balance Labor Amount Due</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($gtotal,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Balance Materials Amount Due</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($mtotal,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		<?php if($vatt!=0){ ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo $vat_percent."% VAT"; ?></b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format($sumvat,2); ?></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo "Less Discount"; ?></b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><?php echo number_format($discount,2); ?></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Total Amount Due</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<b style="font-weight: 900"><span class="nomarg"><?php echo number_format($totalamt,2); ?></span></b>
		    			</td>
		    		</tr>
		    		<?php 
		    		foreach($payment AS $p){  ?>
		    			<?php if($rows_rfd!=0 && $p->payment_amount !=0){ ?>
		    		<tr>
			    		<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo $p->payment_desc; ?></b></td>
			    		<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($p->payment_amount,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php } } ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><input type="text" name="payment_desc" value="" style="text-align: right;" placeholder="Payment Description"></b></td>
		    			<td align="right" colspan="3">
		    				<span class="nomarg" id=''><b style="font-weight: 900"><input type="text" onblur="check_rfd()" onchange='changePrice_rfd();' name="payment_amount" id="payment_amount" placeholder="Payment Amount"></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Balance After Payment</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<b style="font-weight: 900"><span class="nomarg" id='balaft'></span></b>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="7" ><b class="nomarg">Notes: </b>
		    				<textarea class="form-control bor-btm"  name = "notes"></textarea>
		    			</td>
		    			<td align="right" colspan="10" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"></b></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right bor-btm"><br></td>
		    			<td align="center" colspan="3" class="bor-btm"><br></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Prepared by:</b></td>
		    			<td colspan="3"><b>Checked by:</b></td>
		    			<td colspan="3"><b>Noted by:</b></td>
		    			<td colspan="3"><b>Endorsed by:</b></td>
		    			<td colspan="3"><b>Approved by:</b></td>
		    			<td colspan="5"><b>Payment Received by:</b></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg"><?php echo $_SESSION['fullname']; ?></b></td>
		    			<td colspan="3">
		    			<b>
					    			<select name='checked' class="select-des emphasis" required style="width:90%">
					    			<option value=''>-Select Employee-</option>
					    			<?php foreach($employee AS $emp){ ?>
					    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
					    			<?php } ?>
				    				</select>
					    	</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<select name='noted' class="select-des emphasis"  style="width:90%">
		    				
			    			<option value='' selected>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
			    		</select>
		    			</b>
		    			</td>
		    			<td colspan="3">
			    			<b>
				    			<select name='endorsed' class="select-des emphasis" required style="width:90%">
					    			<option value=''>-Select Employee-</option>
					    			<?php foreach($employee AS $emp){ ?>
					    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
					    			<?php } ?>
				    			</select>
			    			</b>
		    			</td>
		    			<td colspan="3">
			    			<b>
					    			<select name='approved' class="select-des emphasis" required style="width:90%">
					    			<option value=''>-Select Employee-</option>
					    			<?php foreach($employee AS $emp){ ?>
					    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
					    			<?php } ?>
				    			</select>
			    			</b>
		    			</td>
		    			<td colspan="5">
		    			<b>
		    			<select name='received' class="select-des emphasis"  style="width:90%">
		    				
			    			<option value='' selected>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
			    		</select>
		    			</b>
		    			</td>
		    		</tr>	    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='rows_rfd' id = "rows_rfd" value='<?php echo $rows_rfd; ?>'>
	    	<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
	    	<input type='hidden' name='pay_to' value='<?php echo $vendor_id; ?>'>
	    	<input type='hidden' name='total_amount' id = "total_amount" value='<?php echo $gtotal; ?>'>
	    	<input type='hidden' name='mtotal_amount' id = "mtotal_amount" value='<?php echo $mtotal; ?>'>
	    	<input type='hidden' name='sum_amount' id = "sum_amount" value='<?php echo $sum_amount; ?>'>
	    	<input type='hidden' name='discount_deduct' id = "discount_deduct" value='<?php echo $discount; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>