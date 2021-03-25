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
    	<form method='POST' action='<?php echo base_url(); ?>pod/save_rfd_calapan'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a> -->
					
						<?php if($rows_dr!=0){ ?>
							<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<?php } else { ?>
							<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">	
						<?php } ?>	
					
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" class=""> <!-- add class cancel -->
	    		<table width="100%">
	    			<tr>
	    				<td width="25%"><?php echo date("m/d/Y") ?></td>
	    				<td width="50%"><center>Procurement System Generated</center></td>
	    				<td width="25%"></td>
	    			</tr>
	    		</table>
		    	<table class="table-borddered" width="100%" style="border:1px solid #000">
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
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Sender:</b></td>
		    			<td colspan="9" class="bor-btm">
		    				<?php if($rows_dr==0){ ?>
			    				<input type="text" style="width:100%" name="company" autocomplete="off">
			    			<?php } else {
			    				echo $company;
			    			} ?>
		    			</td>
		    			<td colspan="3" align="right"><b class="nomarg">RFCD No.:</b></td>
		    			<td colspan="5" class="bor-btm">
		    					<?php if($rows_dr==0){ ?>
		    						<input type="text" style="width:100%" name="apv_no" autocomplete="off">
			    				<?php } else {
			    					echo $apv_no;
			    				} ?>
		    			</td>
		    		</tr>
		    		<tr>

		    			<td colspan="3"><b class="nomarg">Pay To:</b></td>
		    			<td colspan="9" class="bor-btm"><b class="nomarg"><?php echo (!empty($tin)) ? $vendor." / ".$tin : $vendor; ?></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Date Requested:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<?php if($rows_dr==0){ ?>
		    					<input type="date" style="width:100%" name="rfd_date" value="<?php echo (!empty($rfd_date)) ? $rfd_date : ''; ?>">
		    				<?php } else {
		    					echo $rfd_date;
		    				} ?>
		    			</td>
		    		</tr>		    		
		    		<tr>
		    			<td></td>
		    			<td class="bor-btm" align="center">
		    				<?php if($rows_dr==0){ ?>
		    					<input type="radio"  name="cash" value='1'>
		    				<?php } else {
		    					echo (($cash == 1) ? "<span class='fa fa-check'></span>" : "");
		    				} ?>
		    			</td>
		    			<td><b class="nomarg">Cash</b></td>
		    			<td class="bor-btm" align="center">
	    					<?php if($rows_dr==0){ ?>
	    						<input type="radio" name="cash" value='2'>
	    					<?php } else {
	    						echo (($cash == 2) ? "<span class='fa fa-check'></span>" : "");
	    					} ?>
	    				</td>
		    			<td><b class="nomarg">Check</b></td>
		    			<td></td>
		    			<td colspan="2"><b class="nomarg">Bank / no.</b></td>
		    			<td colspan="4" class="bor-btm">
	    					<?php if($rows_dr==0){ ?>
		    					<input type="text" style="width:100%" name="bank_no" autocomplete="off">
		    				<?php } else {
		    					echo $bank_no;
		    				} ?>
	    				</td>
		    			<td colspan="3" align="right"><b class="nomarg">Due Date:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<?php if($rows_dr==0){ ?>
		    					<input type="date" style="width:100%" name="due_date" value="<?php echo (!empty($due_date)) ? $due_date : ''; ?>">
		    				<?php } else {
		    					echo $due_date;
		    				} ?>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="all-border" align="left" colspan="17"><b class="nomarg">Explanation</b></td>
		    			<td class="all-border" align="center" colspan="3"><b class="nomarg">Amount</b></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right"><br></td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right"><b class="nomarg">Payment for:</b></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<?php foreach($items AS $it){ 
		    			$subtotal[] = $it['total']; ?>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg"><?php echo number_format($it['quantity'],2) ." ".$it['uom'] ." " . $it['offer'] . ", " . "  @ ". $it['price'] ." per ".  $it['uom']; ?></b>
		    			</td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($it['total'],2); ?></b></span>
		    			</td>
		    		</tr>	
			    	<?php } ?>
			    	<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Shipping Cost</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($shipping,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Packing and Handling Fee</b></td>
		    			<td align="right" colspan="3" >
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($packing,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php if($vat_percent!=0){ ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo $vat_percent; ?>% VAT</b></td>
		    			<td align="right" colspan="3" >
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($vatt,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Less: Discount</b></td>
		    			<td align="right" colspan="3" >
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($discount,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php
		    				$stotal = (array_sum($subtotal) + $shipping+$packing+$vatt) - $discount;
		    		?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Subtotal</b></td>
		    			<td align="right" colspan="3" class=" bor-top">
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($stotal,4); ?></b></span>
		    			</td>
		    		</tr>

		    		<?php 
		    		$percent=$ewt/100;
		    		if($vat==1){
		    			$less= ($stotal/1.12)*$percent;
		    			$gtotal =$stotal-$less;
		    		} else {
		    			$less= $stotal*$percent;
		    			$gtotal = $stotal-$less;
		    		} ?>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Less: <?php echo number_format($ewt); ?>% EWT</b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($less,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg"><?php echo (($vat==1) ? 'Vatable' : 'Non-Vatable'); ?></b></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<?php foreach($pr AS $p){ ?>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg">Purpose: <?php echo $p['purpose']; ?></b>
		    			</td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg">End Use:  <?php echo $p['enduse']; ?></b>
		    			</td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg">Requestor:  <?php echo $p['requestor'] .(($p['pr_id']!=0) ? "; Item# ". $p['item_no'] : "") ?></b>
		    			</td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right"><br></td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>
		    		<?php } ?>		    		
		    		<tr>
		    			<td align="left" colspan="7" ><b class="nomarg">P.O. No: <?php echo $po_no."-".COMPANY; ?></b></td>
		    			<td align="right" colspan="10" class="bor-right"><b class="nomarg" style="font-weight: 900">Total Amount Due</b></td>
		    			<td align="right" colspan="3" style="border-bottom: 2px solid #000">
		    				<span class="pull-left nomarg"><?php echo $currency; ?></span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($gtotal,4); ?></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="7" ><b class="nomarg">DR No: <?php echo $dr_no."-".COMPANY; ?></b></td>
		    			<td align="right" colspan="10" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right"><b class="nomarg">Notes: </b>
		    				<?php if($rows_dr==0){ ?>
		    				<textarea class="form-control"  name = "notes" rows="1" style="width: 100%"></textarea>
		    				<?php }else { echo $notes; }?>
		    			</td>
		    			<td align="right" colspan="3" ></td>
		    		</tr>	    		
		    		<tr>
		    			<td align="left" colspan="7" ></b></td>
		    			<td align="right" colspan="10" class="bor-right"><b class="nomarg" style="font-weight: 900"></b></td>
		    			<td align="right" colspan="3" >
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right bor-btm"></td>
		    			<td align="center" colspan="3" class="bor-btm"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Prepared by:</b></td>
		    			<td colspan="3"><b>Checked by:</b></td>
		    			<td colspan="3"><b>Noted by:</b></td>
		    			<td colspan="3"><b>Approved by:</b></td>
		    			<td colspan="3"><b>Request Initiated	 by:</b></td>
		    			<td colspan="5"><b>Payment Received by:</b></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td colspan="3"><b class="nomarg"><?php echo $_SESSION['fullname']; ?></b></td>
		    			<td colspan="3">
		    			<b>
		    			<?php if($rows_dr==0){ ?>
		    			<select name='checked' class="select-des emphasis"  style="width:90%">
		    				
			    			<option value='' selected>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
			    		</select>
			    		<?php 	
		    			} else { 
		    				echo $checked; 
		    			} ?>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<?php if($rows_dr==0){ ?>
		    			<select name='noted' class="select-des emphasis"  style="width:90%">
			    			<option value='' selected>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
			    		</select>
			    		<?php 	
		    			} else { 
		    				echo $noted; 
		    			} ?>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<?php if($rows_dr==0){ ?>
		    			<select name='endorsed' class="select-des emphasis"  style="width:90%">
			    			<option value=''>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php 	
		    			} else { 
		    				echo $endorsed; 
		    			} ?>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<?php if($rows_dr==0){ ?>
		    			<select name='approved' class="select-des emphasis" required style="width:90%">
			    			<option value=''>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
	    				<?php 	
		    			} else { 
		    				echo $approved; 
		    			} ?>
		    			</b>
		    			</td>
		    			<td colspan="5">
		    			<b>
		    			<?php if($rows_dr==0){ ?>
		    			<select name='received' class="select-des emphasis"  style="width:90%">
			    			<option value='' selected>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
			    		</select>
			    		<?php 	
		    			} else { 
		    				echo $received; 
		    			} ?>
		    			</b>
		    			</td>
		    		</tr>	    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='po_type' value='<?php echo $po_type; ?>'>
	    	<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
	    	<input type='hidden' name='pay_to' value='<?php echo $vendor_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>