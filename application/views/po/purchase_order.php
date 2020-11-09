<?php 
   	$CI =& get_instance();  
?>
  	<script src="<?php echo base_url(); ?>assets/js/po.js"></script> 
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
	    <script src="<?php echo base_url(); ?>assets/js/all-scripts.js"></script> 
	</head>
  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .pad{
        	padding:0px 250px 0px 250px
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
		.bor-right{
			border-right: 1px solid #000;
		}
		.sel-des{
			border: 0px!important;
		}
		@media print{
			.pad{
        	padding:0px 0px 0px 0px
        	}
			.prnt,#prnt_btn,#item-btn,#pr-btn,#updateTerm{
				display: none;
			}
			.emphasis{
				border: 0px solid #fff!important;
			}
			html, body{
            background: #fff!important;
            font-size:12px!important;
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
		.v-align{
			vertical-align: top;
		}
    </style>

	<div class="modal fade" id="add-pr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add PR
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</h5>					
				</div>
				<form method="POST" action="<?php echo base_url(); ?>po/add_pr">
					<?php
					if(!empty($head)){
					 foreach($head AS $h) { ?>
					
					<div class="modal-body">
						<div class="form-group">
							<h5 class="nomarg">PR NO:</h5>
							<select name='pr' id='pr' class="form-control" onchange='getPRInfo()'>
								<option value="" selected="">-Choose PR-</option>
								<?php foreach($pr AS $p){ ?>
								<option value="<?php echo $p['pr_id']; ?>"><?php echo $p['pr_no']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<h5 class="nomarg">Requestor:</h5>
							<h5 class="nomarg"><b><input type name='requestor' id='requestor' readonly="readonly" class='form-control'></b></h5>
						</div>
						<div class="form-group">
							<h5 class="nomarg">Purpose:</h5>
							<h5 class="nomarg"><b><input type name='purpose' id='purpose' readonly="readonly" class='form-control'></b></h5>
						</div>

						<div class="form-group">
							<h5 class="nomarg">Enduse:</h5>
							<h5 class="nomarg"><b><input type name='enduse' id='enduse' readonly="readonly" class='form-control'></span></b></h5>
						</div>
						
					</div>
					<div class="modal-footer">
					<input type="submit" class="btn btn-primary btn-block" value='Add'>
					</div>
					<?php } 
				} ?>
					<input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
					<input type='hidden' name='po_id' id='po_id' value="<?php echo $po_id; ?>">
					<input type='hidden' name='vendor_id' id='vendor_id' value="<?php echo $vendor_id; ?>">
				</form>
			</div>
		</div>
	</div>

	

    <div  class="pad">
    	<?php 
    	if($revised=='r'){
    		$url=base_url().'po/save_po_revised';
    	} else {
    		$url=base_url().'po/save_po';
    	}
    	?>
    	<form method='POST' action='<?php echo $url; ?>'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==1){ ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
					<?php } else {
						if($revised=='r'){ ?>
							<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save Revision">	
						<?php } else { ?>
							<input type='submit' class="btn btn-warning btn-md p-l-100 p-r-100" name='submit' value="Save as Draft">	
							<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" name='submit' value="Save">	

						<?php }
					 } ?>
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;">    	
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
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b></h4></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no']."-".COMPANY; ?></b></h6></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Supplier:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['vendor']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Address:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['address']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Contact Person:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['contact']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Telephone #:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['phone']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Telefax #:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['fax']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    		<?php } ?>
		    		<!-- <tr id="pr-btn">
		    			<td colspan="20" style="padding-left: 10px">

		    				<a class="addPR btn btn-primary btn-xs" data-toggle="modal" href="#add-pr" data-id="<?php echo $po_id; ?>">
							  Add PR
							</a>
		    			</td>
		    		</tr>	 -->
		    		<!-- LOOp Here --> 
					<tr>
		    			<td colspan="" class="all-border" align="center"><b>#</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Qty</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
		    			<td colspan="13" class="all-border" align="center"><b>Description</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>Unit Price</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>Total</b></td>
		    		</tr>
		    		<?php 
		    		$gtotal=array();
		    		$x=1;
		    		if(!empty($items)){
		    		foreach($items AS $it){
		    			if($it['balance']!=0){ 
		    			$gtotal[] = $it['total']; ?>
		    		<tr>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $x; ?></b></td>
		    			<td colspan="" class="bor-right v-align" align="center"><b><input type='text' name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' value='<?php echo $it['balance']; ?>' <?php echo (($revised!='r') ? "max=".$it['balance'] : ""); ?> style='width:50px; color:red' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"></b></td>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $it['uom']; ?></b></td>
		    			<td colspan="13" class="bor-right v-align" align="left"><b class="nomarg"><?php echo (!empty($CI->get_pn($it['pr_details_id']))) ? nl2br($it['offer']).", ".$CI->get_pn($it['pr_details_id']) : nl2br($it['offer']); ?></b></td>
		    			<td colspan="2" class="bor-right v-align" align="center"><b><input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' value='<?php echo $it['price']; ?>' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)" style='color:red; width:100px' ></b></td>
		    			<td colspan="2" class="bor-right v-align" align="right"><b class="nomarg"><input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="<?php echo $it['total']; ?>" style='text-align:right;' readonly></b></td>
		    		</tr>
		    		<input type='hidden' name='currency<?php echo $x; ?>' value="<?php echo $it['currency']; ?>">
		    		<input type='hidden' name='aoq_id<?php echo $x; ?>' value="<?php echo $it['aoq_id']; ?>">
		    		<input type='hidden' name='aoq_offer_id<?php echo $x; ?>' value="<?php echo $it['aoq_offer_id']; ?>">
		    		<input type='hidden' name='aoq_items_id<?php echo $x; ?>' value="<?php echo $it['aoq_items_id']; ?>">
		    		<input type='hidden' name='pr_details_id<?php echo $x; ?>' value="<?php echo $it['pr_details_id']; ?>">
		    		<textarea hidden  name='offer<?php echo $x; ?>'><?php echo $it['offer']; ?></textarea>
		    		<input type='hidden' name='uom<?php echo $x; ?>' value="<?php echo $it['uom']; ?>">
			    		<?php 
			    		$x++; 
		    				} 
		    			}
		    			$vat_amount=array_sum($gtotal)*0.12;
		    		} else {
		    			$gtotal=array();

		    		} ?>
		    		<input type='hidden' name='count_item' value="<?php echo $x; ?>">
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="13" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    		</tr>
					<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="13" class=" bor-right" align="right">Shipping Cost</td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><input type='text' name='shipping' id='shipping' value='0' onchange='additionalCost()' style='width:100%' ></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="13" class=" bor-right" align="right">Packing and Handling Fee</td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><input type='text' name='packing' id='packing' onchange='additionalCost()' value='0' style='width:100%' ></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="13" class=" bor-right" align="right"><input name="vat_percent" id="vat_percent" value = "12" size="5">% VAT</td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><input type='text' name='vat' id='vat' onchange='additionalCost()' value='<?php echo $vat_amount;?>' style='width:100%' ></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="13" class=" bor-right" align="right">Less: Discount</td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><input type='text' name='discount' id='discount' onchange='additionalCost()' value='0' style='width:100%' ></td>
		    		</tr>

		    		<tr>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="13" class="bor-btm bor-right" align="left">
		    			<?php 
		    			if(!empty($allpr)){
		    			foreach($allpr AS $p){ ?>
		    				<p class="nomarg">
		    					Enduse: <?php echo $p['enduse']; ?><br>
		    					Purpose: <?php echo $p['purpose']; ?><br>
		    					Requestor: <?php echo $p['requestor']; ?><br>
		    					PR no.: <?php echo $p['pr_no']."-".COMPANY; ?><br>
		    				</p>
		    				<br>
		    			<?php }
		    			} ?>
		    			</td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"><br></td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"></td>
		    		</tr>		
		    		<input type='hidden' id='orig_amount' value='<?php echo array_sum($gtotal); ?>'>    		
		    		<tr>
		    			<td colspan="18" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    <td colspan="2" class="all-border" align="right"><b class="nomarg"><span class="pull-left"><?php echo $currency; ?></span><span id='grandtotal'><?php echo number_format(array_sum($gtotal),2); ?></span></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#otherins">
							 Add Other Instruction
							</button><br>
		    				<?php foreach($tc AS $t){ ?>
								<p style = "color:blue;"><?php echo nl2br($t->notes);?> 
									<?php if(!empty($t->notes)){ ?>
										<a class='btn btn-primary btn-xs' id = "edits" data-toggle='modal' data-target='#EditIns' data-id = '<?php echo $t->po_tc_id; ?>' data-name = '<?php echo $t->notes; ?>'>
					    					<span class = 'fa fa-edit'></span>
					    				</a>
					    				<a href="<?php echo base_url(); ?>index.php/po/delete_inst/<?php echo $t->po_tc_id;?>/<?php echo $t->po_id;?>" class="btn btn-custon-three btn-danger btn-xs" onclick="confirmationDelete(this);return false;">
		                                    <span class="fa fa-times"></span>
		                                </a>
	                            	<?php } ?>
			    				</p>
							<?php } ?>	
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#exampleModal">
							 Add Terms & Conditions
							</button>
							<?php $x=3; ?>
		    				<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
		    				<?php $x=4; ?>
		    				<?php if(!empty($payment_terms)){ 
		    				echo $x."."; ?> Payment term: <?php echo $payment_terms ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>	
		    				<?php if(!empty($item_warranty)){ 
		    				echo $x."."; ?> Item Warranty: <?php echo $item_warranty; ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($delivery_time)){ 
		    				echo $x."."; ?> Delivery Time: <?php echo $delivery_time; ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($freight)){ 
		    				echo $x."."; ?> In-land Freight: <?php echo $freight; ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php 
		    					//$no=8;
		    			
		    					foreach($tc AS $t){ 
		    						if(!empty($t->tc_desc)){
			    						echo $x.". " . $t->tc_desc;
			    				?>
			    				<a class='btn btn-primary btn-xs prnt' id = "updateTerm" data-toggle='modal' data-target='#UpdateTerms' data-id = '<?php echo $t->po_tc_id; ?>' data-name = '<?php echo $t->tc_desc; ?>'>
			    					<span class = 'fa fa-edit'></span>
			    				</a>
			    				<br>
			    				<?php
			    					$x++; 
			    					}
		    					} 
		    				?>		    					
		    			</td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b>Prepared by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b>Reviewed/Checked by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b>Recommended by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3"><b>Approved by:</b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b><?php echo $prepared; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b>
		    			<select name='checked' class="select-des emphasis" style="width: 100%" >
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b>
		    			<select name='recommended' class="select-des emphasis" style="width: 100%" >
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3"><b>
		    			<select name='approved' class="select-des emphasis" style="width: 100%" required>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="4"></td>
		    			<td colspan="2"><b>Conforme:</b></td>
		    			<td colspan="8" class="bor-btm"><b></b></td>
		    			<td colspan="6"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="4"></td>
		    			<td colspan="2"><b></b></td>
		    			<td colspan="8" align="center"><b>Supplier's Signature Over Printed Name</b></td>
		    			<td colspan="6"></td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr><td colspan="20"><br></td></tr>
		    	</table>	    
	    	</div>
	    	<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
    	</form>
    	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Terms & Conditions
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>po/add_tc">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="tc_desc" autocomplete="off">
							</div>
						</div>
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

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
					<form method="POST" action="<?php echo base_url(); ?>po/update_terms">
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
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
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
					<form method="POST" action="<?php echo base_url(); ?>po/update_condition">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="condition" autocomplete="off" id = "terms">
							</div>
						</div>
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
						<input type='hidden' name='tc_id' id = "tc_id">
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="EditIns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Update Other Instructions
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>po/update_notes">
						<div class="modal-body">
							<div class="form-group">
								Other Instructions:
								<textarea class="form-control" rows="5" name = "notes" id="notes"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
							<input type='hidden' name='tc_id' id = "tc1_id">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="otherins" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Other Instructions
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>po/add_notes">
						<div class="modal-body">
							<div class="form-group">
								Other Instructions:
								<textarea class="form-control" rows="5" name = "notes"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>