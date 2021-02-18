<?php 
   	$CI =& get_instance();  
?>
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
        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
        }
        .served{
        	background-image: url('<?php echo base_url(); ?>assets/img/served.png')!important;
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
			border-bottom: 1px solid #000!important;
		}
		.all-bor{
			border: 1px solid #000;
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

	        .bor-btm{
				border-bottom: 1px solid #000!important;
			}
			
	        .served{
	        	background-image: url('<?php echo base_url(); ?>assets/img/served.png')!important;
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
		    width: 100%;
		}
	
		.emphasis{
			border-bottom: 2px solid red;
		}
    </style>
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>rfq/save_rfq'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="" onclick="return quitBox('quit');" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<!-- <a  href='<?php echo base_url(); ?>rfq/override_rfq/' onclick="return confirm('Are you sure you want to override RFQ?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Override <u><b>RFQ</b></u></a> -->
						<?php if($completed==0){ ?>
							<a href="<?php echo base_url(); ?>rfq/complete_rfq/<?php echo $rfq_id; ?>"  class="btn btn-info btn-md p-l-50 p-r-50" onclick="return confirm('Are you sure?')"><span class="fa fa-check"></span> Canvass Complete</a>
						<?php  } 
						 if($saved==1){ ?>
						<a href="<?php echo base_url(); ?>rfq/export_rfq/<?php echo $rfq_id; ?>" class="btn btn-primary btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Export</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-50 p-r-50"><span class="fa fa-print"></span> Print</a>
						<!-- <a  href="<?php echo base_url(); ?>rfq/rfq_incoming/<?php echo $rfq_id; ?>" class="btn btn-primary btn-md p-l-50 p-r-50">RFQ Incoming</a> -->
						<?php } else if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">  		
						<?php } ?>
					</div>
					<h4 class="text-white"><b>OUTGOING</b> RFQ</h4>
					<p class="text-white">Instructions: When printing REQUEST FOR QUOTATION make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" class = "<?php if($cancelled==1){ echo 'cancel'; } else if($served==1){ echo 'served';} ?>">    		  			
		    	<table class="table-bordesred" width="100%" style="border:2px solid #000">
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
		    			<td width="3%"><br></td>
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
		    		<tr><td colspan="20" align="center"><b>REQUEST FOR QUOTATION</b></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13" colspan="2">Date: </td>
		    			<td class="f13 bor-btm" colspan="9"><?php echo date('F j, Y', strtotime($rfq_date)); ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="2">RFQ No.:</td>
		    			<td class="f13 bor-btm" colspan="3"><?php echo $rfq_no."-".COMPANY; ?></td>
		    			<td class="f13" colspan="1">Urg:</td>		    			
		    			<td class="f13 bor-btm" colspan="2"><?php echo $code; ?></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"></td></tr>
		    		<tr>
		    			<td class="f13" colspan="2">Supplier:</td>
		    			<td class="f13 bor-btm" colspan="9"><?php echo $vendor; ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="2">Tel. No.:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo $phone; ?></td>
		    		</tr>
		    		<tr id="printnotes">
		    			<td class="f13" colspan="2"></td>
    					<td class="f13" colspan="9"></td>
		    			
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="2">PR No:</td>
    					<td class="f13 bor-btm" colspan="6">
			    			<?php echo $pr_no."-".COMPANY; ?>
		    			</td>   		
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"></td></tr>	    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    		
		    		<tr>
		    			<td colspan="20">
		    				<table class="table-bordered" width="100%">
		    					<tr>
		    						<td class="f13" align="center" width="5%"><b>No.</b></td>
		    						<td class="f13" align="center"><b>Qty</b></td>
		    						<td class="f13" align="center"><b>Unit</b></td>
		    						<td class="f13" align="center"><b>Item Description</b></td>
		    						<td class="f13" align="center"><b>Brand/Offer</b></td>
		    						<td class="f13" align="center"><b>Unit Price</b></td>
		    					</tr>
		    					<?php 
		    					$x=1;
		    					foreach($items AS $it) {  ?>
		    					<tr>
		    						<td class="f13" align="center"><?php echo $x; ?></td>
		    						<td class="f13" align="center"><?php echo $it->quantity; ?></td>
		    						<td class="f13" align="center"><?php echo $it->uom; ?></td>
		    						<td class="f13" align="left" style='width:35%;padding-left: 2px'><?php echo (!empty($CI->get_pn($it->pr_details_id))) ? $it->item_desc.", ".$CI->get_pn($it->pr_details_id) : $it->item_desc; ?></td>
		    						<td class="f13" align="center" style='width:45%;'>
		    							<table width="100%">
		    								<tr class="bor-btm">
		    									<td><br></td>
		    								</tr>
		    								<tr class="bor-btm">
		    									<td><br></td>
		    								</tr>
		    								<tr>
		    									<td><br></td>
		    								</tr>
		    							</table>
		    						</td>
		    						<td class="f13" align="center" style='width:10%'>
		    							<table width="100%">
		    								<tr class="bor-btm">
		    									<td><br></td>
		    								</tr>
		    								<tr class="bor-btm">
		    									<td><br></td>
		    								</tr>
		    								<tr>
		    									<td><br></td>
		    								</tr>
		    							</table>
		    						</td>
		    					</tr>
		    					<?php $x++;
		    					} ?>
		    				</table>
		    			</td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>		    			
		    			
		    			<td class="f13" colspan="20">1. Quotation must be submitted on or before <b><?php echo (!empty($due) ? date("F d, Y", strtotime($due)) : ''); ?></b>
		    			</td></tr>	    	
		    		<tr><td class="f13" colspan="20">2. Please Fill - Up :</td></tr>	    	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5"> a. Price Validity</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 all-bor" colspan="1"></td>	
		    			<td class="f13 all-bor p-l-10" colspan="2">30 Days</td>	
		    			<td class="f13 all-bor" colspan="4"></td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5"> b. Payment Terms</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 all-bor" colspan="1"></td>	
		    			<td class="f13 all-bor p-l-10" colspan="2">n30</td>	
		    			<td class="f13 all-bor" colspan="4"></td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5"> c. Delivery Time</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5"> d. Item's Warranty</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5"> e. In-land Freight</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 all-bor" colspan="1"></td>	
		    			<td class="f13 all-bor p-l-10" colspan="2">Included</td>	
		    			<td class="f13 all-bor" colspan="1"></td>	
		    			<td class="f13 all-bor p-l-10" colspan="3">Not-included</td>	
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<!-- <tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">&nbsp; e. Company's TIN Number</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">&nbsp; d. Item's Warranty</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7"></td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="18">&nbsp; f. Vat <input type="checkbox" name=""> ||  non-Vat <input type="checkbox" name=""></td>
		    		</tr> -->
		    		
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	

		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">Prepared by:</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">Noted by:</td>
		    			<td class="f13" colspan="2">

		    			</td>
		    			<td class="f13" colspan="4">Approved by:</td>
		    			<td class="f13" colspan="2"></td>
		    		</tr>	  
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13 bor-btm" colspan="4"><br></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13 bor-btm" colspan="4"><br></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13 bor-btm" colspan="4"><br></td>
		    			<td class="f13" colspan="2"></td>
		    		</tr>  	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">
		    			<center><?php if($saved==0){ 
		    			 	echo $_SESSION['fullname']; 
		    			 } else {
		    			 	echo $prepared;
		    			 } ?></center>
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4"><center><?php echo $noted; ?></center></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4"><center><?php echo $approved; ?></center></td>
		    			<td class="f13" colspan="2"></td>
		    		</tr>  	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="2">
		    				Conforme:
		    			</td>
		    			<td class="f13 bor-btm" colspan="8"></td>
		    			<td class="f13" colspan="4"></td>
		    			<td class="f13" colspan="4">
		    			</td>
		    		</tr>  
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="7" align="center">Supplier's Signature Over Printed Name</td>
		    			<td class="f13" colspan="5"></td>
		    			<td class="f13" colspan="4">
		    			</td>
		    		</tr> 
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='rfq_id' value='<?php echo $rfq_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
		
    </script>