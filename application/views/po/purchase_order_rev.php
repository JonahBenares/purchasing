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
		.yellow-back{
			background-image: url('<?php echo base_url(); ?>assets/img/yellow.png')!important;
		}
		.newdata{
			background-image: url('<?php echo base_url(); ?>assets/img/newdata.png')!important;
			background-position: center!important;
			background-repeat: no-repeat!important;
		}
		.green-back{
			background-image: url('<?php echo base_url(); ?>assets/img/green.png')!important;
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
        	.yellow-back{
				background-image: url('<?php echo base_url(); ?>assets/img/yellow.png')!important;
			}
			.green-back{
				background-image: url('<?php echo base_url(); ?>assets/img/green.png')!important;
			}
			.newdata{
				background-image: url('<?php echo base_url(); ?>assets/img/newdata.png')!important;
				background-position: center!important;
				background-repeat: no-repeat!important;
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

     <div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="approveLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approved Revision <span class="fa fa-thumbs-o-up"></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                    
                </div>
                <form method='POST' action="<?php echo base_url(); ?>po/approve_revision">
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="m-b-0">Approved by:</p>
                            <input type="text" name="approve_rev" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <p class="m-b-0">Approved Date:</p>
                            <input type="date" name="approve_date" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="po_id" id="po_id" >
                        <input type='submit' value='Approve' class="btn btn-custon-three btn-primary btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>po/save_change_order'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href='<?php echo base_url(); ?>po/po_list' class="btn btn-success btn-md p-l-50 p-r-50"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Print</a>
						<!-- 	<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" value="Save Revision">	 -->
						<?php if($revised==0){ ?>
							<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" value="Save & Print Change Order Form">	
						<?php } else { ?>
							 <a class="btn btn-custon-three btn-info btn-md approverev" title='Aprrove Revision' data-toggle="modal" data-target="#approve" data-id="<?php echo $po_id; ?>"><span class="fa fa-thumbs-up"></span> Approve Revision</a>
						<?php } ?>
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
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
		    		<tr><td colspan="20" align="center"><h4 class="m-b-0"><b>PURCHASE ORDER</b></h4><small class="text-red">CHANGE ORDER FORM</small></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no']."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : ""); ?></b></h6></td>
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
					<tr>
		    			<td colspan="" class="all-border" align="center"><b>#</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>Old Qty</b></td>
		    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
		    			<td colspan="11" class="all-border" align="center"><b>Description</b></td>
		    			<td colspan="2" class="all-border" align="center"><b>OLD U/P</b></td>
		    			<td colspan="3" class="all-border" align="center"></td>
		    		</tr>
		    		<?php
		    		$x=1; 
		    		foreach($items AS $it){ 
		    			$gtotal[] = $it->amount;

		    			if(!empty($it->offer)){
		    				 $offer = $it->offer;
	    				} else {
	    					$offer= $ci->get_name("item_name", "item", "item_id = '$it->item_id'");
	    				} ?>
		    		<tr>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $x; ?></b></td>
		    			<td colspan="2" class="bor-right v-align" align="center"><b><?php echo number_format($it->delivered_quantity,2); ?></b></td>
		    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $it->uom; ?></b></td>
		    			<td colspan="11" class="bor-right v-align" align="left"><b class="nomarg"><?php echo (!empty($ci->get_pn($it->pr_details_id))) ? $offer.", ".$ci->get_pn($it->pr_details_id) : $offer; ?></b></td>
		    			<td colspan="2" class="bor-right v-align" align="center"><b><?php echo number_format($it->unit_price,4); ?></b></td>
		    			<td colspan="3" class="bor-right v-align" align="right"><b class="nomarg"><?php echo number_format($it->amount,2); ?></b></td>		
		    		</tr>	
		    		<?php 
		    		$x++; } ?>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="11" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="3" class=" bor-right" align="center"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="11" class=" bor-right" align="right">Shipping Cost</td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="3" class=" bor-right" align="right"><b class="nomarg"><?php echo number_format($shipping,2); ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="11" class=" bor-right" align="right">Packing and Handling Fee</td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="3" class=" bor-right" align="right"><b class="nomarg"><?php echo number_format($packing,2); ?></b></td>
		    		</tr>
		    		<?php if($vat_percent!=0){ ?>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="11" class=" bor-right" align="right"><?php echo $vat_percent; ?>% VAT</td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="3" class=" bor-right" align="right"><b class="nomarg"><?php echo number_format($vat,2); ?></b></td>
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="2" class=" bor-right" align="center"></td>
		    			<td colspan="" class=" bor-right" align="center"></td>
		    			<td colspan="11" class=" bor-right" align="right">Less: Discount</td>
		    			<td colspan="2" class=" bor-right" align="center"><br></td>
		    			<td colspan="3" class=" bor-right" align="right"><b class="nomarg"><?php echo number_format($discount,2); ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="" class="bor-btm bor-right" align="center"></td>
		    			<td colspan="11" class="bor-btm bor-right" align="left">
		    				<?php foreach($allpr AS $pr){ ?>
		    				<p class="nomarg">
		    					Enduse: <?php echo $pr['enduse']; ?><br>
		    					Purpose: <?php echo $pr['purpose']; ?><br>
		    					Requestor: <?php echo $pr['requestor']; ?><br>
		    					PR no.: <?php echo $pr['pr_no']."-".COMPANY; ?><br>
		    				</p><br>
		    				<?php } ?>
		    			</td>
		    			<td colspan="2" class="bor-btm bor-right" align="center"><br></td>
		    			<td colspan="3" class="bor-btm bor-right" align="center"></td>
		    		</tr>		
		    			<?php $grtotal =array_sum($gtotal);
		    		$grandtotal = ($grtotal+$shipping+$packing+$vat)-$discount;
		    		?>    		
		    		<tr>
		    			<td colspan="17" class="all-border " align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    <td colspan="3" class="all-border " align="right"><b class="nomarg"><span class="pull-left"><?php echo $currency; ?></span><?php echo number_format($grandtotal,2); ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20">
		    				<br>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td  colspan="20">
		    				<div class="newdata">
		    					<table width="100%">
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
						    			<td colspan="" class="all-border yellow-back" align="center"><b>#</b></td>
						    			<td colspan="2" class="all-border yellow-back" align="center"><b>New Qty</b></td>
						    			<td colspan="" class="all-border yellow-back" align="center"><b>Unit</b></td>
						    			<td colspan="11" class="all-border yellow-back" align="center"><b>New Description</b></td>
						    			<td colspan="2" class="all-border yellow-back" align="center"><b>New U/P</b></td>
						    			<td colspan="3" class="all-border yellow-back" align="center"><b>Total</b></td>
						    		</tr>

						    		<?php
						    		$x=1; 
						    		if($revised==0){
							    		if(!empty($items)){
								    		foreach($items AS $it){ 
								    			$gtotal2[] = $it->amount;

								    			if(!empty($it->offer)){
								    				 $offer = $it->offer;
							    				} else {
							    					$offer= $ci->get_name("item_name", "item", "item_id = '$it->item_id'");
							    				} ?>
								    		<tr>
								    			<td colspan="" class="bor-right v-align" align="center"><b><?php echo $x; ?></b></td>
								    			<td colspan="2" class="bor-right v-align" align="center"><input type='text' name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' value='<?php echo $it->delivered_quantity; ?>' style='width:100%; color:red' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"></td>
								    			<td colspan="" class="bor-right v-align" align="center"><b><input type='text' name='uom<?php echo $x; ?>' id='uom<?php echo $x; ?>' class='uom' value="<?php echo $it->uom; ?>" style='width:100%;'></b></td>
								    			<td colspan="11" class="bor-right v-align" align="left"><textarea style='width:100%' name='offer<?php echo $x; ?>'><?php echo $offer; ?></textarea></td>
								    			<td colspan="2" class="bor-right v-align" align="center"><input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' value='<?php echo number_format($it->unit_price,4); ?>' onkeyup='changePrice(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)" style='color:red; width:100px' ></td>
								    			<td colspan="3" class="bor-right v-align" align="right"><input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="<?php echo number_format($it->amount,2); ?>" style='text-align:right;' readonly></td>		
								    		</tr>	
								    			<?php 
									    		$x++; 
								    			}	
							    			
							    		} else {
							    			$gtotal2=array();
							    		} 
							    	} else {

							    		if(!empty($items)){
								    		foreach($items_temp AS $it){ 
								    			$gtotal2[] = $it->amount;

								    			if(!empty($it->offer)){
								    				 $offer = $it->offer;
							    				} else {
							    					$offer= $ci->get_name("item_name", "item", "item_id = '$it->item_id'");
							    				} ?>
								    		<tr>
								    			<td colspan="" class="bor-right" align="center"><b><?php echo $x; ?></b></td>
								    			<td colspan="2" class="bor-right" align="center"><?php echo $it->delivered_quantity; ?></td>
								    			<td colspan="" class="bor-right" align="center"><b><?php echo $it->uom; ?></b></td>
								    			<td colspan="11" class="bor-right" align="left"><?php echo $offer; ?></td>
								    			<td colspan="2" class="bor-right" align="center"><?php echo number_format($it->unit_price,4); ?></td>
								    			<td colspan="3" class="bor-right" align="right"><?php echo number_format($it->amount,2); ?></td>		
								    		</tr>	
								    			<?php 
									    		$x++; 
								    			}	
							    			
							    		} else {
							    			$gtotal2=array();
							    		} 
							    	} ?>
						    		<input type='hidden' name='count_item' value="<?php echo $x; ?>">
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"><br></td>
						    			<td colspan="3" class=" bor-right" align="center"></td>
						    		</tr>
						    		<?php if($revised==0){ ?>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Shipping Cost</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='shipping' id='shipping' value='<?php echo $shipping; ?>' onchange='additionalCost()' style='width:100%' ></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Packing and Handling Fee</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='packing' id='packing' onchange='additionalCost()' value='<?php echo $packing; ?>' style='width:100%' ></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right"><input name="vat_percent" id="vat_percent" value = "<?php echo $vat_percent; ?>" size="5">% VAT</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='vat' id='vat' onchange='additionalCost()' value='<?php echo $vat; ?>' style='width:100%' ></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Less: Discount</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="center"><input type='text' name='discount' id='discount' onchange='additionalCost()' value='<?php echo $discount; ?>' style='width:100%' ></td>
						    		</tr>
						    	<?php } else { ?>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Shipping Cost</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="right"><?php echo number_format($shipping_temp,2); ?></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Packing and Handling Fee</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="right"><?php echo number_format($packing_temp,2); ?></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right"><?php echo $vat_percent_temp; ?>% VAT</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="right"><?php echo number_format($vat_temp,2); ?></td>
						    		</tr>
						    		<tr>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="" class=" bor-right" align="center"></td>
						    			<td colspan="11" class=" bor-right" align="right">Less: Discount</td>
						    			<td colspan="2" class=" bor-right" align="center"></td>
						    			<td colspan="3" class=" bor-right" align="right"><?php echo number_format($discount_temp,2); ?></td>
						    		</tr>
						    	<?php } ?>
						    		<tr>
						    			<td colspan="" class="bor-btm bor-right" align="center"></td>
						    			<td colspan="2" class="bor-btm bor-right" align="center"></td>
						    			<td colspan="" class="bor-btm bor-right" align="center"></td>
						    			<td colspan="11" class="bor-btm bor-right" align="left">
						    				<?php foreach($allpr AS $pr){ ?>
						    				<p class="nomarg">
						    					Enduse: <?php echo $pr['enduse']; ?><br>
						    					Purpose: <?php echo $pr['purpose']; ?><br>
						    					Requestor: <?php echo $pr['requestor']; ?><br>
						    					PR no.: <?php echo $pr['pr_no']."-".COMPANY; ?><br>
						    				</p><br>
						    				<?php } ?>
						    				<br>
						    			</td>
						    			<td colspan="2" class="bor-btm bor-right" align="center"><br></td>
						    			<td colspan="3" class="bor-btm bor-right" align="center"></td>
						    		</tr>
						    		<?php 
						    			$grtotal2 =array_sum($gtotal2);
						    			if($revised==0){
						    				$grandtotal2 = ($grtotal2+$shipping+$packing+$vat)-$discount;
						    			}else {
						    				$grandtotal2 = ($grtotal2+$shipping_temp+$packing_temp+$vat_temp)-$discount_temp;
						    			}
						    		?>    			
						    		<input type='hidden' id='orig_amount' value='<?php echo $grtotal2; ?>'>
						    		<tr>
						    			<td colspan="17" class="all-border yellow-back" align="right"><b class="nomarg">GRAND TOTAL</b></td>
									    <td colspan="3" class="all-border yellow-back" align="right"><b class="nomarg"><span class="pull-left"><?php echo $currency_temp; ?></span><span id='grandtotal'><?php echo number_format($grandtotal2,2); ?></span></b></td>
						    		</tr>
						    		<tr>
						    			<td colspan="20">
						    				<i></i>
						    			</td>
						    		</tr>
		    					</table>
		    				</div>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($revised==0){ ?>
			    				<button type="button" class="btn btn-primary btn-xs " data-toggle="modal" data-target="#otherins">
									Add Other Instruction
								</button><br> 
								<?php $xs = 1; foreach($tc AS $t){ if(!empty($t->notes)){ ?>
									<textarea type = "text" style='color:blue;width:92%' rows = '1' class="form-control" name = "notes<?php echo $xs; ?>"><?php echo $t->notes; ?></textarea><!-- <span style = "color:blue;"><?php echo $t->notes;?></span> --><?php } $xs++; } ?>
							<?php }else{ ?>
								Other Instructions:<br><?php foreach($tc_temp AS $t){ ?><span style = "color:blue;"><?php echo nl2br($t->notes)."<br>";?></span><?php } ?>

							<?php } ?>
			    		</td>
			    	</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($revised==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs " data-toggle="modal" data-target="#exampleModal">
							 Add Terms & Conditions
							</button>
							<?php } ?>
		    				<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
		    				<?php if($po_type!=1){ $x=4; ?>
		    				<?php if(!empty($payment_terms)){ 
		    				echo $x."."; ?> Payment term: <?php echo $payment_terms; ?><br>
		    				<?php $x++; } ?>	
		    				<?php if(!empty($item_warranty)){ 
		    				echo $x."."; ?> Item Warranty: <?php echo $item_warranty; ?><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($delivery_time)){ 
		    				echo $x."."; ?> Delivery Time: <?php echo $delivery_time; ?><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($freight)){ 
		    				echo $x."."; ?> In-land Freight: <?php echo $freight; ?><br>
		    				<?php $x++; } } ?>
		    				<?php 
		    					//$no=8;
		    				
		    					$y = 1;
		    					$x = 4;
		    					if($revised==0){
		    						foreach($tc AS $t){ 
		    							if(!empty($t->tc_desc)){
			    						//echo $no.". " . $t->tc_desc."<br>";
			    						//$no++; 
			    			?>
			    				<?php echo $x.". "; ?><input type = "text" style='color:red;width: 90%' name = "terms<?php echo $y; ?>" value = "<?php echo $t->tc_desc; ?>"><br>
			    			<?php
				    					}
				    					$x++;
				    					$y++;
			    					} 
		    					}else { 
		    						foreach($tc AS $t){ 
			    						if(!empty($t->tc_desc)){
				    						echo $x.". " . $t->tc_desc."<br>";
				    						$x++; 
				    					}
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
		    			<td colspan="4"><b><?php echo $checked;?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="4"><b><?php echo $recommended; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="3"><b><?php echo $approved; ?></b></td>
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
					<form method="POST" action="<?php echo base_url(); ?>po/add_tc_temp">
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
					<form method="POST" action="<?php echo base_url(); ?>po/add_otherins_temp">
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