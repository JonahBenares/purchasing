  	<?php $CI =& get_instance(); ?>
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
	    <script src="<?php echo base_url(); ?>assets/js/rfdis.js"></script> 
	</head>

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
			#hide,#hidde{
				display: none;
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
    

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add 
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</h5>					
				</div>
				<div class="modal-body">
				<form method='POST' action="<?php echo base_url(); ?>rfdis/add_rfd_purpose">
				<div class="form-group">
					<h5 class="nomarg">Notes:</h5>
					<h5 class="nomarg"><b>
						<input type='text' name='notes' class="form-control">
					</b></h5>
				</div>
				<div class="form-group">
					<h5 class="nomarg">Requestor:</h5>
					<h5 class="nomarg"><b>
						<select name='requested_by' class="form-control">
                            <option value='' selected>-Select Employee-</option>
                            <?php foreach($employee AS $emp){ ?>
                            <option value="<?php echo $emp->employee_id; ?>">
                            	<?php echo $emp->employee_name; ?>
                            </option>
                            <?php }  ?> 
                        </select>
					</b></h5>
				</div>
				<div class="form-group">
					<h5 class="nomarg">Purpose:</h5>
					<h5 class="nomarg"><b>
						<input type="text" name='purpose' class="form-control">
					</b></h5>
				</div>

				<div class="form-group">
					<h5 class="nomarg">Enduse:</h5>
					<h5 class="nomarg"><b>
						 <input type="text" name='enduse' class="form-control">
					</b></h5>
				</div>
				
				</div>
				<div class="modal-footer">
					<input type='hidden' name='rfd_id' value='<?php echo $rfd_id; ?>'>
					<input type="submit" class="btn btn-primary btn-block" value="Save changes">
				</div>
			</form>
			</div>
		</div>
	</div>
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>rfdis/save_rfdis'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="" onclick = "javascript:window.open('', '_parent', '').close();" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==0) { ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">	
						<?php } else { ?> 
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>rfdis/rfdis_dr/<?php echo $rfd_id; ?>" class="btn btn-warning btn-md p-l-50 p-r-50" target="_blank"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>		
						<?php } ?>				
					</div>
					<p class="text-white">Instructions: When printing DELIVERY RECEIPT make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" >    
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
		    		<!-- <tr><td class="f13" colspan="20" align="center"><br></td></tr> -->
		    		<?php foreach($rfd as $r){ ?>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Company:</b></td>
		    			<td colspan="9" class="bor-btm">
		    				<b class='nomarg'><?php echo $r->company; ?></b>
		    			</td>
		    			<td colspan="3" align="right"><b class="nomarg">APV No.:</b></td>
		    			<td colspan="5" class="bor-btm"><b class='nomarg'><?php echo $r->apv_no; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Pay To:</b></td>
		    			<td colspan="9" class="bor-btm"><b class="nomarg"><?php echo $CI->getname("vendor_name", "vendor_head", "vendor_id", $r->pay_to); ?></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Date:</b></td>
		    			<td colspan="5" class="bor-btm"><b class='nomarg'><?php echo date('F j, Y', strtotime($r->rfd_date)); ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Check Name:</b></td>
		    			<td colspan="9" class="bor-btm"><b class='nomarg'><?php echo $r->check_name; ?></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Due Date:</b></td>
		    			<td colspan="5" class="bor-btm"><b class='nomarg'><?php echo date('F j, Y', strtotime($r->due_date)); ?></b></td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td class="bor-btm" align="center"><?php echo (($r->cash_check == 1) ? "<span class='fa fa-check'></span>" : ""); ?></td>
		    			<td><b class="nomarg">Cash</b></td>
		    			<td class="bor-btm" align="center"><?php echo (($r->cash_check == 2) ? "<span class='fa fa-check'></span>" : ""); ?></td>
		    			<td><b class="nomarg">Check</b></td>
		    			<td></td>
		    			<td colspan="2"><b class="nomarg">Bank / no.</b></td>
		    			<td colspan="4" class="bor-btm"><b class='nomarg'><?php echo $r->bank_no; ?></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Check Due:</b></td>
		    			<td colspan="5" class="bor-btm"><b class='nomarg'><?php echo date('F j, Y', strtotime($r->check_due)); ?></b></td>
		    		</tr>
		    		<?php } ?>
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
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg">Payment for: 
		    				<?php if($saved==0){ ?>
		    				<a class="btn btn-xs btn-primary" id="hidde" onclick="additemrfd('<?php echo base_url(); ?>','<?php echo $rfd_id; ?>','<?php echo $supplier_id; ?>')" >Add Item/s</a>
		    				</b>
		    				<?php } ?>
		    			</td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<?php 
		    			if(!empty($items)){
		    				foreach($items AS $it){ 
		    					$total = $it['quantity'] * $it['price'];
		    					$gross[]=$total;
		    		?>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">		    				
		    				<div style="padding-left: 10px">
		    					<?php if($saved==0) { ?>
		    					<a href="<?php echo base_url(); ?>rfdis/delete_item/<?php echo $it['rfd_items_id']; ?>/<?php echo $rfd_id ?>" onclick="return confirm('Are you sure you want to delete item?')" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
		    					<?php } ?>		    					
		    					<b class="nomarg"><?php echo number_format($it['quantity']) .", ". $it['item'] . ", " . $it['specs']. ", @Php ". number_format($it['price'],2) . " per " . $it['unit']; ?></b></div>
		    			</td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">₱</span>
		    				<span class="nomarg" id=''><b><?php echo number_format($total,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php 
		    			}
		    			if($vat==1){
		    				$less_amount = array_sum($gross) / 1.12 * ($ewt/100);
		    			}else {
		    				$less_amount = array_sum($gross) * ($ewt/100);
		    			}
		    			$net = array_sum($gross) - $less_amount;
		    		?>
					<input type='hidden' name='gross' value='<?php echo array_sum($gross); ?>'>
					<input type='hidden' name='less_amount' value='<?php echo $less_amount; ?>'>
					<input type='hidden' name='net' value='<?php echo $net; ?>'>
		    		<tr>

		    			<td align="right" colspan="17" class="bor-right"><b class="nomarg">Less: <?php echo number_format($ewt); ?>% EWT<br>
		    				<?php echo (($vat==1) ? 'Vatable' : 'Non-vatable'); ?></b></td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><b><?php echo number_format($less_amount,2); ?></b></span>
		    			</td>
		    		</tr>
		    		<?php } ?>
		    		<tr id="hide">

		    			<td align="left" colspan="12" >
		    				<b class="nomarg">
		    					<?php if($saved==0){ ?>
		    					<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-xs btn-primary" onclick="" >Add Purpose/ EndUse/ Requestor</button>
		    					<?php } ?>
		    				</b>
		    			</td>
		    			<td colspan="5" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<?php 
		    			if(!empty($rfdpurp)){
		    				foreach($rfdpurp AS $pp) { 
		    		?>		    
		    		<tr>
		    			<td align="left" colspan="12" >
		    				<div style="padding-left:10px"><b class="nomarg"><?php echo $pp['notes']; ?></b></div>
		    			</td>
		    			<td colspan="5" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="12" >
		    				<div style="padding-left:10px">Purpose: <b class="nomarg"><?php echo $pp['purpose']; ?></b></div>
		    			</td>
		    			<td colspan="5" class="bor-right">
		    				<?php if($saved==0){ ?>
		    				<a href="<?php echo base_url(); ?>rfdis/delete_purpose/<?php echo $pp['rfd_purpose_id']; ?>/<?php echo $rfd_id ?>" onclick="return confirm('Are you sure you want to delete purpose?')" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
		    				<?php } ?>
		    			</td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="12" >
		    				<div style="padding-left:10px">End Use: <b class="nomarg"><?php echo $pp['enduse']; ?></b></div>
		    			</td>
		    			<td colspan="5" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="12" >
		    				<div style="padding-left:10px">Requestor: <b class="nomarg"><?php echo $pp['requestor']; ?></b></div>
		    			</td>
		    			<td colspan="5" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="12"><br></td>
		    			<td align="center" colspan="5" class="bor-right"><br></td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>
		    		<?php } } ?>
		    		<tr>
		    			<td align="left" colspan="7" ><b class="nomarg"></b></td>
		    			<td align="right" colspan="10" class="bor-right"><b class="nomarg" style="font-weight: 900">Total Amount Due</b></td>
		    			<td align="right" colspan="3" style="border-bottom: 2px solid #000">
		    				<span class="pull-left nomarg">₱</span>
		    				<?php if(!empty($items)){ ?>
		    				<span class="nomarg" id=''><b style="font-weight: 900"><?php echo number_format($net,2); ?></b></span>
		    				<?php } ?>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right bor-btm"><br></td>
		    			<td align="center" colspan="3" class="bor-btm"><br></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="5"><b class="nomarg">Prepared by:</b></td>
		    			<td colspan="5"><b>Checked by:</b></td>
		    			<td colspan="5"><b>Endorsed by:</b></td>
		    			<td colspan="5"><b>Approved by:</b></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td colspan="5"><b class="nomarg"><?php echo $_SESSION['fullname']; ?></b></td>
		    			<td colspan="5">
		    			<b>
		    			<?php if($saved==0){ ?>
		    			<select name='checked' class="select-des emphasis">
			    			<option value=''>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else { echo $checked; } ?>
		    			</b>
		    			</td>
		    			<td colspan="5">
		    			<b>
		    			<?php if($saved==0){ ?>
		    			<select name='endorsed' class="select-des emphasis">
			    			<option value=''>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else { echo $endorsed; } ?>
		    			</b>
		    			</td>
		    			<td colspan="5">
		    			<b>
		    			<?php if($saved==0){ ?>
		    			<select name='approved' class="select-des emphasis">
			    			<option value=''>-Select Employee-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    		<?php } else { echo $approved; } ?>
		    			</b>
		    			</td>
		    		</tr>	    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='rfd_id' value='<?php echo $rfd_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>