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
	        	background-image: url('../../assets/img/cancel.png')!important;
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
				<form method='POST' action='<?php echo base_url(); ?>jod/create_jo_details'>
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
				<form method='POST' action='<?php echo base_url(); ?>jod/create_jod_terms'>
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
					<input type="hidden" name='jor_id' value="<?php echo $jor_id; ?>">
					<input type="hidden" name='group_id' value="<?php echo $group_id; ?>">
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
					<form method="POST" action="<?php echo base_url(); ?>jod/update_condition">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<textarea type="text" class="form-control" name="condition" autocomplete="off" id = "terms"></textarea>
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
    	<?php 
    	if($revised=='r'){
    		$url=base_url().'jod/save_jod_revised';
    	} else {
    		$url=base_url().'jod/save_jod';
    	}
    	?>
    	<form method='POST' action='<?php echo $url; ?>'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>joi/joi_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==1){ ?>
							<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<?php } else { 
							if($revised=='r'){ ?>
							<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save Revision">	
						<?php } else { ?>
							<input type='submit' class="btn btn-warning btn-md p-l-100 p-r-100" name='submit' value="Save as Draft">	
							<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" name='submit' value="Save">	
						<?php } } ?>
						<!-- <a href="<?php echo base_url(); ?>joi/jo_issuance_saved" class="btn btn-primary btn-md p-l-25 p-r-25">Save</a> -->
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>jo/jo_rfd" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AR</b></a> -->
						<!-- <input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" value="Save">  	 -->
					</div>
					<h4 class="text-white">JOB ORDER ISSUANCE</b></h4>
					<p class="text-white">Instructions: When printing JOB ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
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
		    	<table class="table-bdordered" width="100%" style="border:2px solid #000">
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
		    		<tr><td colspan="20" align="center"><h4><b>JOB ORDER</b></h4><small>D I R E C T</small></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<b><?php echo $h['vendor'];?></b><br>
		    				<span id='contact_person'><?php echo $h['contact'];?></span><br>
		    				<span id='address'><?php echo $h['address'];?></span><br>
		    				<span id='phone'><?php echo $h['phone'];?></span><br>
		    				<span id='fax'><?php echo $h['fax'];?></span><br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Date Needed:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['date_needed']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13 " colspan="3">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo $h['completion_date']; ?></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['date_prepared']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JOR No.:</td>
		    			<td class="f13 bor-btm" colspan="6"><b><?php echo $h['cenpri_jo_no']; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo $h['start_of_work']; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO No.:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo $h['joi_no']."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : "");?></td>
		    		</tr>		    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px; text-transform: uppercase;"><b><?php echo $h['project_title'];?></b></h5>
			    		</td>
		    		</tr>
		    		<?php } ?>	
		    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		    		
		    		<!-- <tr>
		    			<td class="f13  p-l-5" colspan="20" align="left">
			    			<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addscope">
							  <span class="fa fa-plus"></span> Add Scope
							</button>		    			
			    		</td>
			    	</tr> -->		    		
		    		<tr>
		    			<td colspan="20">
		    				<table class="table-bordsered" width="100%">
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Scope of Work:</b></td>
		    						<td width="5%" class="f13" align="center"><b>Qty</b></td>
		    						<td width="5%" class="f13" align="center"><b>UM</b></td>
		    						<td width="5%" class="f13" align="center"><b>Currency</b></td>
		    						<td width="15%" class="f13" align="center"><b>Unit Cost</b></td>
		    						<td width="15%" class="f13" align="center"><b>Total Cost</b></td>
		    					</tr>
		    					<tr>
                                    <td class="f13 p-l-5" align="left"><b><?php echo $h['general_desc']; ?></b></td>
                                </tr>
                                <!--ITEM-->
		    					<?php 
		    						$gtotal=array();
		    						$mattotal=array();
		    						if(!empty($items)){
		    							$x=1;
			    						foreach($items AS $it){ 
			    							if($saved==1){
					    						$gtotal[] = $it['total'];
					    						$mattotal[] = $it['materials_amount'];
					    					}
		    					?>
		    					<tr>
		    						<td class="f13 p-l-5" align="left">
		    							<b class="nomarg"><textarea rows="4" style="width:100%" name='offer<?php echo $x; ?>'> <?php echo $it['offer']; ?></textarea></b>
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top">
		    							<b>
		    								<!-- <input type="text" name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' onblur="this.value = minmax(this.value, 0, <?php echo $it['quantity']; ?>)" value='<?php echo $it['quantity']; ?>' style='width:50px; color:red;text-align: center' onchange='changePrice_JO(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"/> -->
		    								<input type="text" name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' value='<?php echo $it['quantity']; ?>' style='width:50px; color:red;text-align: center' onchange='changePrice_JO(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"/>
		    							</b>
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top"><?php echo $it['uom']; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top">
				    				<select name='currency<?php echo $x; ?>'>
						    			<?php foreach($currency AS $curr){ ?>
						    		<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    		<?php } ?>
						    		</select>
				    				</td>
		    						<td class="f13" align="center" style="vertical-align:top">
		    							<b>
		    								<?php if($saved==0) { ?>
		    								<input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' onkeyup='changePrice_JO(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)" style='color:red;width: 100%;text-align: center'>
			    							<?php } else { echo $it['price']; } ?>
		    							</b>
		    						</td>
		    						<td class="f13" align="right" style="vertical-align:top">
		    							<b class="nomarg">
		    								<?php if($saved==0){ ?>
		    									<input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' style='text-align:right;width: 100%' readonly>
		    								<?php }else { echo number_format($it['total'],2); } ?>
		    							</b>
		    						</td>
		    					</tr>
		    					<!-- <input type='text' name='currency<?php echo $x; ?>' value="<?php echo $it['currency']; ?>"> -->
					    		<input type='hidden' name='jor_items_id<?php echo $x; ?>' value="<?php echo $it['jor_items_id']; ?>">
					    		<input type='hidden' name='uom<?php echo $x; ?>' value="<?php echo $it['uom']; ?>">
		    					<?php  $x++; } ?> 
		    					<input type='hidden' name='count_item' value="<?php echo $x; ?>">
		    					<?php }else{ $gtotal=array(); $mattotal=array(); } ?>
		    					<!--ITEM-->
		    					<tr>
		    						<td colspan="6"><br> <b>&nbsp;&nbsp;Materials:</b></td>
		    					</tr>
		    					<!--MATERIAL-->
		    					<tr>
		    						<td class="f13 p-l-5" align="left">
		    							<b class="nomarg"><textarea rows="4" style="width:100%" name='materials_offer'></textarea></b>
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top">
		    							<b>	
		    								<input type="text" name='materials_qty' id='materials_qty' class='materials_qty' style='width:50px; color:red;text-align: center' onchange='changesinglePrice_JO()' onkeypress="return isNumberKey(this, event)"/>
		    							</b>
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top"><input type='text' name='materials_uom' style='width:50px; color:red;text-align: center'></td>
		    						<td class="f13" align="center" style="vertical-align:top">
				    				<select name='materials_currency'>
						    			<?php foreach($currency AS $curr){ ?>
						    				<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    			<?php } ?>
						    		</select>
				    				</td>
		    						<td class="f13" align="center" style="vertical-align:top">
		    							<b>
		    								<input type='text' name='materials_price' id='materials_price' onkeyup='changesinglePrice_JO()' onkeypress="return isNumberKey(this, event)" style='color:red; width:161px;text-align: center'>
		    							</b>
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top">
		    							<b class="nomarg">
		    								<input type='text' name='materials_tprice' id='materials_tprice' class='materials_tprice' style='text-align:right;' readonly>
		    							</b>
		    						</td>
		    					</tr>
		    					<!--MATERIAL-->
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
		    					<?php $y=1; foreach($notes AS $n){ ?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<?php echo nl2br($n->notes); ?><br><br>
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<input type='hidden' name='jor_notes<?php echo $y; ?>' value="<?php echo $n->notes; ?>">
		    					<?php $y++; } ?>
		    					<input type='hidden' name='count_notes' value="<?php echo $y; ?>">
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    					</tr>
		    					<tr>
		    						<td colspan='4' ></td>
		    						<td align="right">Total Labor:</td>
		    						<td class="bor-btm" align="right">
		    							<?php if($saved==0){ ?>
		    								<span ><input type='text' class="nobord" name='sum_cost' id='sum_cost' class='sum_cost' style='text-align:right;width: 100%;' readonly >
		    							<?php }else { echo number_format($it['total'],2); } ?>
		    						</td>
		    					</tr>
		    					<tr>
		    						<td colspan='4' ></td>
		    						<td align="right">Total Materials:</td>
		    						<td class="bor-btm" align="right">
		    							<?php if($saved==0){ ?>
		    								<span ><input type='text' class="nobord" name='mat_sum_cost' id='mat_sum_cost' class='mat_sum_cost' style='text-align:right;width: 100%;' readonly >
		    							<?php }else { echo number_format($it['materials_amount'],2); } ?>
		    						</td>
		    					</tr>
		    					<tr>
		    						<td colspan="4"></td>
		    						<td  align="right">VAT %: <input style="text-align: center; border-bottom:1px solid #000 ;width:60px" class="nobord" type="text" placeholder="0%" name="vat_percent" id='vat_percent' onblur='changePrice()'>
		    						</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="vat_amount" id='vat_amount' readonly="readonly" style='text-align:right;width: 100%;'></td>
		    					</tr>
		    					<tr>
		    						<td colspan="4" ></td>
		    						<td align="right">Subtotal:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="subtotal" id='subtotal' readonly="readonly" style='text-align:right;width: 100%;'></td>
		    					</tr>
		    					<tr>
		    						<td colspan="4" ></td>
		    						<td align="right">Less Discount:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="less_amount" id='less_amount'  onblur='changePrice()' style='text-align:right;width: 100%;'></td>
		    					</tr>
		    					<tr>
		    						<td colspan="4" ></td>
		    						<td align="right">GRAND TOTAL:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="net" id='net' value="" readonly="readonly" style='text-align:right;width: 100%;'></td>
		    					</tr>
		    				
		    					
		    				</table>
		    			</td>
		    		</tr>
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<button type="button" class="btn btn-primary btn-xs prnt" data-toggle="modal" data-target="#addterms">
							 Add Terms & Conditions
							</button>
							<?php $x=3; ?>
		    				<br>Terms & Conditions:<br>
		    				1. JO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				2. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>		    				 
                            3. Price is 
                            	<select type="text" name="vat_in_ex">
                                    <option value = "0">inclusive of VAT</option>
                                    <option value = "1">exclusive of VAT</option>
                                </select>		    				
                            <br>
			    			<?php $x=4; ?>
		    				<?php if(!empty($payment_terms)){ 
		    				echo $x."."; ?> Payment term: <?php echo nl2br($payment_terms) ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>	
		    				<?php if(!empty($item_warranty)){ 
		    				echo $x."."; ?> Item Warranty: <?php echo nl2br($item_warranty); ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($delivery_time)){ 
		    				echo $x."."; ?> Work Duration: <?php echo nl2br($delivery_time); ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($freight)){ 
		    				echo $x."."; ?> In-land Freight: <?php echo nl2br($freight); ?> <button type="button" class="btn btn-primary btn-xs " data-toggle="modal" id = "prnt_btn" data-target="#Edit">
							 <span class = "fa fa-edit"></span>
							</button><br>
		    				<?php $x++; } ?>
		    				<?php 
		    					//$no=8;
		    			
		    					foreach($tc AS $t){ 
		    						if(!empty($t->tc_desc)){
			    						echo $x.". " . nl2br($t->tc_desc);
			    				?>
			    				<a class='btn btn-primary btn-xs prnt' id = "updateTerm" data-toggle='modal' data-target='#UpdateTerms' data-id = '<?php echo $t->joi_tc_id; ?>' data-name = '<?php echo $t->tc_desc; ?>'>
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
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Total Project Cost:</td>
		    			<td class="f13 bor-btm" colspan="7" align="right"><h4 style="margin: 0px"><b></span><span id='gtotal'></span></b></h4></td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>		    			
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Conforme:</td>
		    			<td class="f13 bor-btm" colspan="7"><input type="text" name="conforme" class="btn-block nobord"></td>
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
		    			<td class="f13" colspan="4" align="center">Reviewed/Checked by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center">Recommended by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="4" align="center">Approved by:</td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<?php echo $prepared; ?>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="checked_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					<?php foreach($employee AS $emp){ ?>
				    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
		    				</select>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="recommended_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					<?php foreach($employee AS $emp){ ?>
				    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
		    				</select>
		    			</td>

		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="approved_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					<?php foreach($employee AS $emp){ ?>
				    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
		    				</select>
		    			</td>
		    		</tr>  
		    		<!-- <tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Purchasing Department</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="6" align="center"><small>Personnel</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Project Director</small></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   	 -->
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>    	
		    		<tr id = "hide_work">
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
		    			<td class="f13  bor-btm" id = "border_work" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center"><br></td>
		    		</tr>   
		    		<tr id = "hide_input">
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="verified_by"  id="verified_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
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
	    	<input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
	    	<input type="hidden" name='joi_id' value="<?php echo $joi_id; ?>">
	    	<input type="hidden" name='jor_id' value="<?php echo $jor_id; ?>">
    	</form>
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
					<form method="POST" action="<?php echo base_url(); ?>jod/update_terms">
						<div class="modal-body">
							<div class="form-group">
								Payment:
								<textarea type="text" class="form-control" name="payments" autocomplete="off" value = "<?php echo $payment_terms;?>" rows='3'></textarea>
								Item Warranty:
								<textarea type="text" class="form-control" name="item_war" autocomplete="off" value = "<?php echo $item_warranty;?>" rows='3'></textarea>
								Work Duration:
								<textarea type="text" class="form-control" name="del_itm" autocomplete="off" value = "<?php echo $delivery_time;?>" rows='3'></textarea>
								Freight:
								<textarea type="text" class="form-control" name="freigh" autocomplete="off" value = "<?php echo $freight;?>" rows='3'></textarea>
							</div>
						</div>
						<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
						<input type='hidden' name='aoq_vendors_id' value='<?php echo $aoq_vendors_id; ?>'>
						<!--<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>-->

					</form>
				</div>
			</div>
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