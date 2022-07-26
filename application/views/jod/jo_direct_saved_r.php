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

  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .pad{
        	padding:0px 250px 0px 250px
        }
        @media print{
        	.pad{
        	padding:0px 0px 0px 0px
        	}
        }
        .cancel{
        	background-image: url('../../assets/img/cancel.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
        }
        .table-bordered>tbody>tr>td, 
        .table-bordered>tbody>tr>th, 
        .table-bordered>tfoot>tr>td, 
        .table-bordered>tfoot>tr>th, 
        .table-bordered>thead>tr>td, 
        .table-bordered>thead>tr>th
        {
		    border: 1px solid #000!important;
		}
		.f13{
			font-size:13px!important;
		}
		.bor-btm{
			border-bottom: 1px solid #000;
		}
		.sel-des{
			border: 0px!important;
		}
		@media print{
			#prnt_btn, #printnotes{
				display: none;
			}
			.emphasis{
				border: 0px solid #fff!important;
			}
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
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
		.nobord{
			border: 0px solid #fff;
		}
		.p-5{
			padding: 3px;
		}
    </style>
    <div class="modal fade" id="addscope" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Scope
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</h5>										
				</div>
				<form method='POST' action='<?php echo base_url(); ?>jod/create_jod_details'>
					<div class="modal-body">
						<div class="form-group">
							<p style="font-size: 14px" class="nomarg">Scope:</p>
							<textarea class="form-control" rows="3" name="scope"></textarea>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">Qty:</p>
								<input type="text" class="form-control" name="quantity">
							</div>
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">U/M:</p>
								<input type="text" class="form-control" name="uom">
							</div>
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">Unit Cost:</p>
								<input type="text" class="form-control" name="unit_cost">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary btn-block" value="Add">
					</div>
					<input type="hidden" name='jo_id' value="">
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addterms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Terms & Conditions
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</h5>										
				</div>
				<form method='POST' action='<?php echo base_url(); ?>jod/create_jod_terms_saved'>
					<div class="modal-body">
						<div class="form-group">
							<p style="font-size: 14px" class="nomarg">Terms & Conditions:</p>
							<textarea class="form-control" name='terms' rows="3"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary btn-block" value="Add">
					</div>
					<input type="hidden" name='joi_id' value="<?php echo $joi_id; ?>">
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="Edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Update AOQ Terms
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>jod/update_terms_saved">
						<div class="modal-body">
							<div class="form-group">
								Payment:
								<input type="text" class="form-control" name="payments" autocomplete="off" value = "<?php echo $payment_terms;?>">
								Item Warranty:
								<input type="text" class="form-control" name="item_war" autocomplete="off" value = "<?php echo $item_warranty;?>">
								Delivery_item:
								<input type="text" class="form-control" name="del_itm" autocomplete="off" value = "<?php echo $delivery_time;?>">
								Freight:
								<input type="text" class="form-control" name="freigh" autocomplete="off" value = "<?php echo $freight;?>">
							</div>
						</div>
						<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
						<input type='hidden' name='aoq_vendors_id' value='<?php echo $aoq_vendors_id; ?>'>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>
		<div class="modal fade" id="UpdateTerms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Update Terms & Condition
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>jod/update_condition_saved">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="condition" autocomplete="off" id = "terms">
							</div>
						</div>
						<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
						<input type='hidden' name='tc_id' id = "tc_id">
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>joi/joi_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($draft!=1){ 
						 		if($revised==0){ ?>
							<a  href='<?php echo base_url(); ?>jod/jo_direct_rev/<?php echo $joi_id; ?>' onclick="return confirm('Are you sure you want to revise JO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>JOI</b></u></a>
						<?php } } ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<?php if($draft!=1){ 
								if($revised==0){ ?>
						<a  href="<?php echo base_url(); ?>jod/jod_rfd/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<?php } }?>
						<a  href="<?php echo base_url(); ?>jod/jod_dr/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jod/jod_ac/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AR</b></a>
						<a  href="<?php echo base_url(); ?>jod/jod_coc/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>COC</b></a>
						
					</div>
					<h4 class="text-white"><b>JOB ORDER ISSUANCE</b></h4>
					<p class="text-white">Instructions: When printing JOB ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" class="<?php  if($cancelled==1){ echo 'cancel'; }?>">
	    		<table width="100%">
	    			<tr>
	    				<td width="25%"><?php echo date("m/d/Y") ?></td>
	    				<td width="50%"><center>Procurement System Generated</center></td>
	    				<td width="25%"></td>
	    			</tr>
	    		</table>   		  			
		    	<table class="table-borsdered" width="100%" style="border:2px solid #000">
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
			    				<h4 class="company-st" >
				    				<img class="logo-st" width="120px" src="<?php echo base_url().LOGO;?>">
				    				<b><?php echo COMPANY_NAME;?></b>
				    			</h4>
			    				<div  class="det-st">
			    					<?php echo ADDRESS;?><br>
			    					<?php echo ADDRESS_2;?><br>
			    					<?php echo TIN;?><br>
			    					<?php echo TEL_NO;?><br>
			    					<?php echo TELFAX;?><br>
			    				</div>
			    			</center>
		    			</td>
		    		</tr>
		    		<tr><td colspan="20" align="center"><h4><b>JOB ORDER</b></h4><small class="text-red">REVISED</small></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td class="f13" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<b><?php echo $h['vendor'];?></b><br>
		    				<span id='contact_person'><?php echo $h['contact'];?></span><br>
		    				<span id='address'><?php echo $h['address'];?></span><br>
		    				<span id='phone'><?php echo $h['phone'];?></span><br>
		    				<span id='fax'><?php echo $h['fax'];?></span><br>
		    				<br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		
		    		<tr>
		    			<td class="f13" colspan="4">Date Needed:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['date_needed']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="5"><?php echo $h['completion_date']; ?></td>
		    		</tr>

		    		<tr>
		    			<td class="f13" colspan="4">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['date_prepared']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JO No.:</td>
		    			<td class="f13 bor-btm" colspan="5"><b><?php echo $h['cenpri_jo_no']; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="4">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['start_of_work']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO. No:</td>
		    			<td class="f13 bor-btm" colspan="5"><?php echo $h['joi_no']."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : ""); ?></td>
		    		</tr>	
		    		<!-- <tr>
		    			<td class="f13" colspan="4">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date("F d, Y",strtotime($work_completion));?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13" colspan="5"></td>
		    		</tr> -->			    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px"><b><?php echo $h['project_title'];?></b></h5>
			    		</td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<?php } ?>	    		
		    		<tr>
		    		
		    			<td colspan="20">
		    				<table class="table-borsdered" width="100%">
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Scope of Work:</b></td>
		    						<td width="10%" class="f13" align="center"><b>Qty</b></td>
		    						<td width="5%" class="f13" align="center"><b>UM</b></td>
		    						<td width="5%" class="f13" align="center"><b>Currency</b></td>
		    						<td width="15%" class="f13" align="center"><b>Unit Cost</b></td>
		    						<td width="15%" class="f13" align="center"><b>Total Cost</b></td>
		    					</tr>
		    					<tr>
                                    <td class="f13 p-l-5" align="left"><b><?php echo $h['general_desc']; ?></b></td>
                                </tr>
		    					<?php
					    		$gtotal=array();
					    		if(!empty($items)){
					    		foreach($items AS $it){ 
					    			$gtotal[] = $it->amount+ $it->materials_amount;
				    			?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><?php echo nl2br($it->offer)."<br><br>"; ?></td>
		    						<td class="f13" align="center"><?php echo number_format($it->delivered_quantity,2); ?></td>
		    						<td class="f13" align="center"><?php echo $it->uom; ?></td>
		    						<td class="f13" align="center"><?php echo $it->currency; ?></td>
		    						<td class="f13" align="center"><?php echo $it->unit_price; ?></td>
		    						<td class="f13" align="right"><?php echo $it->amount; ?></td>
		    					</tr>
		    					<?php } }else { $gtotal=array(); } ?>

		    					<?php
					    		$gtotal=array();
					    		if(!empty($items)){
					    		foreach($items AS $it){ 
					    			$gtotal[] = $it->amount+ $it->materials_amount;
				    			?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><?php echo nl2br($it->materials_offer)."<br><br>"; ?></td>
		    						<td class="f13" align="center"><?php echo number_format($it->materials_qty,2); ?></td>
		    						<td class="f13" align="center"><?php echo $it->materials_unit; ?></td>
		    						<td class="f13" align="center"><?php echo $it->materials_currency; ?></td>
		    						<td class="f13" align="center"><?php echo $it->materials_unitprice; ?></td>
		    						<td class="f13" align="right"><?php echo $it->materials_amount; ?></td>
		    					</tr>
		    					<?php } }else { $gtotal=array(); } ?>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<b>Notes:</b>		    						
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<?php 
		    						$y=1; 
		    						foreach($tc AS $n){ 
		    							if($n->notes!=''){
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<?php echo nl2br($n->notes)."<br><br>"; ?>
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<?php $y++; } } ?>
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    					</tr>
		    					<?php 
		    						$grtotal =array_sum($gtotal);
		    						$subtotal=$grtotal+$vat;
		    						$grandtotal = ($grtotal+$vat)-$discount;
		    					?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Amount:</td>
		    						<td align="right"><?php echo number_format(array_sum($gtotal),2); ?></td>
		    					</tr>
		    					<?php if($vat!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'><?php echo $vat_percent; ?>% VAT:</td>
		    						<td align="right"><?php echo number_format($vat,2); ?></td>
		    					</tr>
		    					<?php } ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Sub Total:</td>
		    						<td align="right"><?php echo number_format($subtotal,2);?></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Less Discount:</td>
		    						<td align="right"><?php echo number_format($discount,2);?></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Grand Total:</td>
		    						<td align="right"><?php echo number_format($grandtotal,2); ?></td>
		    					</tr>
		    				</table>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				1. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				2. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>		    				 
                            3. Price is <?php echo (($vat_in_ex == '0') ? 'inclusive of VAT' : 'exclusive of VAT');?><br>
		    				<?php if(!empty($payment_terms)){ ?>
		    				4. Payment term: <?php echo $payment_terms; ?><br>
		    				<?php } ?>	
		    				<?php if(!empty($item_warranty)){ ?>
		    				5. Item Warranty: <?php echo $item_warranty; ?><br>
		    				<?php } ?>
		    				<?php if(!empty($delivery_time)){ ?>
		    				6. Delivery Time: <?php echo $delivery_time; ?><br>
		    				<?php } ?>
		    				<?php if(!empty($freight)){ ?>
		    				7. In-land Freight: <?php echo $freight; ?><br>
		    				<?php } ?>
		    				<?php 
		    					//$no=8;
		    					if(!empty($payment_terms) || !empty($item_warranty) || !empty($delivery_time) || !empty($freight)){
		    						$no=8;
		    					}else {
		    						$no=4;
		    					}
		    					foreach($tc AS $t){ 
		    						if(!empty($t->tc_desc)){
			    						echo $no.". " . $t->tc_desc."<br>";
			    						$no++; 
			    					}
		    					} 
		    				?>
		    			</td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Total Project Cost:</td>
		    			<td class="f13 bor-btm" colspan="7" align="right"><h4 style="margin: 0px"><b><span id='gtotal'><?php echo number_format($grandtotal,2); ?></span></b></h4></td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Conforme:</td>
		    			<td class="f13 bor-btm" colspan="7" align="center"><?php echo $conforme; ?></td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13" colspan="7" align="center">Contractor's Signature Over Printed Name</td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>
		    		<tr><td class="f13 bor-btm" colspan="20" align="center"><br></td></tr>    	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>    	

		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center">Prepared by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">Checked by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center">Recommended by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="3" align="center">Approved by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><?php echo $prepared; ?><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 bor-btm" colspan="4" align="center"><?php echo $checked; ?><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><?php echo $recommended; ?><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="3" align="center"><?php echo $approved; ?><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="3" align="center">
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr> 
		    		<!-- <tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Purchasing Department</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="6" align="center"><small>Personnel</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Project Director</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   -->
		    		<tr><td class="f13" colspan="20" align="center"><br><br></td></tr>  
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>    	
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center">Work Completion Verified by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center"></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><?php echo $verified_by; ?><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"></td>
		    			<td class="f13" colspan="1" align="center"></td></td>

		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="3" align="center"></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center"></td>
		    		</tr>     	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    	</table>		    
	    	</div>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
		function quitBox(cmd)
		{   
		    if (cmd=='quit')
		    {
		    	self.opener.location.reload();
		        open(location, '_self').close();

		    }   
		    return false;   
		}
    </script>