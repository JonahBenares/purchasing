  	<script src="<?php echo base_url(); ?>assets/js/pod.js"></script> 
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
			.prnt,#prnt_btn,#item-btn,#pr-btn{
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
    <div class="modal fade" id="addpurp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
				<form method='POST' action="<?php echo base_url(); ?>pod/add_po_purpose">
				<div class="form-group">
					<h5 class="nomarg">Notes:</h5>
					<h5 class="nomarg"><b>
						<input type='text' name='notes' class="form-control">
					</b></h5>
				</div>
				<div class="form-group">
					<h5 class="nomarg">Requestor:</h5>
					<h5 class="nomarg"><b>
						<!-- <select name='requested_by' class="form-control">
                            <option value='' selected>-Select Employee-</option>
                            <?php foreach($employee AS $emp){ ?>
			    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
                        </select> -->
                        <input type='text' name='requested_by' class="form-control">
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
					<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
					<input type="submit" class="btn btn-primary btn-block" value="Save changes">
				</div>
			</form>
			</div>
		</div>
	</div>
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>pod/save_po_draft'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<a href = '<?php echo base_url(); ?>pod/po_direct_saved/<?php echo $po_id; ?>' class="btn btn-warning btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Print</a>
						<input type='submit' class="btn btn-warning btn-md p-l-100 p-r-100" name='submit' value="Save as Draft">
						<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" name = "submit" value="Save">	
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
		    	<table class="table-borsdered" width="100%" style="border:2px solid #000;border-bottom: none;">
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
		    		<tr><td colspan="20" align="center">
		    			<br><h4 class="nomarg"><b>PURCHASE ORDER</b></h4><small class="text-red">DRAFT</small></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no'] ."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : "");  ?></b></h6></td>
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
		    				<?php if($saved==0){ ?>
		    				<a class="addPR btn btn-primary btn-xs" onclick="additempod('<?php echo base_url(); ?>','<?php echo $po_id; ?>','<?php echo $supplier_id; ?>')">
							  Add Item
							</a>
							<?php }?>
		    			</td>
		    		</tr> -->	
		    		<!-- LOOp Here --> 
					<tr>
		    			<td colspan="" class="all-border" align="center"><b>#</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Qty</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
		    			<td colspan="12" class="all-border" align="center"><b>Description</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>Unit Price</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Currency</b></td>		    			
		    			<td colspan="2" class="all-border" align="center"></td>
		    		</tr>
		    		<?php 
		    			$x=1;
		    			if(!empty($items)){
		    				foreach($items AS $it){ 
		    					$gtotal[] = $it['total']; 
		    		?>
		    		<tr>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $x; ?></b></td>

		    			<td colspan="" class="bor-right v-align" align="center"><b><input type='number' name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' value='<?php echo $it['quantity']; ?>' max='<?php echo $it['quantity']; ?>' style='width:50px; color:red' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"></b></td>

		    			<!-- <td colspan="" class="bor-right v-align" align="center"><b><?php //echo $it['uom']; ?></b></td> -->
		    			<td colspan="" class="bor-right v-align" align="center"><b><input type='text' name='uom<?php echo $x; ?>' id='uom<?php echo $x; ?>' class='uom' value='<?php echo $it['uom']; ?>' style = "width:100%;"></b></td>

		    			<td colspan="12" class="bor-right v-align" align="left"><b class="nomarg"><textarea class = "form-control" name='item<?php echo $x; ?>'><?php echo $it['item']; ?></textarea></b></td>

		    			<td colspan="2" class="bor-right v-align" align="center"><b><input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>'  onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)" style='color:red; width:100px' value='<?php echo number_format($it['price'],4); ?>'></b></td>
						<td width="10%" class="bor-right v-align" align="center">
		    				<?php if($saved==0){ ?>
				    		<select style="width: 100%" name='currency<?php echo $x; ?>'>
						    	<?php foreach($currency AS $curr){ ?>
						    		<option value="<?php echo $curr; ?>" <?php echo (($curr==$it['currency']) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    	<?php } ?>
						    </select>
							<?php }else{ echo $it['currency']; } ?>
				    	</td>
		    			<td colspan="2" class="bor-right v-align" align="right"><b class="nomarg"><input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' style='text-align:right;' value='<?php echo number_format($it['total'],2); ?>' readonly></b></td>

		    		</tr>
		    		<!-- <input type='hidden' name='uom<?php echo $x; ?>' value="<?php echo $it['uom']; ?>"> -->
		    		<input type='hidden' name='po_items_id<?php echo $x; ?>' value="<?php echo $it['po_items_id']; ?>">
		    		<input type='hidden' name='pr_details_id<?php echo $x; ?>' value="<?php echo $it['pr_details_id']; ?>">
		    		<?php 
		    			$x++; } } 
		    			else { 
		    				$gtotal=array(); 
		    			} 
		    		?>
		    			<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="left">
		    				<p class="nomarg"><br></p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>		    			
		    			<td colspan="2" class="bor-right" align="right"><b class="nomarg"></b></td>		
		    		</tr>	
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Shipping Cost</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class="bor-right" align="right"><b class="nomarg"><input type='text' name='shipping' id='shipping' onchange='additionalCost()' style='width:100%' value='<?php echo $shipping; ?>'></b></td>		
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Packing and Handling Fee</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class="bor-right" align="right"><b class="nomarg"><input type='text' name='packing' id='packing' onchange='additionalCost()' value='<?php echo $packing; ?>' style='width:100%' ></b></td>		
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg"><input name="vat_percent" id="vat_percent" value = "<?php echo $vat_percent; ?>" size="5">% VAT</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class="bor-right" align="right"><b class="nomarg"><input type='text' name='vat' id='vat' onchange='additionalCost()' value='<?php echo $vat; ?>' style='width:100%' ></b></td>		
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Less: Discount</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class="bor-right" align="right"><b class="nomarg"><input type='text' name='discount' id='discount' onchange='additionalCost()' value='<?php echo $discount; ?>' style='width:100%' ></b></td>		
		    		</tr>
		    		<input type='hidden' name='count_item' value="<?php echo $x; ?>">
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="12" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    		</tr>

		    		<tr>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="12" class="bor-btm bor-right" align="left">
	    				<p class="nomarg">
	    					<!-- <?php if($saved==0){ ?>
	    					<button type="button" data-toggle="modal" data-target="#addpurp" class="btn btn-xs btn-primary" onclick="" >Add Purpose/ Enduse/ Requestor</button>
	    					<?php } ?>
	    					<br> -->
	    					<?php 
				    			if(!empty($popurp)){
				    				foreach($popurp AS $pp) { 
				    		?>
	    					Enduse: <?php echo $pp['enduse']; ?><br>
	    					Purpose: <?php echo $pp['purpose']; ?><br>
	    					Requestor: <?php echo $pp['requestor']; ?><br>
	    					<!-- Notes: <?php echo $pp['notes']; ?><br> -->
	    					<br>
	    					<?php } } ?>
	    				</p>
	    				<br>
		    			</td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"><br></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"></td>
		    		</tr>		   
		    		<?php
		    			$grtotal =array_sum($gtotal);
		    			$grandtotal = ($grtotal+$shipping+$packing+$vat)-$discount;
		    		?> 		
		    		<input type='hidden' id='orig_amount' value='<?php echo array_sum($gtotal); ?>'>   
		    		<tr>
		    			<td colspan="18" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    <td colspan="2" class="all-border" align="right"><b class="nomarg"><span class="pull-left"></span><span id='grandtotal'><?php echo number_format($grandtotal,2); ?></span></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20">
		    				<i></i>
		    			</td>
		    		</tr>
		    		<tr>		    			
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($saved==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#otherins">
							 Add Other Instruction
							</button><br>
							<?php } ?>
		    				Other Instructions: 
		    				<?php 
		    					foreach($tc AS $t){ 
		    						if(!empty($t->notes)) { 
		    				?>
		    					<p style = "color:blue;"><?php echo nl2br($t->notes);?>
		    						<?php if(!empty($t->notes)){ ?>
										<a class='btn btn-primary btn-xs prnt' id = "edits" data-toggle='modal' data-target='#EditIns' data-id = '<?php echo $t->po_tc_id; ?>' data-name = '<?php echo $t->notes; ?>'>
					    					<span class = 'fa fa-edit'></span>
					    				</a>
					    				<a href="<?php echo base_url(); ?>index.php/pod/delete_inst/<?php echo $t->po_tc_id;?>/<?php echo $t->po_id;?>" class="btn btn-custon-three btn-danger btn-xs" id = "prnt_btn" onclick="confirmationDelete(this);return false;">
		                                    <span class="fa fa-times"></span>
		                                </a>
	                            	<?php } ?>
		    					</p>
		    				<?php } } ?>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($saved==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#exampleModal">
							 Add Terms & Conditions:
							</button>
							<?php } ?>
		    				<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>	
		    				<?php 
		    					$no=4;
			    				foreach($tc AS $t){ 
			    					if(!empty($t->tc_desc)){
			    						echo $no.". " . $t->tc_desc;
			    						$no++; 
			    			?>
			    			<a class='btn btn-primary btn-xs prnt' id = "updateTerm" data-toggle='modal' data-target='#UpdateTerms' data-id = '<?php echo $t->po_tc_id; ?>' data-name = '<?php echo $t->tc_desc; ?>'>
		    					<span class = 'fa fa-edit'></span>
		    				</a><br>
			    			<?php } } ?>	  	
		    			</td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    	</table>
		    	<table class="table-borsdered" width="100%" style="border:2px solid #000;border-top: none;">
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
		    			<td colspan="4"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3"><b></b></td>
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
		    			<td colspan="4" class=""><b><?php echo $prepared; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4" class=""><b>
			    			<?php if($saved==0){ ?>
			    			<select name='checked' class="select-des emphasis" style="width: 100%" >
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>' <?php echo (($checked_id==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $checked; } ?>

			    			<td colspan="1"></td>
		    			<td colspan="4" class=""><b>
			    			<?php if($saved==0){ ?>
			    			<select name='recommended' class="select-des emphasis" style="width: 100%" >
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>' <?php echo (($recommended_id==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $recommended; } ?>

		    			<td colspan="1"></td>
		    			<td colspan="3" class=""><b>
			    			<?php if($saved==0){ ?>
			    			<select name='approved' class="select-des emphasis" style="width: 100%" required>
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>' <?php echo (($approved_id==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $approved; } ?>
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
	    	<input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
	    	<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
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
					<form method="POST" action="<?php echo base_url(); ?>pod/add_tc_draft">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="tc_desc" autocomplete="off">
							</div>
						</div>
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
						<input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
						<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save Changes">
						</div>
					</form>
				</div>
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
				<form method="POST" action="<?php echo base_url(); ?>pod/add_notes">
					<div class="modal-body">
						<div class="form-group">
							Other Instructions:
							<textarea class="form-control" rows="5" name = "notes"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
						<input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
						<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
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
					<form method="POST" action="<?php echo base_url(); ?>pod/update_notes">
						<div class="modal-body">
							<div class="form-group">
								Other Instructions:
								<textarea class="form-control" rows="5" name = "notes" id="notes"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
							<input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
							<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
							<input type='hidden' name='tc_id' id = "tc1_id">
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
					<form method="POST" action="<?php echo base_url(); ?>pod/update_condition_draft">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="condition" autocomplete="off" id = "terms">
							</div>
						</div>
						<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
						<input type='hidden' name='pr_id' value='<?php echo $pr_id; ?>'>
						<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
						<input type='hidden' name='tc_id' id = "tc_id">
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>