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
                <form method='POST' action="<?php echo base_url(); ?>joi/approve_revision">
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
                        <input type="hidden" name="joi_id" id="joi_id1" >
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
				<form method='POST' action='<?php echo base_url(); ?>joi/add_tc_temp'>
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
				<form method="POST" action="<?php echo base_url(); ?>joi/update_condition_revise">
					<div class="modal-body">
						<div class="form-group">
							Terms & Conditions:
							<textarea type="text" class="form-control" name="condition" autocomplete="off" id = "terms"></textarea>
						</div>
					</div>
					<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
					<input type='hidden' name='jor_id' value='<?php echo $jor_id; ?>'>
					<input type='hidden' name='group_id' value='<?php echo $group_id; ?>'>
					<input type='hidden' name='tc_id' id = "tc_id">
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary btn-block" value="Save changes">
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="UpdateTermsTemp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Update Terms & Condition
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>
						
					</div>
					<form method="POST" action="<?php echo base_url(); ?>joi/update_condition_revise_temp">
						<div class="modal-body">
							<div class="form-group">
								Terms & Conditions:
								<input type="text" class="form-control" name="condition" autocomplete="off" id = "termstemp">
							</div>
						</div>
						<input type='hidden' name='joi_id' value='<?php echo $joi_id; ?>'>
						<input type='hidden' name='tc_id' id = "tc_id_temps">
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary btn-block" value="Save changes">
						</div>

					</form>
				</div>
			</div>
		</div>
<body onload="changePrice()">
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>joi/save_change_order'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>joi/joi_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<?php if($revised==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-50 p-r-50" value="Save & Print Change Order Form">
						<?php } else { ?>
						<a class="btn btn-custon-three btn-info btn-md approverev" title='Aprrove Revision' data-toggle="modal" data-target="#approve" data-id="<?php echo $joi_id; ?>" id="jo"><span class="fa fa-thumbs-up"></span> Approve Revision</a>
						<?php } ?>
						<!-- <a  href="<?php echo base_url(); ?>jo/jo_rfd/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac/<?php echo $jo_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AR</b></a> -->
						
					</div>
					<h4 class="text-white"><b>OUTGOING</b> RFQ <b>For JOB ORDER</b></h4>
					<p class="text-white">Instructions: When printing JOB ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" class="<?php  if($cancelled==1){ echo 'cancel'; }?>">  <!--  -->
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
			    				<!-- <select name='vendor' id='vendor' onchange="chooseVendor()" class='form-control'>
			    					<option value=''>-Select Vendor-</option>
	                                <?php foreach($supplier AS $sup){ ?>
	                                    <option value="<?php echo $sup->vendor_id; ?>" <?php echo (($vendor_id == $sup->vendor_id) ? ' selected' : '');?>><?php echo $sup->vendor_name; ?></option>
	                                <?php } ?>  
	                            </select> -->
	                            <?php foreach($head AS $h){ ?>
	                            <b><?php echo $h['vendor']; ?></b><br>
	                            <span id='contact_person'><?php echo $h['contact_person']; ?></span><br>
	                            <span id='address'><?php echo $h['address']; ?></span><br>
			    				<span id='phone'><?php echo $h['phone']; ?></span><br>
			    				<span id='fax'><?php echo $h['fax']; ?></span><br>
			    				<input type="hidden" name="vendor" value="<?php echo $h['vendor_id'];?>" style = "width: 100%">
			    				<?php } ?>
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
			    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JOR No.:</td>
			    			<td class="f13 bor-btm" colspan="5"><b><input type="text" name="cenjo_no" value="<?php echo $cenjo_no;?>" style = "width: 100%"></b></td>
			    		</tr>
			    		<tr>
			    			<td class="f13" colspan="4">Start of Work:</td>
			    			<td class="f13 bor-btm" colspan="7"><input type="date" name="start_of_work" value="<?php echo $start_of_work;?>" style = "width: 100%"></td>
			    			<td class="f13" colspan="1"></td>
			    			<td class="f13" colspan="3">JOI No.:</td>
			    			<td class="f13 bor-btm" colspan="5"><input type="text" name="joi_no" value="<?php echo $joi_no;?>" style = "width: 100%" readonly></td>
			    		</tr>	    			    		
			    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	 
				    	<tr>
			    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
				    			<h5 style="margin: 5px"><b><textarea name="project_title" rows="2" style = "width: 100%;text-align: center"><?php echo $project_title;?></textarea></b></h5>
				    		</td>
			    		</tr>
			    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
			    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>  
		    			<?php }else{ ?>
		    			<tr>
			    			<td class="f13" colspan="3" style="vertical-align:top">TO:</td>
			    			<td class="f13" colspan="10" align="left">
			    				<?php foreach($head AS $h){ ?>
	                            <b><?php echo $h['vendor']; ?></b><br>
	                            <span id='contact_person'><?php echo $h['contact_person']; ?></span><br>
	                            <span id='address'><?php echo $h['address']; ?></span><br>
			    				<span id='phone'><?php echo $h['phone']; ?></span><br>
			    				<span id='fax'><?php echo $h['fax']; ?></span><br>
			    				<input type="hidden" name="vendor" value="<?php echo $h['vendor_id'];?>" style = "width: 100%">
			    				<?php } ?>
			    				<br>
			    			</td>
			    			<td colspan="7"></td>
			    		</tr>
			    		<tr>
			    			<td class="f13" colspan="4">Date Needed:</td>
			    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_needed_temp));?></td>
			    			<td class="f13" colspan="1"></td>
			    			<td class="f13" colspan="3">Completion of Work:</td>
			    			<td class="f13 bor-btm" colspan="5"><?php echo date('F j, Y', strtotime($completion_date_temp));?></td>
			    		</tr>

			    		<tr>
			    			<td class="f13" colspan="4">Date Prepared:</td>
			    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_prepared_temp));?></td>
			    			<td class="f13" colspan="1"></td>
			    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JO No.:</td>
			    			<td class="f13 bor-btm" colspan="5"><b><?php echo $cenjo_no_temp; ?></b></td>
			    		</tr>
			    		<tr>
			    			<td class="f13" colspan="4">Start of Work:</td>
			    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($start_of_work_temp));?></td>
			    			<td class="f13" colspan="1"></td>
			    			<td class="f13" colspan="3">JO. No:</td>
			    			<td class="f13 bor-btm" colspan="5"><?php echo $joi_no."-".COMPANY; ?></td>
			    		</tr>
			    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	 
				    	<tr>
			    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
				    			<h5 style="margin: 5px"><b><?php echo $project_title_temp;?></b></h5>
				    		</td>
			    		</tr>
			    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
			    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>  
		    		<?php } ?>		
		    		<tr>
		    		
		    			<td colspan="20">
		    				<table class="table-borderded" width="100%">
		    					<tr>
		    						<td width="40%" class="f13 p-l-5" align="left"><b>Scope of Work:</b></td>
		    						<td width="10%" class="f13" align="center"><b>Qty</b></td>
		    						<td width="5%" class="f13" align="center"><b>UM</b></td>
		    						<td width="10%" class="f13" align="center"><b>Currency</b></td>
		    						<td width="15%" class="f13" align="center"><b>Unit Cost</b></td>
		    						<td width="15%" class="f13" align="center"><b>Total Cost</b></td>
		    					</tr>
		    					<?php if($revised==0){ ?>
		    					<tr>
                               		<td class="f13" style="padding-left: 5px" align="left"><input type="text" name="general_desc" class = "form-control" value="<?php echo $general_desc;?>"></input></td>
                            	</tr>
                            	<?php } ?>
		    					<?php 
		    						$x=1; 
		    						if($revised==0){
		    							//ITEMS
		    							if(!empty($items)){
			    							foreach($items AS $det){ 
			    								$gtotal2[] = $det->amount;
			    								$mattotal[] = $det->materials_amount;
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"> <textarea name = "scope_of_work<?php echo $x; ?>" class = "form-control" rows="4"><?php echo $det->offer; ?></textarea></td>

		    						<td class="f13" align="center" style="vertical-align:top;">
		    							<input type="text" name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' onblur="this.value = minmax(this.value, 0, <?php echo $det->delivered_quantity; ?>)" value='<?php echo $det->delivered_quantity; ?>' style='width:50px; color:red;text-align: center' onchange='changePrice_JO(<?php echo $x; ?>)' onkeypress="return isNumberKey(this, event)"/>
		    							<!-- <input type="text" name="quantity<?php echo $x; ?>" id='quantity<?php echo $x; ?>' style = "width:50%;text-align: center" value = "<?php echo $det->delivered_quantity; ?>" onblur='changePrice_JO(<?php echo $x; ?>)'> -->
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top;"><input type="text" name="uom<?php echo $x; ?>" style = "width:100%;text-align: center" value = "<?php echo $det->uom; ?>"></td>
		    						<td class="f13" align="center" style="vertical-align:top">
				    				<select name='currency<?php echo $x; ?>'>
						    			<?php foreach($currency2 AS $curr){ ?>
						    		<option value="<?php echo $curr; ?>" <?php echo (($curr==$det->currency) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    		<?php } ?>
						    		</select>
				    				</td>
		    						<td class="f13" align="center" style="vertical-align:top;"><input type="text" name='price<?php echo $x; ?>' id='price<?php echo $x; ?>' style = "width:130px;text-align: center" value = "<?php echo $det->unit_price; ?>" onblur='changePrice_JO(<?php echo $x; ?>)'></td>
		    						<td class="f13" align="right" style="vertical-align:top;"><input type="text" name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' style = "width:100%;text-align: center" class='tprice' value = "<?php echo $det->amount; ?>"></td>
		    						<td class="f13" align="right" style="vertical-align:top;"><a href="<?php echo base_url(); ?>joi/delete_scope/<?php echo $det->joi_items_id?>/<?php echo $det->joi_id?>" class="btn btn-danger btn-xs" style = "text-align: center"><span class="fa fa-times"></span></a></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5" style="vertical-align:top;"></td></tr>
		    					<?php $x++; } ?> 
		    					<!--ITEMS-->
		    					<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Materials:</b></td>
		    					</tr>
		    					<?php } ?>
		    					<!--MATERIALS-->
		    					<?php 
		    						$y=1;
		    						$b=1;
		    						foreach($items AS $det){ 
		    							if($det->materials_offer!='' && $det->materials_qty!=0){
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"> <textarea name = "materials_offer<?php echo $y; ?>_<?php echo $b; ?>" class = "form-control" rows="4"><?php echo $det->materials_offer; ?></textarea></td>

		    						<td class="f13" align="center" style="vertical-align:top;">
		    							<input type="text" name='materials_qty<?php echo $y; ?>_<?php echo $b; ?>' id='materials_qty<?php echo $y; ?>_<?php echo $b; ?>' class='quantity' onblur="this.value = minmax(this.value, 0, <?php echo $det->materials_qty; ?>)" value='<?php echo $det->materials_qty; ?>' style='width:50px; color:red;text-align: center' onchange='changematerialsPrice_JO(<?php echo $y; ?>,<?php echo $b; ?>)' onkeypress="return isNumberKey(this, event)"/>
		    							<!-- <input type="text" name="quantity<?php echo $x; ?>" id='quantity<?php echo $x; ?>' style = "width:50%;text-align: center" value = "<?php echo $det->delivered_quantity; ?>" onblur='changematerialsPrice_JO(<?php echo $y; ?>,<?php echo $b; ?>)'> -->
		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top;"><input type="text" name="materials_unit<?php echo $y; ?>_<?php echo $b; ?>" style = "width:100%;text-align: center" value = "<?php echo $det->materials_unit; ?>"></td>
		    						<td class="f13" align="center" style="vertical-align:top">
				    				<select name='materials_currency<?php echo $y; ?>_<?php echo $b; ?>'>
						    			<?php foreach($currency2 AS $curr){ ?>
						    		<option value="<?php echo $curr; ?>" <?php echo (($curr==$det->materials_currency) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    		<?php } ?>
						    		</select>
				    				</td>
		    						<td class="f13" align="center" style="vertical-align:top;"><input type="text" name='materials_price<?php echo $y; ?>_<?php echo $b; ?>' id='materials_price<?php echo $y; ?>_<?php echo $b; ?>' style = "width:130px;text-align: center" value = "<?php echo $det->materials_unitprice; ?>" onblur='changematerialsPrice_JO(<?php echo $y; ?>,<?php echo $b; ?>)'></td>
		    						<td class="f13" align="right" style="vertical-align:top;"><input type="text" name='materials_tprice<?php echo $y; ?>_<?php echo $b; ?>' id='materials_tprice<?php echo $y; ?>_<?php echo $b; ?>' style = "width:100%;text-align: center" class='materials_tprice' value = "<?php echo $det->materials_amount; ?>"></td><!-- 
		    						<td class="f13" align="right" style="vertical-align:top;"><a href="<?php echo base_url(); ?>joi/delete_scope/<?php echo $det->joi_items_id?>/<?php echo $det->joi_id?>" class="btn btn-danger btn-xs" style = "text-align: center"><span class="fa fa-times"></span></a></td> -->
		    					</tr>
		    					<tr><td colspan="5" class="p-5" style="vertical-align:top;"></td></tr>
		    					<?php $y++; $b++; } } ?> 
		    					<!--MATERIALS-->
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<!-- <b>Notes:</b>		    						 -->
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<!-- <?php 
		    						$y=1; 
		    						foreach($tc AS $n){ 
		    							if($n->notes!=''){
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<textarea name = "joi_notes<?php echo $y; ?>" class = "form-control"><?php echo $n->notes; ?></textarea>
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<input type='hidden' name='joi_tc_id<?php echo $y; ?>' value="<?php echo $n->joi_tc_id; ?>">
		    					<?php $y++; } } ?>
		    					<input type='hidden' name='count_notes' value="<?php echo $y; ?>"> -->

		    					<?php }else { $gtotal2=array(); $mattotal=array(); } }else {
		    					?>
		    					<!--ITEMS-->
		    					<tr>
                                    <td class="f13 p-l-5" align="left"><b><?php echo $general_desc_temp; ?></b></td>
                                </tr>
		    					<?php
		    						if(!empty($items_temp)){
		    						foreach($items_temp AS $det){ 
			    						$gtotal2[] = $det->amount;
			    						$mattotal[] = $det->materials_amount;
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><?php echo nl2br($det->offer)."<br><br>"; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->delivered_quantity; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->uom; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->currency; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo number_format($det->unit_price,4); ?></td>
		    						<td class="f13" align="right" style="vertical-align:top;"><?php echo number_format($det->amount,4); ?></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<?php } ?> 
		    					<!--ITEMS-->
		    					<?php if($materials_offer_temp!='' && $materials_qty_temp!=0){ ?>
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Materials:</b></td>
		    					</tr>
		    					<?php } ?>
		    					<!--MATERIALS-->
		    					<?php 
		    						foreach($items_temp AS $det){ 
		    							if($det->materials_offer!='' && $det->materials_qty!=0){
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><?php echo nl2br($det->materials_offer)."<br><br>"; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->materials_qty; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->materials_unit; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo $det->materials_currency; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top;"><?php echo number_format($det->materials_unitprice,4); ?></td>
		    						<td class="f13" align="right" style="vertical-align:top;"><?php echo number_format($det->materials_amount,4); ?></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<?php } } ?> 
		    					<!--MATERIALS-->
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
		    						foreach($tc_temp AS $n){ 
		    							if($n->notes!=''){
		    					?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left">
		    							<?php echo $n->notes."<br><br>"; ?>
		    						</td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					<input type='hidden' name='joi_tc_id<?php echo $y; ?>' value="<?php echo $n->joi_tc_id; ?>">
		    					<?php $y++; } } ?>
		    					<input type='hidden' name='count_notes' value="<?php echo $y; ?>">
		    					<?php }else { $gtotal2=array();  $mattotal=array(); } } ?>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="right"></td>
		    						<td class="f13" align="right"></td>
		    					</tr>
		    					<tr><td colspan="6" class="p-5"></td></tr>
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13 p-l-5" align="left"></td>
		    					</tr>
		    					<?php 
		    						if($revised==0){ 
			    						$sum_cost = array_sum($gtotal2);
			    						$matsum_cost = array_sum($mattotal);
			    						$percent=$vat_percent/100;
			    						$total=$sum_cost+$matsum_cost;
			    						$sumvat=($total*$percent);
			    						$subtotal= $total + $sumvat; 
			    						$grandtotal = ($sum_cost+ $matsum_cost +$sumvat)-$discount;
			    						/*$subtotal= $sum_cost + $matsum_cost + $vat_amount; 
			    						$grandtotal = ($sum_cost+ $matsum_cost +$vat_amount)-$discount;*/
		    					?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Total Labor:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="sum_cost" id='sum_cost' value="<?php echo $sum_cost;?>" readonly="readonly" style='text-align: right;'></td>
		    					</tr>
		    					<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Total Materials:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="mat_sum_cost" id='mat_sum_cost' onkeyup='changePrice()' value="<?php echo $matsum_cost;?>" readonly="readonly" style='text-align: right;'></td>
		    					</tr>
		    					<?php } else{ ?>
		    						<input class="nobord" type="hidden" name="mat_sum_cost" id='mat_sum_cost' value="<?php echo $matsum_cost;?>" readonly="readonly" style='text-align: right;'>
		    					<?php } ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Discount Labor:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" name="discount_lab" id='discount_lab' onblur='changePrice()' value="<?php echo $discount_lab;?>" type="text" onkeypress="return isNumberKey(this, event)" style='text-align: right;'></td>
		    					</tr>
		    					<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Discount Material:</td>	    						
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="discount_mat" id='discount_mat' onblur='changePrice()' value="<?php echo $discount_mat;?>" onkeypress="return isNumberKey(this, event)" style='text-align: right;'></td>
		    					</tr>
		    					<?php }else{ ?>
		    						<input class="nobord" type="hidden" name="discount_mat" id='discount_mat' value="0" readonly="readonly" style="text-align: right;width: 100%;">
		    					<?php } ?>
		    					<!-- <tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Subtotal:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="subtotal" id='subtotal' readonly="readonly" value="<?php echo $subtotal;?>" style='text-align: right;'></td>
		    					</tr> -->
		    					<?php if($grand_total!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Less Discount:</td>
		    						
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="less_amount" id='less_amount' value="<?php echo $discount;?>" onblur='changePrice()' style='text-align: right;'></td>
		    					</tr>
		    					<?php } ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>		    						
		    						<td align="right">VAT %: <input class="nobord" type="text" placeholder="0%" name="vat_percent" id='vat_percent' onblur='changePrice()' value="<?php echo $vat_percent;?>" style="width:60px;border-bottom: 1px solid #000"></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="vat_amount" id='vat_amount' readonly="readonly" value="<?php echo $sumvat;?>" style='text-align: right;'></td>
		    					</tr>
		    					<?php } else { 
		    						$sum_cost = array_sum($gtotal2);
		    						$matsum_cost = array_sum($mattotal);
		    						$percent_temp=$vat_percent_temp/100;
		    						$total_temp=$sum_cost+$matsum_cost;
		    						$sumvat_temp=($total_temp*$percent_temp);
		    						$subtotal= $total_temp + $sumvat_temp; 
		    						$grandtotal = ($sum_cost+ $matsum_cost +$sumvat_temp)-$discount_temp;
		    						/*$subtotal= $sum_cost + $matsum_cost + $vat_temp; 
		    						$grandtotal = ($sum_cost+$matsum_cost+$vat_temp)-$discount_temp;*/
	    						?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Total Labor:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="sum_cost" id='sum_cost' onblur='changePrice()' value="<?php echo $sum_cost;?>" readonly="readonly" style='text-align: right;'></td>
		    					</tr>
		    					<?php if($materials_offer_temp!='' && $materials_qty_temp!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Total Materials:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="mat_sum_cost" id='mat_sum_cost' onblur='changePrice()' value="<?php echo $matsum_cost;?>" readonly="readonly" style='text-align: right;'></td>
		    					</tr>
		    					<?php } else{ ?>
		    						<input class="nobord" type="hidden" name="mat_sum_cost" id='mat_sum_cost' value="<?php echo $matsum_cost;?>" readonly="readonly" style='text-align: right;'>
		    					<?php }?>

		    					<!-- <?php if($discount_lab_temp!=0){ ?> -->
								<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Discount Labor:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" name="discount_lab" id='discount_lab' onblur='changePrice()' value="<?php echo $discount_lab_temp;?>" type="text" onkeypress="return isNumberKey(this, event)" style='text-align: right;'></td>
		    					</tr>
		    					<!-- <?php }else{ ?>
		    						<input class="nobord" type="hidden" name="discount_lab" id='discount_lab' value="0" readonly="readonly" style="text-align: right;width: 100%;">
		    					<?php } ?> -->
		    					<?php if($materials_offer_temp!='' && $materials_qty_temp!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Discount Material:</td>		    						
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="discount_mat" id='discount_mat' onblur='changePrice()' value="<?php echo $discount_mat_temp;?>" onkeypress="return isNumberKey(this, event)" style='text-align: right;'></td>
		    					</tr>
		    					<?php }else{ ?>
		    						<input class="nobord" type="hidden" name="discount_mat" id='discount_mat' value="0" readonly="readonly" style="text-align: right;width: 100%;">
		    					<?php } ?>
		    					<?php if($grand_total_temp!=0){ ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Less Discount:</td>		    						
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="less_amount" id='less_amount' value="<?php echo $discount_temp;?>" onblur='changePrice()' style='text-align: right;'></td>
		    					</tr>
		    					<?php } ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">VAT %: <input class="nobord" type="text" placeholder="0%" name="vat_percent" id='vat_percent' onblur='changePrice()' value="<?php echo $vat_percent_temp;?>" style="width: 60px;border-bottom: 1px solid #000;"></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="vat_amount" id='vat_amount' readonly="readonly" value="<?php echo $sumvat_temp;?>" style='text-align: right;'></td>
		    					</tr>
		    					<!-- <tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">Subtotal:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="subtotal" id='subtotal' readonly="readonly" value="<?php echo $subtotal;?>" style='text-align: right;'></td>
		    					</tr> -->
		    					
		    					<?php } ?>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td align="right">GRAND TOTAL:</td>
		    						<?php if($grand_total_temp!=0){ ?>
		    							<td class="bor-btm" align="right"><input class="nobord" type="text" name="net" id='net' value="<?php echo $grand_total_temp;?>" readonly="readonly" style='text-align: right;'></td>
		    						<?php }else{ ?>
		    							<td class="bor-btm" align="right"><input class="nobord" type="text" name="net" id='net' value="<?php echo $grandtotal;?>" readonly="readonly" style='text-align: right;'></td>
		    						<?php } ?>
		    						
		    					</tr>
		    				</table>
		    			</td>
		    		</tr>
		    		<!-- <tr>
		    			<td colspan="20">
		    				<table class="table-borsdered" width="100%">
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Notes:</b></td>
		    						<td width="10%" class="f13" align="center"><b></b></td>
		    						<td width="5%" class="f13" align="center"><b></b></td>
		    						<td width="15%" class="f13" align="center"><b> </b></td>
		    						<td width="15%" class="f13" align="center"><b> </b></td>
		    						<td width="15%" class="f13" align="center"></td>
		    					</tr>
		    					<tr>
		    						<td class="f13" style="padding-left: 5px" align="left"><textarea name = "scope_of_work<?php echo $x; ?>" class = "form-control"></textarea></td>

		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    						<td></td>
		    					</tr>
		    					
		    				</table>
		    			</td>
		    		</tr> -->
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<?php if($revised==0){ ?>
		    				<button type="button" class="btn btn-primary btn-xs " data-toggle="modal" data-target="#addterms">
							 Add Terms & Conditions
							</button>
							<?php } ?>
		    				<br>Terms & Conditions:<br>
		    				1. JO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				2. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>		    				 
                            3. Price is
                           <?php 
                            if($revised==0){ ?>
                                <select type="text" name="vat_in_ex">
                                    <option value = "0" <?php echo (($vat_in_ex == '0') ? 'selected' : '');?>>inclusive of VAT</option>
                                    <option value = "1" <?php echo (($vat_in_ex == '1') ? 'selected' : '');?>>exclusive of VAT</option>
                                </select>	
                            <?php } else { ?>
                               <?php echo (($vat_in_ex_temp == '0') ? 'inclusive of VAT' : 'exclusive of VAT');?>
                            <?php } ?>
	                        <br>
		    				<?php if($joi_type!=1){ $x=4; ?>
		    				<?php if(!empty($payment_terms)){ 
		    				echo $x."."; ?> Payment term: <?php echo $payment_terms; ?><br>
		    				<?php $x++; } ?>	
		    				<?php if(!empty($item_warranty)){ 
		    				echo $x."."; ?> Item Warranty: <?php echo $item_warranty; ?><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($delivery_time)){ 
		    				echo $x."."; ?> Work Duration: <?php echo $delivery_time; ?><br>
		    				<?php $x++; } ?>
		    				<?php if(!empty($freight)){ 
		    				echo $x."."; ?> In-land Freight: <?php echo $freight; ?><br>
		    				<?php $x++; } } ?>
		    				<?php 
		    				
		    					$y = $x;
		    					//$x = 4;
		    					if($revised==0){
		    						foreach($tc AS $t){ 
		    							if(!empty($t->tc_desc)){
			    			?>
			    				<!-- <?php echo $x.". "; ?><input type = "text" style='color:red;width: 90%' name = "terms<?php echo $y; ?>" value = "<?php echo $t->tc_desc; ?>"><br> -->
								<?php echo $x.". ".$t->tc_desc; ?>
								<a class='btn btn-primary btn-xs prnt' id = "updateTerm" data-toggle='modal' data-target='#UpdateTerms' data-id = '<?php echo $t->joi_tc_id; ?>' data-name = '<?php echo $t->tc_desc; ?>'>
			    					<span class = 'fa fa-edit'></span>
			    				</a>
			    				<br>
			    			<?php }$x++;$y++;} ?>
							<?php 
								foreach($tc_temp AS $tt){ 
									if(!empty($tt->tc_desc)){
			    			?>
							<?php echo $x.". ".$tt->tc_desc; ?>
								<!-- <input type = "text" style='color:red;width: 90%' name = "terms<?php echo $y; ?>" value = "<?php echo $tt->tc_desc; ?>"> -->
								<a class='btn btn-primary btn-xs prnt' id = "updateTermTemp" data-toggle='modal' data-target='#UpdateTermsTemp' data-id = '<?php echo $tt->joi_tc_id; ?>' data-name = '<?php echo $tt->tc_desc; ?>'>
									<span class = 'fa fa-edit'></span>
								</a>
								<br>
							<?php $x++; } $y++; } ?>
							<?php
		    					}else { 
		    						foreach($tc AS $t){ 
			    						if(!empty($t->tc_desc)){
				    						echo $x.". " . $t->tc_desc."<br>";
				    						$x++; 
				    					}
			    					} 
									foreach($tc_temp AS $t){ 
			    						if(!empty($t->tc_desc)){
				    						echo $x.". " . $t->tc_desc."<br>";
				    						$x++; 
				    					}
			    					} 
			    				}
		    				?>
		    			</td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Total Project Cost:</td>
		    			<td class="f13 bor-btm" colspan="7" align="right"><h4 style="margin: 0px"><b><span id='gtotal'></span></b></h4></td>
		    			<td class="f13" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Conforme:</td>
		    			<td class="f13 bor-btm" colspan="7" align="center"><?php if($revised==0){ ?><input type="text" name='conforme' style = "width:100%;" value = "<?php echo $conforme; ?>"><?php } else{ echo $conforme_temp; } ?></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="6"></td>
		    			<td class="f13" colspan="2"></td>
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
		    				<?php echo ($revised==0) ? $prepared : $prepared_by_temp; ?>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="checked_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($checked_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $checked_temp; }?>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="recommended_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($recommended_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $recommended_temp; } ?>
		    			</td>

		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="3" align="center">
		    				<?php if($revised==0){ ?>
		    				<select type="text" name="approved_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>" <?php echo (($approved_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $approved_temp; } ?>
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
		    				<?php if($revised==0){ ?>
		    				<select name="verified_by" id="verified_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"  <?php echo (($verified_id == $emp->employee_id) ? ' selected' : '');?>><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    				<?php }else { echo $verified_by_temp; } ?>
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
	    	<input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url();?>">
	    	<input type='hidden' name='joi_id' value="<?php echo $joi_id?>">
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