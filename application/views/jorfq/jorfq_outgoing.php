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
        
        .served{
        	background-image: url('<?php echo base_url(); ?>assets/img/served.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
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
    	<form method='POST' action='<?php echo base_url(); ?>jorfq/save_jorfq'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>jorfq/jorfq_list" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($completed==0){ ?>
						<a href="<?php echo base_url(); ?>jorfq/complete_jorfq/<?php echo $jo_rfq_id; ?>"  class="btn btn-info btn-md p-l-50 p-r-50" onclick="return confirm('Are you sure?')"><span class="fa fa-check"></span> Canvass Complete</a>
						<?php  } ?>
						<?php if($saved==1){ ?>
						<!-- <a href="<?php echo base_url(); ?>jorfq/export_jorfq/<?php echo $jo_rfq_id; ?>" class="btn btn-primary btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Export</a> -->
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Print</a>
						<!-- <a  href="<?php echo base_url(); ?>jo/jo_rfd" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_dr" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<a  href="<?php echo base_url(); ?>jo/jo_ac" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AR</b></a> -->
						<?php } else if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" value="Save"> 
						<?php } ?> 	
					</div>
					<h4 class="text-white">JO - RFQ</b></h4>
					<p class="text-white">Instructions: When printing JO - RFQ make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" class = "<?php if($cancelled==1){ echo 'cancel'; } else if($served==1){ echo 'served';} ?>">  
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
			    			<h5 style="margin: 5px; text-transform: uppercase;"><b><?php echo $purpose; ?></b></h5>
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
		    			<td colspan="10">
		    				<div style="margin: 10px">
			    				<b>Scope of Work:</b>
			    				<br>
			    				<?php echo $general_desc;?>
			    				<br>
			    				<?php foreach($items AS $i){ ?>
			    				<?php echo "<b>".$i['item_no'].".</b> ".nl2br($i['scope_of_work'])."<br>"; ?><br>
			    				<?php } ?>
							</div>
		    			</td>
		    			<td class="bor-btm" colspan="9">
		    				<div style="margin: 10px; vertical-align: bottom;"></div>
		    			</td>
		    			<td  colspan="1">
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20">
		    				<div style="margin: 10px">
			    				<b>Notes:</b>
			    				<br>
			    				<?php $x=1; foreach($rfq_notes AS $n){ ?>
			    				<?php echo $x.". ".$n->notes."<br>"; ?>
			    				<?php $x++; } ?>
			    			</div>	
		    			</td>
		    		</tr>
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	


		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">JO Reference No.</td>
		    			<td class="f13" colspan="16" align="left"> <?php echo $jo_no."-".COMPANY; ?></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr>
		    		<!-- <tr>
		    			<td class="f13 p-l-10" colspan="3">End User: </td>
		    			<td class="f13" colspan="16" align="left"><center><?php{ 
		    			 	echo $requested_by; 
		    			 } ?></center></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr> -->
		    		
		    		<tr><td class="f13  p-l-5" colspan="20" align="left"><br></td></tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<!-- <tr>
		    			<td class="f13 p-l-10" colspan="3">Supplier's Name:</td>
		    			<td class="f13 bor-btm" colspan="16" align="left"><?php echo $vendor; ?></td>
		    			<td class="f13" colspan="1"></td>		    			
		    		</tr> -->
		    		<tr>
		    			<td class="f13 p-l-10" colspan="19">
		    				<table width="100%">
		    					<tr>
		    						<td width="11%">Supplier's Name:</td>
		    						<td class="f13 bor-btm" width="65%" align="left"><?php echo $vendor; ?></td>
		    					</tr>
		    				</table>
		    			</td>
		    			<td class="f13" colspan="1"></td>			    			
		    		</tr>
		    		<tr>
		    			<td class="f13  p-l-10" colspan="10">
		    				<table width="100%">
		    					<tr>
		    						<td width="25%">Contact Number:</td>
		    						<td class="f13 bor-btm" width="65%" align="left"><?php echo $phone; ?></td>
		    					</tr>
		    				</table>
		    			</td>
		    			<td class="f13 p-l-10" colspan="9">
		    				<table width="100%">
		    					<tr>
		    						<td width="25%">Duration:</td>
		    						<td class="f13 bor-btm" width="65%" align="left"><?php echo $duration; ?></td>
		    					</tr>
		    				</table>
		    			</td>
		    			<td class="f13" colspan="1"></td>	
		    		</tr>
		    		<tr>
		    			<td class="f13  p-l-10" colspan="10">
		    				<table width="100%">
		    					<tr>
		    						<td width="25%">Warranty:</td>
		    						<td class="f13 bor-btm" width="65%" align="left"></td>
		    					</tr>
		    				</table>
		    			</td>
		    			<td class="f13 p-l-10" colspan="9">
		    				<table width="100%">
		    					<tr>
		    						<td width="25%">Payment Terms:</td>
		    						<td class="f13 bor-btm" width="65%" align="left"></td>
		    					</tr>
		    				</table>
		    			</td>
		    			<td class="f13" colspan="1"></td>	
		    		</tr>
		    		<!-- <tr>
		    			<td class="f13" colspan="3">Payment Terms:</td>
		    			<td class="f13" colspan="5" align="left"><?php echo $phone; ?></td>
		    			<td class="f13" colspan="1"></td>	
		    		</tr>
		    		<tr>
		    			<td class="f13 p-l-10" colspan="3">Duration:</td>
		    			<td class="f13 bor-btm" colspan="16" align="right"><?php echo $duration; ?></td>
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
		    		</tr> -->

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
		    			<td class="f13 " colspan="5" align="center">Prepared by:</td>
		    			<td class="f13" colspan="8" align="center">Noted by:</td>
		    			<td class="f13" colspan="5" align="center">Approved by:</td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13  bor-btm" colspan="5" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 bor-btm" colspan="6" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13 bor-btm" colspan="5" align="center"><br></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>   
		    		<tr>
		    			<td class="f13" colspan="1" align="center"></td>
		    			<td class="f13" colspan="5" align="center">
		    				<?php echo $prepared; ?></td>
		    			<td class="f13" colspan="8" align="center"><?php echo $noted; ?></td>
		    			<!-- <td class="f13" colspan="2" align="center"><?php echo $approved; ?></td>
		    			<td class="f13" colspan="8" align="center">
		    		      <center><?php if($saved==0){ 
		    			 	echo $_SESSION['fullname']; 
		    			 } else {
		    			 	echo $prepared;
		    			 } ?></center>
		    			</td> -->
		    			<td class="f13" colspan="5" align="center"><?php echo $approved; ?></td>
		    			<td class="f13" colspan="1" align="center"></td>
		    		</tr>  
		    		<tr><td class="f13" colspan="20" align="center"><br><br></td></tr>    
		    	</table>		    
	    	<input type='hidden' name='jo_rfq_id' value='<?php echo $jo_rfq_id; ?>'>
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