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
		.f12{
			font-size:12px!important;
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
			.prnt,#prnt_btn,#item-btn,#pr-btn,#updateTermRep{
				display: none;
			}
			.emphasis{
				border: 0px solid #fff!important;
			}
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
	        }
	        #prhide{
				display: none!important;
			}
			input, textarea {
		        border: 0 !important;
		        border-style: none !important;
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
				<form method='POST' action="<?php echo base_url(); ?>po/add_purpose">
			
				<div class="form-group">
					<h5 class="nomarg">Notes:</h5>
					<h5 class="nomarg"><b>
						<input type='text' name='notes' class="form-control">
					</b></h5>
				</div>
				<div class="form-group">
					<h5 class="nomarg">Requestor:</h5>
					<h5 class="nomarg"><b>
						<!--  <select name='requested_by' class="form-control">
						 	<option value='' selected>-Select Employee-</option>
						 	<?php foreach($employee AS $emp){ ?>
                            <option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
                        	<?php } ?>
                        </select> -->
                        <input name='requested_by' class="form-control">
					</b></h5>
				</div>
				<div class="form-group">
					<h5 class="nomarg">Purpose:</h5>
					<h5 class="nomarg"><b>
						<input name='purpose' class="form-control">
					</b></h5>
				</div>

				<div class="form-group">
					<h5 class="nomarg">Enduse:</h5>
					<h5 class="nomarg"><b>
						 <input name='enduse' class="form-control">
					</b></h5>
				</div>
				
				</div>
				<div class="modal-footer">
					<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
					<input type='hidden' name='po_pr_id' value='<?php echo $po_pr_id; ?>'>
					<input type="submit" class="btn btn-primary btn-block" value="Save changes">
				</div>
			</form>
			</div>
		</div>
	</div>
    <div  class="pad">

    	<form method='POST' action='<?php echo base_url(); ?>po/save_repeatPO_draft'>  
    		<input type='hidden' name='po_id' value="<?php echo $po_id; ?>">
    		<input type='hidden' name='prepared_by' value="<?php echo $_SESSION['user_id']; ?>">
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($revised==0 && $saved==1){ ?>
							<a  href='<?php echo base_url(); ?>po/purchase_order_rev/<?php echo $po_id; ?>' onclick="return confirm('Are you sure you want to revise PO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>PO</b></u></a>
						<?php } ?>
						<!-- <a  href='<?php echo base_url(); ?>po/revise_repeatpo/' onclick="return confirm('Are you sure you want to revise PO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>PO</b></u></a> -->
						<?php if($draft==1){ ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span>Print</a>
						<!-- <a  href="<?php echo base_url(); ?>po/reporder_dr/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>
						<a  href="<?php echo base_url(); ?>po/rfd_prnt/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>RFD</b></u></a> -->
						<?php //} else { ?>
						<input type='submit' class="btn btn-warning btn-md p-l-100 p-r-100" name='submit' value="Save as Draft">
						<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" name='submit' value="Save">	
						<?php } ?>
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
		    	<table class="table-borddered" width="100%" style="border:2px solid #000">
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
		    		<!-- <tr><td class="f13" colspan="20" align="center"><?php echo ADDRESS;?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo TEL_NO;?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo TELFAX;?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo ADDRESS_2;?></td></tr> -->
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b></h4><small class="text-red">DRAFT</small></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no']."-".COMPANY.(($revision_no!=0) ? ".r".$revision_no : ""); ?></b></h6></td>
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
		    		<tr id="pr-btn">
		    			<td colspan="20" style="padding-left: 10px">
		    				<div class="btn-group" id="prhide">
		    					<?php if($saved==0){ 

		    						 ?>
			    				<a class="addPR btn btn-primary btn-xs" onclick="addPo('<?php echo base_url(); ?>','<?php echo $po_id; ?>','<?php echo $vendor_id; ?>','<?php echo $pr_id; ?>','<?php echo $group_id; ?>')" data-id="">
								  Add PO
								</a>
								<?php } ?>
							<!-- 	<a class="addPR btn btn-warning btn-xs" data-toggle="modal" href="#add-pr" data-id="" data-target="#add-pr">
								  Add PR
								</a> -->
							</div>
		    			</td>
		    		</tr>	
		    		<!-- LOOp Here -->  	
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="padding: 10px!important">
		    				<table  class="table-borddered" width="100%" style="border:1px solid #000;">
		    					<tr>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    		</tr>
		    					<tr>
					    			<td colspan="" class="all-border" align="center"><b>#</b></td>
					    			<td colspan="" class="all-border" align="center"><b>Qty</b></td>
					    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
					    			<td colspan="12" class="all-border" align="center"><b>Description</b></td>
					    			<td colspan="2" class="all-border" align="center"><b>Unit Price</b></td>
					    			<td colspan="3" class="all-border" align="center"></td>
					    			<!-- <td class="all-border" align="center"><span class="fa fa-times"></span></td> -->
					    		</tr>	
					    		<?php
					    		$x=1; 
					    		if(!empty($items)){
					    			foreach($items AS $it){ 
					    				$total_amount[] = $it['amount']; ?>
					    		<tr>
					    			<td colspan="" class="bor-right" align="center"><b><?php echo $x; ?></b></td>
					    			<td colspan="" class="bor-right" align="center">
					    				<!-- <b><?php echo $it['quantity']; ?></b> -->
					    				<b><input type='number' name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' max='<?php echo $it['quantity']; ?>' value='<?php echo $it['quantity']; ?>' style='width:50px; color:red' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"></b>
					    			</td>
					    			<td colspan="" class="bor-right" align="center"><b><input type='text' name='uom<?php echo $x; ?>' id='uom<?php echo $x; ?>' class='uom' value='<?php echo $it['uom']; ?>' style = "width:100%;"><?php //echo $it['uom']; ?></b></td>
					    			<td colspan="12" class="bor-right" align="left">
					    				<!-- <b><?php echo $it['offer']; ?></b> -->
					    				<b><textarea style="width:100%" name='offer<?php echo $x; ?>'><?php echo $it['offer']; ?></textarea></b>
					    			</td>
					    			<td colspan="2" class="bor-right" align="center">
					    				<!-- <b><?php echo number_format($it['price'],2); ?></b> -->
					    				<b><input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' value='<?php echo number_format($it['price'],4); ?>' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)" style='color:red;width:100%' ></b>
					    			</td>
					    			<td colspan="3" class="bor-right" align="right">
					    				<b>
					    				<!-- <?php echo number_format($it['amount'],2); ?> -->
					    				<input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="<?php echo number_format($it['amount'],2); ?>" style='text-align:right;width:100%' readonly>
					    				</b>
					    			</td>
					    			<!-- <td align="center"><a href='<?php echo base_url(); ?>/po/remove_po_item/' class="btn-danger btn-xs" onclick="return confirm('Are you sure you want to remove item?')"><span class="fa fa-times"></span></a></td>	 -->
					    			<input type='hidden' name='po_items_id<?php echo $x; ?>' value="<?php echo $it['po_items_id']; ?>">		
					    		</tr> 
					    		<?php 
					    		$x++;
					    		$vat_amount=array_sum($total_amount)*0.12;
					    			}
					    		 ?>
					    		 <input type='hidden' name='count_item' value="<?php echo $x; ?>">
					    		<tr>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b><br></b></td>
					    			<td colspan="12" class="bor-right" align="left"><b></b></td>
					    			<td colspan="2" class="bor-right" align="center"><b></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b></b></td>
					    			<!-- <td colspan="" class="bor-right" align="center"><b></b></td> -->
					    		</tr> 
					    		<tr>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="12" class=" bor-right" align="right">Shipping Cost</td>
					    			<td colspan="2" class=" bor-right" align="center"></td>
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='shipping' id='shipping' value='<?php echo ($shipping!=0) ? $shipping : '0'; ?>' onchange='additionalCost()' style='width:100%' ></td>
					    		</tr>
					    		<tr>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="12" class=" bor-right" align="right">Packing and Handling Fee</td>
					    			<td colspan="2" class=" bor-right" align="center"></td>
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='packing' id='packing' onchange='additionalCost()' value='<?php echo ($packing!=0) ? $packing : '0'; ?>' style='width:100%' ></td>
					    		</tr>
					    		<tr>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="12" class=" bor-right" align="right"><input type = "text" name="vat_percent" id="vat_percent" value="<?php echo $vat_percent; ?>" size="5">% VAT</td>
					    			<td colspan="2" class=" bor-right" align="center"></td>
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='vat' id='vat' onchange='additionalCost()' value='<?php echo $vat; ?>' style='width:100%' ></td>
					    		</tr>
					    		<tr>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="12" class=" bor-right" align="right">Less: Discount</td>
					    			<td colspan="2" class=" bor-right" align="center"></td>
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='discount' id='discount' onchange='additionalCost()' value='<?php echo ($discount!=0) ? $discount : '0'; ?>' style='width:100%' ></td>
					    		</tr>
					    		<!-- <tr>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="12" class="bor-right" align="left">
					    			<?php if($saved==0){ ?>
					    			<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-xs btn-primary" onclick="" >Add Purpose/ EndUse/ Requestor</button>
					    			<?php } ?>
					    			</td>
					    			<td colspan="2" class="bor-right" align="center"><b></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    		</tr> -->
					    		<?php 
					    			if(!empty($popr)){
					    				foreach($popr AS $pr) { ?>
					    		<tr>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="12" class="bor-right" align="left">
					    				<b>
					    					<!-- <p class="f12 nomarg">Notes: <?php echo $pr['notes']?></p> -->
					    					<p class="f12 nomarg">End Use: <?php echo $pr['enduse']?></p>
					    					<p class="f12 nomarg">Purpose: <?php echo $pr['purpose']?></p>
					    					<p class="f12 nomarg">Requestor: <?php echo $pr['requestor']?></p>
					    					<p class="f12 nomarg">PR No: <?php echo $pr_no."-".COMPANY; ?></p>
						    			</b>
						    		</td>
					    			<td colspan="2" class="bor-right" align="center"><b></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b></b></td>
					    			<!-- <td colspan="" class="bor-right" align="center"><b></b></td> -->
					    		</tr> 
					    		<?php } } ?>
					    		<tr>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b></b></td>
					    			<td colspan="" class="bor-right" align="center"><b><br></b></td>
					    			<td colspan="12" class="bor-right" align="left"><b></b></td>
					    			<td colspan="2" class="bor-right" align="center"><b></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b></b></td>
					    			<!-- <td colspan="" class="bor-right" align="center"><b></b></td> -->
					    		</tr> 
					    	</table>
			    		</td>
			    	</tr>
			    	<!-- LOOp Here --> 
			    	
			    	<tr>
		    			<td class="f13" colspan="20" align="center" style="padding: 10px!important">
		    				<table  class="table-bodrdered" width="100%" style="border:0px solid #000;">
		    					<tr>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    		</tr>
					    		<input type='hidden' id='orig_amount' value='<?php echo array_sum($total_amount); ?>'>
					    		<?php 
					    			$total =array_sum($total_amount);
					    			$grandtotal = ($total+$shipping+$packing+$vat)-$discount;
					    		?>
					    		<tr>
					    			<td colspan="17" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><span class="pull-left">₱</span><span id='grandtotal'><?php echo number_format($grandtotal,2); ?></span></b></td>
					    		</tr>
					    	<?php } ?>
		    				</table>
			    		</td>
			    	</tr>
			    
			    	<tr>
		    			<td class="f13" colspan="20" style="padding: 10px!important">
		    				<p class="f12 nomarg">Note:</p>
		    					<?php 
						    	if(!empty($items)){
								    	foreach($items AS $it){ 
								  ?>
		    					<p class="f12 nomarg">Item No. <?php echo $it['item_no']; ?> is a repeat Order of PO No. <?php echo $it['orig_pono']."-".COMPANY; ?></p>
		    				 <?php } 
							} ?>
			    		</td>
			    	</tr>
				   
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($saved==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#terms">
							 Add Terms & Conditions:
							</button>
							<?php } ?>
							<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
		    				<?php $x=4; ?>
		    				<?php if(!empty($payment_terms)){ 
		    				echo $x."."; ?> Payment term: <?php echo $payment_terms; ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
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
		    					//$no=4;
		    					foreach($tc AS $t){ 
		    						if(!empty($t->tc_desc)){
			    						echo $x.". " . $t->tc_desc;
			    				?>
			    				<a class='btn btn-primary btn-xs' id = "updateTermRep" data-toggle='modal' data-target='#UpdateTerms' data-id = '<?php echo $t->po_tc_id; ?>' data-name = '<?php echo $t->tc_desc; ?>'>
			    					<span class = 'fa fa-edit'></span>
			    				</a>
			    				<br>
			    				<?php
			    					$x++; 
			    					//$no++; 
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
		    			<td colspan="4" class="bor-btm"><b></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4" class="bor-btm"><b></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3" class="bor-btm"><b></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="4" ><b><?php echo $prepared; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4" ><b>
			    			<?php if($saved==0){ ?>
			    			<select name='checked' class="select-des emphasis" style="width: 100%" required>
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>' <?php echo (($checked_id==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $checked; } ?>

			    		<td colspan="1"></td>
		    			<td colspan="4" ><b>
			    			<?php if($saved==0){ ?>
			    			<select name='recommended' class="select-des emphasis" style="width: 100%" required>
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>' <?php echo (($recommended_id==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $recommended; } ?>

		    			<td colspan="1"></td>
		    			<td colspan="3" ><b>
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
    	<div class="modal fade" id="terms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Terms & Conditions
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>po/add_tc_reporder">
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
				<form method="POST" action="<?php echo base_url(); ?>po/update_condition_reporderdraft">
					<div class="modal-body">
						<div class="form-group">
							Terms & Conditions:
							<input type="text" class="form-control" name="condition" autocomplete="off" id = "termsrep">
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
				<form method="POST" action="<?php echo base_url(); ?>po/update_terms__reporderdraft">
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
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>