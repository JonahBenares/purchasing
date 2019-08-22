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
				<form >
					<div class="modal-body">
						<div class="form-group">
							<p style="font-size: 14px" class="nomarg">Scope:</p>
							<textarea class="form-control" rows="3"></textarea>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">Qty:</p>
								<input type="text" class="form-control" name="">
							</div>
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">U/M:</p>
								<input type="text" class="form-control" name="">
							</div>
							<div class="col-lg-4">
								<p style="font-size: 14px" class="nomarg">Unit Cost:</p>
								<input type="text" class="form-control" name="">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-primary btn-block" value="Add">
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
				<form >
					<div class="modal-body">
						<div class="form-group">
							<p style="font-size: 14px" class="nomarg">Terms & Conditions:</p>
							<textarea class="form-control" rows="3"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-primary btn-block" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>jo/create_jo'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>jo/jo_rfd" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AC</b></a>
						<input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" value="Save">  	
					</div>
					<h4 class="text-white"><b>OUTGOING</b> RFQ <b>For JOB ORDER</b></h4>
					<p class="text-white">Instructions: When printing JOB ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;">    		  			
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
		    			<td colspan="5" align="center"><img width="150px" src="<?php echo base_url(); ?>assets/img/logo_cenpri.png"></td>
		    			<td colspan="15"><h4 style="margin-left: 15px"><b>CENTRAL NEGROS POWER RELIABILITY, INC.</b></h4></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center">Office: 88 Corner Rizal-Mabini Sts., Bacolod City</td></tr>
		    		<tr><td class="f13" colspan="20" align="center">Bacolod Office Telefax: (034) 435-1932/476-7382</td></tr>
		    		<tr><td colspan="20" align="center"><h4><b>JOB ORDER</b></h4></td></tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="3" style="vertical-align:top">TO:</td>
		    			<td class="f13" colspan="10" align="left">
		    				<b>HYDRAUKING INDUSTRIAL CORPORATION</b><br>
		    				<span id='address'>542 M, Aliganga Street, Brgy. Tanke Naga City, Cebu City</span><br>
		    				<span id='phone'>340-6467</span><br>
		    			</td>
		    			<td colspan="7"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="4">Date Prepared:</td>
		    			<td class="f13 bor-btm" colspan="7">April 17, 2019</td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">CENPRI JO No.:</td>
		    			<td class="f13 bor-btm" colspan="5"><b>CENJO EM046-19</b></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-5" colspan="4">Start of Work:</td>
		    			<td class="f13 bor-btm" colspan="7">April 23, 2019</td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3">JO. No:</td>
		    			<td class="f13 bor-btm" colspan="5">JO 2019-014</td>
		    		</tr>	
		    		<tr>
		    			<td class="f13 p-l-5" colspan="4">Completion of Work:</td>
		    			<td class="f13 bor-btm" colspan="7">May 29, 2019</td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13" colspan="5"></td>
		    		</tr>			    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px; text-transform: uppercase;"><b>Servicing and Repair of Hydraulic Jack</b></h5>
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
		    					<tr>
		    						<td class="f13 p-l-5" align="left">
		    							Servicing and Repair of Hadraulic Jack<br>
		    							brand: Enerpac MOdel: RC106, SN: E4816K, 10Tons<br>
		    							a. replace
		    							b. honing
		    							c. hard
		    							d. replace

		    						</td>
		    						<td class="f13" align="center" style="vertical-align:top">1</td>
		    						<td class="f13" align="center" style="vertical-align:top">pc</td>
		    						<td class="f13" align="center" style="vertical-align:top">14,850</td>
		    						<td class="f13" align="center" style="vertical-align:top"><input type="text" name="total_cost" id="total_cost" readonly="readonly" class="btn-block"></td>
		    					</tr>
		    					<tr>
		    						<td class="f13 p-l-5" align="left"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    						<td class="f13" align="center"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>Less:</td>
		    						<td><input type="text" placeholder="Discount %" name="less_percent" id='less_percent' value='0' onblur='changePrice()'></td>
		    						<td class="bor-btm" align="right"><input type="text" name="less_amount" id='less_amount' readonly="readonly"></td>
		    					</tr>
		    					<tr>
		    						<td></td>
		    						<td></td>
		    						<td>Net</td>
		    						<td></td>
		    						<td class="bor-btm" align="right"><input type="text" name="net" id='net' readonly="readonly"></td>
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
		    				Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to CENPRI.<br>
		    				4. Payment term:<br>	
		    				5. Item Warranty: <br>	 
		    			</td>
		    			<td colspan="9"></td>
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
		    			<td class="f13 bor-btm" colspan="7"><input type="text" name="conforme" class="btn-block"></td>
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
		    			<td class="f13" colspan="3" align="center">Prepared by:</td>
		    			<td class="f13 bor-btm" colspan="5" align="center"></td>
		    			<td class="f13" colspan="2" align="center"></td>
		    			<td class="f13" colspan="3" align="center">Approved by:</td>
		    			<td class="f13 bor-btm" colspan="5" align="center"></td>
		    			<td class="f13" colspan="2" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="3" align="center"></td>
		    			<td class="f13" colspan="5" align="center">
		    				<?php echo $_SESSION['fullname']; ?>
		    			</td>
		    			<td class="f13" colspan="2" align="center"></td>
		    			<td class="f13" colspan="3" align="center"></td>
		    			<td class="f13" colspan="5" align="center">
		    				<select type="text" name="approved_by" class="btn-block">
		    					<option value=''>-Select-</option>
		    					 <?php foreach($employee AS $emp){ ?>
                                    <option value="<?php echo $emp->employee_id; ?>"><?php echo $emp->employee_name; ?></option>
								<?php } ?> 
		    				</select>
		    			</td>
		    			<td class="f13" colspan="2" align="center"></td>
		    		</tr>  
		    		<tr>
		    			<td class="f13" colspan="3" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Purchasing Department</small></td>
		    			<td class="f13" colspan="2" align="center"></td>
		    			<td class="f13" colspan="3" align="center"></td>
		    			<td class="f13" colspan="5" align="center"><small>Project Director</small></td>
		    			<td class="f13" colspan="2" align="center"></td>
		    		</tr>   	
		    		<tr><td class="f13" colspan="20" align="center"><br><br></td></tr>    	
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
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