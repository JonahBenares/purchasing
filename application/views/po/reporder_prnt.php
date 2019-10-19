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
			#prnt_btn,#item-btn,#pr-btn{
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

    	<form method='POST' action='<?php echo base_url(); ?>po/save_repeatPO'>  
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
						<?php if($saved==1){ ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>PO</b></u></a>
						<a  href="<?php echo base_url(); ?>po/reporder_dr/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>
						<a  href="<?php echo base_url(); ?>po/rfd_prnt/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>RFD</b></u></a>
						<?php } else { ?>
						<input type='submit' class="btn btn-warning btn-md p-l-100 p-r-100" name='submit' value="Save as Draft">
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" name='submit' value="Save">	
						<?php } ?>
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;">    		  			
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
		    			<td colspan="5" align="center"><img width="150px" src="<?php echo $_SESSION['logo'];?>"></td>
		    			<td colspan="15"><h4 style="margin: 0px"><b><?php echo $_SESSION['company_name'];?></b></h4></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo $_SESSION['address'];?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo $_SESSION['tel_no'];?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo $_SESSION['telfax'];?></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><?php echo $_SESSION['address2'];?></td></tr>
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b></h4></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no']; ?></b></h6></td>
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
					    			<td colspan="" class="bor-right" align="center"><b><?php echo $it['quantity']; ?></b></td>
					    			<td colspan="" class="bor-right" align="center"><b><?php echo $it['uom']; ?></b></td>
					    			<td colspan="12" class="bor-right" align="left"><b><?php echo $it['offer']; ?></b></td>
					    			<td colspan="2" class="bor-right" align="center"><b><?php echo number_format($it['price'],2); ?></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b><?php echo number_format($it['amount'],2); ?></b></td>
					    			<!-- <td align="center"><a href='<?php echo base_url(); ?>/po/remove_po_item/' class="btn-danger btn-xs" onclick="return confirm('Are you sure you want to remove item?')"><span class="fa fa-times"></span></a></td>	 -->			
					    		</tr> 
					    		<?php 
					    		$x++;
					    			}
					    		 ?>
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
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='shipping' id='shipping' value='0' onchange='additionalCost()' style='width:100%' ></td>
					    		</tr>
					    		<tr>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="" class=" bor-right" align="center"></td>
					    			<td colspan="12" class=" bor-right" align="right">Less: Discount</td>
					    			<td colspan="2" class=" bor-right" align="center"></td>
					    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='discount' id='discount' onchange='additionalCost()' value='0' style='width:100%' ></td>
					    		</tr>
					    		<tr>
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
					    				<!-- 	<p class="f12 nomarg">Notes: <?php echo $pr['notes']?></p> -->
					    					<p class="f12 nomarg">Purpose: <?php echo $pr['purpose']?></p>
					    					<p class="f12 nomarg">End Use: <?php echo $pr['enduse']?></p>
					    					<p class="f12 nomarg">Requestor: <?php echo $pr['requestor']?></p>
						    			</b>
						    		</td>
					    			<td colspan="2" class="bor-right" align="center"><b></b></td>
					    			<td colspan="3" class="bor-right" align="right"><b></b></td>
					    			<!-- <td colspan="" class="bor-right" align="center"><b></b></td> -->
					    		</tr> 
					    		<?php } } ?>
					    		<input type='hidden' id='orig_amount' value='<?php echo array_sum($total_amount); ?>'>
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
					    		<tr>
					    			<td colspan="17" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><span class="pull-left">₱</span><span id='grandtotal'><?php echo number_format(array_sum($total_amount),2); ?></span></b></td>
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
		    					<p class="f12 nomarg">Item No. <?php echo $it['item_no']; ?> is a repeat Order of PO No. <?php echo $it['orig_pono']; ?></p>
		    				 <?php } 
							} ?>
			    		</td>
			    	</tr>
				   
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($saved==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs " data-toggle="modal" data-target="#terms">
							 Add Terms & Conditions:
							</button>
							<?php } ?>
							<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo $_SESSION['jo_name'];?>.<br>
		    				<?php 
		    					$no=4;
		    					foreach($tc AS $t){ 
		    						if(!empty($t->tc_desc)){
			    						echo $no.". " . $t->tc_desc."<br>";
			    						$no++; 
			    					}
		    					} 
		    				?>
		    			</td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Prepared by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Checked by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b>Approved by:</b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b><?php echo $prepared; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b>
			    			<?php if($saved==0){ ?>
			    			<select name='checked' class="select-des emphasis" style="width: 100%" required>
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
			    			</select></b></td>
			    			<?php }else { ?>
			    			<?php echo $checked; } ?>
		    			<td colspan="1"></td>
		    			<td colspan="6" class="bor-btm"><b>
			    			<?php if($saved==0){ ?>
			    			<select name='approved' class="select-des emphasis" style="width: 100%" required>
				    			<option value=''>-Select-</option>
				    			<?php foreach($employee AS $emp){ ?>
				    			<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
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
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>