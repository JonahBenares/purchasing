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
   <!--  <div class="modal fade" id="addscope" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	</div> -->
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>jo/save_jo'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>jorfq/jorfq_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>jo/jo_rfd" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AC</b></a> -->
						<input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" value="Save">  	
					</div>
					<h4 class="text-white">JO - RFQ</b></h4>
					<p class="text-white">Instructions: When printing JO - RFQ make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
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
			    					<!-- <?php echo TIN;?><br> -->
			    					<?php echo TEL_NO;?><br>
			    					<?php echo TELFAX;?><br>
			    				</div>
			    			</center>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" align="center">
		    				<small>JOB ORDER</small>
		    				<h4 style="margin-top: 0px"><b>REQUEST FOR QUOTATION</b></h4>
		    			</td>
		    		</tr>
		    					    			    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="border:2px solid #000">
			    			<h5 style="margin: 5px; text-transform: uppercase;"><b>Printing of tarpaulin for Environmental Compliance Certificate (ECC) notice as per DENR-EMB Compliance</b></h5>
			    		</td>
		    		</tr>
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
		    				<div style="margin: 10px">
		    				<b>Scope of Work:</b>
		    				<br>
		    				<?php foreach($items AS $i){ ?>
		    				<?php echo $i->scope_of_work; ?><br><br>
		    				<?php } ?>
							</div>
		    			</td>
		    		</tr>
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	


		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">JO Reference No.</td>
		    			<td class="f13" colspan="16" align="left"> <?php echo $jo_no."-".COMPANY; ?></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">End User: </td>
		    			<td class="f13" colspan="16" align="left"><center><?php{ 
		    			 	echo $_SESSION['fullname']; 
		    			 } ?></center></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr>
		    		
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Supplier's Name:</td>
		    			<td class="f13 bor-btm" colspan="16" align="right"><?php echo $vendor; ?></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Contact Number:</td>
		    			<td class="f13 bor-btm" colspan="16" align="right"><?php echo $phone; ?></td>
		    			<td class="f13" colspan="1"></td>		
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Duration:</td>
		    			<td class="f13 bor-btm" colspan="16" align="right"></td>
		    			<td class="f13" colspan="1"></td>		
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Warranty:</td>
		    			<td class="f13 bor-btm" colspan="16"></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Payment Terms:</td>
		    			<td class="f13 bor-btm" colspan="16"></td>
		    			<td col></td>
		    		</tr>

		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Notes:</td>
		    			<td class="f13 bor-btm" colspan="16"><?php echo $notes; ?></td>
		    			<td col></td>		    		 
		    		</tr>
		    		<tr><td class="f13 bor-btm" colspan="20" align="center"><br></td></tr>    	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>    	
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 " colspan="8" align="center">Prepared by:</td>
		    			<td class="f13" colspan="2" align="center"></td>
		    			<td class="f13" colspan="8" align="center">Approved by::</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="8" align="center"><br></td>
		    			<td class="f13" colspan="2" align="center"></td>
		    			<td class="f13 bor-btm" colspan="8" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="8" align="center">
		    				<!-- <?php echo $prepared; ?> -->
		    			</td>
		    			<td class="f13" colspan="2" align="center"><?php echo $approved; ?></td>
		    			<td class="f13" colspan="8" align="center">
		    		      <center><?php if($saved==0){ 
		    			 	echo $_SESSION['fullname']; 
		    			 } else {
		    			 	echo $prepared;
		    			 } ?></center>
		    			</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>  
		    		<tr><td class="f13" colspan="20" align="center"><br><br></td></tr>    
		    	</table>		    
	    	</div>
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