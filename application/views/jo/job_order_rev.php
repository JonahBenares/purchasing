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
				<form method='POST' action='<?php echo base_url(); ?>jo/create_jo_details_temp'>
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
					<input type="hidden" name='jo_id' value="<?php echo $jo_id; ?>">
				</form>
			</div>
		</div>
	</div>

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
                <form method='POST' action="<?php echo base_url(); ?>jo/approve_revision">
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
                        <input type="hidden" name="jo_id" id="jo_id" >
                        <input type='submit' value='Approve' class="btn btn-custon-three btn-primary btn-block">
                    </div>
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
				<form method='POST' action='<?php echo base_url(); ?>jo/create_jo_terms_temp'>
					<div class="modal-body">
						<div class="form-group">
							<p style="font-size: 14px" class="nomarg">Terms & Conditions:</p>
							<textarea class="form-control" name='terms' rows="3"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary btn-block" value="Add">
					</div>
					<input type="hidden" name='jo_id' value="<?php echo $jo_id; ?>">
				</form>
			</div>
		</div>
	</div>
<body onload="changePrice()">
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>jo/save_change_order'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>jo/jo_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<?php if($revised==0){ ?>
							<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" value="Save & Print Change Order Form">	
						<?php } else { ?>
							 <a class="btn btn-custon-three btn-info btn-md approverev" title='Aprrove Revision' data-toggle="modal" data-target="#approve" data-id="<?php echo $jo_id; ?>"><span class="fa fa-thumbs-up"></span> Approve Revision</a>
						<?php } ?>
						<!-- <a  href="<?php echo base_url(); ?>jo/jo_rfd/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AC</b></a> -->
						
					</div>
					<h4 class="text-white"><b>OUTGOING</b> RFQ <b>For JOB ORDER</b></h4>
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
		    		<tr><td colspan="20" align="center"><h4><b>JOB ORDER</b></h4><small class="text-red">CHANGE ORDER FORM</small></td></tr>
		    		<?php if($revised==0){ ?>
		    		<tr>
		    			<td class="f13" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<select name='vendor' id='vendor' onchange="chooseVendor()" class='form-control'>
                                <option value=''>-Select Vendor-</option>
                                 <?php foreach($supplier AS $sup){ ?>
                                    <option value="<?php echo $sup->vendor_id; ?>" <?php echo (($vendor_id == $sup->vendor_id) ? ' selected' : '');?>><?php echo $sup->vendor_name; ?></option>
                                <?php } ?>        
                            </select>
                            <span id='contact_person'><?php echo $contact_person; ?></span><br>
                            <span id='address'><?php echo $address; ?></span><br>
		    				<span id='phone'><?php echo $phone; ?></span><br>
		    				<span id='fax'><?php echo $fax; ?></span><br>
		    				<!-- <b><?php echo $vendor; ?></b><br>
		    				<span id='address'><?php echo $address; ?></span><br>
		    				<span id='phone'><?php echo $phone; ?></span><br> -->
		    				<br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="4">Date Needed:</td>
		    			<td class="f13 bor-btm" colspan="7"><input type="date" name="date_needed" value="<?php echo $date_needed;?>" style = "width: 100%"></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="5"><input type="date" name="work_completion" value="<?php echo $work_completion;?>" style = "width: 100%"></td>
		    		</tr>

		    		<tr>
		    			<td class="f13" colspan="4">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7"><input type="date" name="date_prepared" value="<?php echo $date_prepared;?>" style = "width: 100%"></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JO No.:</td>
		    			<td class="f13 bor-btm" colspan="5"><b><input type="text" name="cenjo_no" value="<?php echo $cenjo_no; ?>" style = "width: 100%"></b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="4">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><input type="date" name="start_of_work" value="<?php echo $start_of_work;?>" style = "width: 100%"></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO. No:</td>
		    			<td class="f13 bor-btm" colspan="5"><input type="text" name="jo_no" value="<?php echo $jo_no; ?>" style = "width: 100%" readonly></td>
		    		</tr>	
		    		<?php }else { ?>
		    		<tr>
		    			<td class="f13" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<b><?php echo $vendor; ?></b><br>
		    				<span id='contact_person'><?php echo $contact_person; ?></span><br>
		    				<span id='address'><?php echo $address; ?></span><br>
		    				<span id='phone'><?php echo $phone; ?></span><br>
		    				<span id='fax'><?php echo $fax; ?></span><br>
		    				<br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="4">Date Needed:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_needed));?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="5"><?php echo date('F j, Y', strtotime($work_completion));?></td>
		    		</tr>

		    		<tr>
		    			<td class="f13" colspan="4">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_prepared));?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JO No.:</td>
		    			<td class="f13 bor-btm" colspan="5"><b><?php echo $cenjo_no; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="4">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($start_of_work));?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO. No:</td>
		    			<td class="f13 bor-btm" colspan="5"><?php echo $jo_no."-".COMPANY; ?></td>
		    		</tr>
		    		<?php } ?>
		    		<!-- <tr>
		    			<td class="f13" colspan="4">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date("F d, Y",strtotime($work_completion));?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13" colspan="5"></td>
		    		</tr> -->			    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    
		    		<?php if($revised==0){ ?>		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px"><b><input type="text" name="project_title" value="<?php echo $project_title; ?>" style = "width: 100%;text-align: center"></b></h5>
			    		</td>
		    		</tr>
			    	<?php } else { ?>
			    	<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px"><b><?php echo $project_title; ?></b></h5>
			    		</td>
		    		</tr>
			    	<?php } ?>
		    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<?php if($revised==0){ ?>	 
		    		<tr>
		    			<td class="f13  p-l-5" colspan="20" align="left">
			    			<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addscope">
							  <span class="fa fa-plus"></span> Add Scope
							</button>		    			
			    		</td>
			    	</tr>   
			    	<?php } ?>		
		    		<tr>
		    		
		    			<td colspan="20">
		    				<table class="table-borsdered" width="100%">
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Scope of Work:</b></td>
		    						<td width="10%" class="f13" align="center"><b>Qty</b></td>
		    						<td width="5%" class="f13" align="center"><b>UM</b></td>
		    						<td width="15%" class="f13" align="center"><b>Unit Cost</b></td>
		    						<td width="15%" class="f13" align="center"><b>Total Cost</b></td>
		    						<td width="15%" class="f13" align="center"></td>
		    					</tr>
		    					<?php 
		    						$x=1; 
		    						if($revised==0){
		    							if(!empty($details)){
			    							foreach($details AS $det){ 
			    								$gtotal2[] = $det->total_cost;
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><textarea name = "scope_of_work<?php echo $x; ?>" class = "form-control"><?php echo $det->scope_of_work; ?></textarea></td>

		    						<td class="f13" align="center"><input type="text" name="quantity<?php echo $x; ?>" id='quantity<?php echo $x; ?>' style = "width:50%;text-align: center" value = "<?php echo $det->quantity; ?>" onblur='changePrice_JO(<?php echo $x; ?>)'></td>
		    						<td class="f13" align="center"><input type="text" name="uom<?php echo $x; ?>" style = "width:100%;text-align: center" value = "<?php echo $det->uom; ?>"></td>
		    						<td class="f13" align="center"><input type="text" name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' style = "width:100%;text-align: center" value = "<?php echo $det->unit_cost; ?>" onblur='changePrice_JO(<?php echo $x; ?>)'></td>
		    						<td class="f13" align="right"><input type="text" name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' style = "width:100%;text-align: center" class='tprice' value = "<?php echo $det->total_cost; ?>"></td>
		    						<td class="f13" align="right"><a href="<?php echo base_url(); ?>jo/delete_scope/<?php echo $det->jo_details_id?>/<?php echo $det->jo_id?>" class="btn btn-danger btn-xs" style = "text-align: center"><span class="fa fa-times"></span></a></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<?php $x++; } }else { $gtotal2=array(); } }else {
		    						if(!empty($details)){
		    						foreach($details_temp AS $det){ 
			    						$gtotal2[] = $det->total_cost;
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><?php echo nl2br($det->scope_of_work); ?></td>
		    						<td class="f13" align="center"><?php echo $det->quantity; ?></td>
		    						<td class="f13" align="center"><?php echo $det->uom; ?></td>
		    						<td class="f13" align="center"><?php echo $det->unit_cost; ?></td>
		    						<td class="f13" align="right"><?php echo $det->total_cost; ?></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<?php } }else { $gtotal2=array(); } } ?>
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="
		    						<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    					</tr>
		    					<?php $sum_cost = array_sum($gtotal2);
		    					$subtotal= $sum_cost + $vat_amount; ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Amount:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="sum_cost" id='sum_cost' value="<?php echo $sum_cost; ?>" readonly="readonly"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>VAT %:</td>
		    						<td><input class="nobord" type="text" placeholder="0%" name="vat_percent" id='vat_percent' onblur='changePrice()' value="<?php echo number_format($vat_percent); ?>"></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="vat_amount" id='vat_amount' readonly="readonly" value="<?php echo $vat_amount; ?>"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Subtotal:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="subtotal" id='subtotal' readonly="readonly" value="<?php echo $subtotal; ;?>"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Less Discount:</td>
		    						
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="less_amount" id='less_amount' value="<?php echo $discount_amount; ?>" onblur='changePrice()'></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>GRAND TOTAL:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="net" id='net' value="<?php echo $grand_total; ?>" readonly="readonly"></td>
		    					</tr>
		    				
		    					<!-- <tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'><?php echo number_format($vat_percent) . "% VAT" ?>:</td>
		    						<td align="right"><?php echo number_format($vat_amount,2); ?></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Less Discount:</td>
		    						<td align="right"><?php echo number_format($discount_amount,2); ?></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>Net</td>
		    						<td></td>
		    						<td align="right" ><span id='grandtotal'><?php echo number_format(array_sum($gtotal2),2); ?></span></td>
		    					</tr> -->
		    				</table>
		    			</td>
		    		</tr>
		    		<?php if($revised==0){ ?>	
		    		<tr>
		    			<td class="f13  p-l-5" colspan="20" align="left">
			    			<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addterms">
							  <span class="fa fa-plus"></span> Terms & Conditions:
							</button>		    			
			    		</td>
			    	</tr>
			    	<?php } ?>
		    		<tr>
		    			<td class="f13" colspan="11" align="left" style="padding-left: 5px">
		    				<b>Terms and Conditions:</b><br>
		    				<?php 
		    					$y = 1;
		    					if($revised==0){
		    					foreach($terms AS $trm){ 
		    					//echo nl2br($trm->terms);
		    				?>
		    				<textarea name = "termsc<?php echo $y; ?>" class = "form-control"><?php echo $trm->terms; ?></textarea>
		    				<?php 
		    					$y++; } } else { 
			    					foreach($terms_temp AS $trm){
			    						echo nl2br($trm->terms)."<br>"; 
			    					} 
		    					} 
		    				?>
		    			</td>
		    			<td colspan="9"></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Total Project Cost:</td>
		    			<td class="f13 bor-btm" colspan="7" align="right"><h4 style="margin: 0px"><b><span id='gtotal'><?php echo number_format($grand_total,2); ?></span></b></h4></td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Conforme:</td>
		    			<td class="f13 bor-btm" colspan="7" align="center"><?php if($revised==0){ ?><input type="text" name='conforme' style = "width:100%;" value = "<?php echo $conforme; ?>"><?php } else{ echo $conforme; } ?></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="6"></td>
		    			<td class="f13" colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13" colspan="7" align="center">Supplier's Signature Over Printed Name</td>
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
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="3" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<?php echo $prepared; ?>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<!-- <?php echo $checked; ?> -->
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="checked_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($checked_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $checked; }?>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<!-- <?php echo $approved;?> -->
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="recommended_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($recommended_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $recommended; } ?>

		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="3" align="center">
		    				<!-- <?php echo $approved;?> -->
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="approved_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($approved_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $approved; } ?>
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
		    			<td class="f13  bor-btm" colspan="4" align="center"><br></td>
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
		    				<select type="text" name="verified_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"  <?php echo (($verified_by == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
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
	    	<input type='hidden' name='jo_id' value="<?php echo $jo_id; ?>">
    	</form>
    </div>
   </td>
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