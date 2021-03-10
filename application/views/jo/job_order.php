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
				<form method='POST' action='<?php echo base_url(); ?>jo/create_jo_details'>
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
				<form method='POST' action='<?php echo base_url(); ?>jo/create_jo_terms'>
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
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>jo/save_jo'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>jo/jo_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>jo/jo_rfd" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AC</b></a> -->
						<input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" value="Save">  	
					</div>
					<h4 class="text-white">JOB ORDER</b></h4>
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
		    	<table class="table-basdordered" width="100%" style="border:2px solid #000">
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
		    		<tr><td colspan="20" align="center"><h4><b>JOB ORDER</b></h4></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<b><?php echo $vendor; ?></b><br>
		    				<span id='contact_person'><?php echo $contact_person; ?></span><br>
		    				<span id='address'><?php echo $address; ?></span><br>
		    				<span id='phone'><?php echo $phone; ?></span><br>
		    				<span id='fax'><?php echo $fax; ?></span><br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Date Needed:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_needed)); ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13 " colspan="3">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo date('F j, Y', strtotime($work_completion)); ?></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($date_prepared)); ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"><?php echo JO_NAME;?> JO No.:</td>
		    			<td class="f13 bor-btm" colspan="6"><b><?php echo $cenjo_no; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7"><?php echo date('F j, Y', strtotime($start_of_work)); ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO. No:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo $jo_no."-".COMPANY; ?></td>
		    		</tr>	
		    					    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px; text-transform: uppercase;"><b><?php echo $project_title; ?></b></h5>
			    		</td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><i><small>PROJECT TITLE/DESCRIPTION</small></i></td></tr>		    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		    		
		    		<tr>
		    			<td class="f13  p-l-5" colspan="20" align="left">
			    			<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addscope">
							  <span class="fa fa-plus"></span> Add Scope
							</button>		    			
			    		</td>
			    	</tr>		    		
		    		<tr>
		    			<td colspan="20">
		    				<table class="table-borsdered" width="100%">
		    					<tr>
		    						<td width="55%" class="f13 p-l-5" align="left"><b>Scope of Work:</b></td>
		    						<td width="10%" class="f13" align="center"><b>Qty</b></td>
		    						<td width="5%" class="f13" align="center"><b>UM</b></td>
		    						<td width="15%" class="f13" align="center"><b>Unit Cost</b></td>
		    						<td width="15%" class="f13" align="center"><b>Total Cost</b></td>
		    					</tr>
		    					<?php
		    						$total[] = '';
		    						foreach($details AS $det){
		    						$total[]= $det->total_cost; ?>
		    					<tr>
		    						<td class="f13 p-l-5" align="left">
		    							<?php echo nl2br($det->scope_of_work); ?>

		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top"><?php echo $det->quantity; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top"><?php echo $det->uom; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top"><?php echo $det->unit_cost; ?></td>
		    						<td class="f13" align="center" style="vertical-align:top"><input type="text" name="total_cost" id="total_cost" value="<?php echo $det->total_cost; ?>" readonly="readonly" class="btn-block nobord texcenter"></td>
		    					</tr>
		    					<tr><td colspan="5" class="p-5"></td></tr>
		    					<?php } ?>
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    					</tr>
		    					<?php $sum_cost = array_sum($total); ?>
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
		    						<td><input class="nobord" type="text" placeholder="0%" name="vat_percent" id='vat_percent' onblur='changePrice()'></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="vat_amount" id='vat_amount' readonly="readonly"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td colspan='2'>Subtotal:</td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="subtotal" id='subtotal' readonly="readonly"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>Less Discount:</td>
		    						<td><!-- <input class="nobord" type="text" placeholder="Discount %" name="less_percent" id='less_percent'> --></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="less_amount" id='less_amount'  onblur='changePrice()'></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>GRAND TOTAL:</td>
		    						<td></td>
		    						<td class="bor-btm" align="right"><input class="nobord" type="text" name="net" id='net' value="<?php echo $sum_cost; ?>" readonly="readonly"></td>
		    					</tr>
		    				
		    					
		    				</table>
		    			</td>
		    		</tr>
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	
		    		<tr>
		    			<td class="f13  p-l-5" colspan="20" align="left">
			    			<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addterms">
							  <span class="fa fa-plus"></span> Terms & Conditions:
							</button>		    			
			    		</td>
			    	</tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="11" align="left">
		    				<b>Terms & Conditions:</b><br>
		    			<?php foreach($terms AS $trm){
		    				echo nl2br($trm->terms)."<br>";
		    			} ?>
		    			</td>
		    			<td colspan="9"></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3">Total Project Cost:</td>
		    			<td class="f13 bor-btm" colspan="7" align="right"><h4 style="margin: 0px"><b><span id='gtotal'><?php echo number_format($sum_cost,2); ?></span></b></h4></td>
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
                                    <option value="<?php echo $emp->employee_id; ?>"><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="recommended_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    			</td>

		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="4" align="center">
		    				<select type="text" name="approved_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"><?php echo $emp->employee_name; ?></option>
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
	    	<input type="hidden" name='jo_id' value="<?php echo $jo_id; ?>">
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