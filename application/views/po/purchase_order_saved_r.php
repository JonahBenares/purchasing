	<?php
	$ci =& get_instance();
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
	</head>

  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .cancel{
        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
        }
        .amend{
        	background-image: url('<?php echo base_url(); ?>assets/img/amendment.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
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
	        .cancel{
	        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
	        	background-repeat:no-repeat!important;
	        	background-size: contain!important;
	        	background-position: center center!important;
	        }
	        .amend{
	        	background-image: url('<?php echo base_url(); ?>assets/img/amendment.png')!important;
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
		.v-align{
			vertical-align: top;
		}
    </style>

	<!-- Modal -->
	<div class="modal fade" id="uploadApproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Upload Approval
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</h5>					
				</div>
				<form enctype="multipart/form-data" method = "POST" action = '<?php echo base_url(); ?>po/upload_revise'>
					<div class="modal-body">
						<input type="file" name="revise_img" class="form-control">
					</div>
					<div class="modal-footer">
						<?php
			    		$x=1; 
			    		foreach($items AS $it){ ?>
							<input type='hidden' name='aoq_offer_id<?php echo $x; ?>' value="<?php echo $it->aoq_offer_id; ?>">
							<input type='hidden' name='qty<?php echo $x; ?>' value="<?php echo $it->quantity; ?>">
						<?php $x++; } ?>
						<input type='hidden' name='count_item' value="<?php echo $x; ?>">
						<input type="hidden" name="po_id" value = "<?php echo $po_id; ?>">
						<input type="hidden" name="po_no" value = "<?php echo $po_no; ?>">
						<button type="submit" class="btn btn-primary btn-block">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

    <div  class="pad ">
    	<form method='POST' action='<?php echo base_url(); ?>po/po_complete'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="<?php echo base_url(); ?>po/po_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<!-- <?php if($revised==0){ ?>
						<a  href='<?php echo base_url(); ?>po/revise_po/<?php echo $po_id; ?>' onclick="return confirm('Are you sure you want to revise PO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>PO</b></u></a>
						<?php } ?> -->
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b><?php if($revised==0){ echo 'PO'; }else { echo 'RFA'; } ?></b></u></a>
						<?php if($revised==0){ ?>
							<?php if($revise_no!=''){ ?>
							<a  href="<?php echo base_url(); ?>po/delivery_receipt_r/<?php echo $po_id; ?>/<?php echo $revise_no; ?>" target='_blank' class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>
							<?php } else { ?>
							<a  href="<?php echo base_url(); ?>po/delivery_receipt/<?php echo $po_id; ?>" target='_blank' class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>
							<?php } ?>
						<a  href="<?php echo base_url(); ?>po/rfd_prnt/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25" target='_blank'><span class="fa fa-print"></span> Print <u><b>RFD</b></u></a>
						<a  href="<?php echo base_url(); ?>po/rfd_calapan/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25" target='_blank'><span class="fa fa-print"></span> Print <u><b>RFD Calapan</b></u></a>
						<?php } ?>
						<?php if($revised==1){ ?>
						<a  href="#" class="btn btn-primary btn-md p-l-25 p-r-25" data-toggle="modal" data-target="#uploadApproval"><span class="fa fa-upload"></span> Upload <u><b>Approval</b></u></a>
						<?php } ?>
							
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" class="<?php if($revised==1){ echo 'amend'; }?>" >  <!-- add class or amend cancel -->
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
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b><br><small class="text-red">REVISED</small></h4></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no'] ."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : ""); ?></b></h6></td>
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
		    		<!-- LOOp Here -->  
					<tr>
		    			<td colspan="" class="all-border" align="center"><b>#</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Qty</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
		    			<td colspan="12" class="all-border" align="center"><b>Description</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>Unit Price</b></td>
		    			<td colspan="3" class="all-border" align="center"></td>
		    		</tr>
		    		<?php
		    		$x=1; 
		    		foreach($items AS $it){ 
		    			$gtotal[] = $it->amount; ?>
		    		<tr>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $x; ?></b></td>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo number_format($it->delivered_quantity); ?></b></td>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $it->uom; ?></b></td>
		    			<td colspan="12" class="bor-right v-align" align="left"><b class="nomarg"><?php echo $it->offer; ?></b></td>
		    			<td colspan="2" class="bor-right v-align" align="center"><b><?php echo $it->unit_price; ?></b></td>
		    			<td colspan="3" class="bor-right v-align" align="right"><b class="nomarg"><?php echo number_format($it->amount,2); ?></b></td>		
		    		</tr>	
		    		<?php 
		    		$x++; } ?>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="left">
		    				<p class="nomarg"><br></p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"></b></td>		
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Shipping Cost</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"><?php echo number_format($shipping,2); ?></b></td>		
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Packing and Handling Fee</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"><?php echo number_format($packing,2); ?></b></td>		
		    		</tr>
		    		<?php if($vat_percent!=0){ ?>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg"><?php echo $vat_percent; ?>% VAT</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"><?php echo number_format($vat,2); ?></b></td>		
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="right">
		    				<p class="nomarg">Less: Discount</p>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"><?php echo number_format($discount,2); ?></b></td>		
		    		</tr>	
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="left">
		    				<?php foreach($allpr AS $pr){ ?>
		    				<p class="nomarg">
		    					Enduse: <?php echo $pr['enduse']; ?><br>
		    					Purpose: <?php echo $pr['purpose']; ?><br>
		    					Requestor: <?php echo $pr['requestor']; ?><br>
		    					PR no.: <?php echo $pr['pr_no']."-".COMPANY; ?><br>
		    				</p><br>
		    				<?php } ?>
		    			</td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"></b></td>		
		    		</tr>	
		    		<tr>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="" class="bor-right" align="center"><b></b></td>
		    			<td colspan="12" class="bor-right" align="left"><b class="nomarg"></b></td>
		    			<td colspan="2" class="bor-right" align="center"><b></b></td>
		    			<td colspan="3" class="bor-right" align="right"><b class="nomarg"></b></td>		
		    		</tr>	
		    		<?php $grtotal =array_sum($gtotal);
		    		$grandtotal = ($grtotal+$shipping+$packing)-$discount;
		    		?>
		    		<tr>
		    			<td colspan="17" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
		    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><span class="pull-left">₱</span><span id='grandtotal'><?php echo number_format($grandtotal,2); ?></span></b></td>
		    		</tr>
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
					    		
		    				</table>
			    		</td>
			    	</tr>
		    		<tr>
		    			<td colspan="20">
		    				<i></i>
		    			</td>
		    		</tr>
		    	<!-- 	<tr>
		    			<td colspan="2"><h6 class="nomarg text-red"><b>Cancel Date:</b></h6></td>
		    			<td colspan="4"><h6 class="nomarg text-red"><b></b></h6></td>
		    			<td colspan="2" align="right"><h6 class="nomarg text-red"><b>Reason:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg text-red"><b></b></h6></td>
		    		</tr> -->
		    		<tr><td colspan="20" style="padding: 10px!important">
								Other Instructions:<br><?php foreach($tc AS $t){ ?><span style = "color:blue;"><?php echo nl2br($t->notes)."<br>";?></span><?php } ?>
		    		</td></tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
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
		    				<!-- <br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
		    				4. Payment term: PAYEMENT TERMS HERE<br>
		    				5. Delivery Term: Exstock of Supplier. -->
		    			</td>
		    		</tr>
		    		
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Prepared by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b>Reviewed/Checked by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Approved by:</b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b><?php echo $_SESSION['fullname']; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b><?php echo $checked;?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b><?php echo $approved;?></b></td>
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
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>