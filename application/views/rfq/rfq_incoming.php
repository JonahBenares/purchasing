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
        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
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
		.f10{
			font-size:10px!important;
		}
		.bor-btm{
			border-bottom: 1px solid #000;
		}
		.sel-des{
			border: 0px!important;
			width: 100%;
		}
		@media print{
			#prnt_btn, .reco, #printnotes{
				display: none;
			}
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
	        }
			.served{
	        	background-image: url('<?php echo base_url(); ?>assets/img/served.png')!important;
	        	background-repeat:no-repeat!important;
	        	background-size: contain!important;
	        	background-position: center center!important;
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
		.emphasis{
			/*border-bottom: 1px solid red!important;*/
			background-color: #ffe5e5!important;
		}
    </style>    

    <!-- <script type="text/javascript">
    	$(document).on("click", ".editbrp", function () {
		     var detail_id = $(this).data('id');
		     var offer = $(this).data('offer');
		     var price = $(this).data('price');
		     $(".modal #detail_id").val(detail_id);
		     $(".modal #offer").val(offer);
		     $(".modal #price").val(price);		  
		});
    </script>
	<div class="modal fade" id="editbrp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit Brand/Offer & Unit Price
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</h5>				
				</div>
				<form method='POST' action="<?php echo base_url(); ?>rfq/save_revision_rfq">
					<div class="modal-body">
						<div class="form-group">
							<h5 style="margin: 0px">Brand/Offer:
								<input type="text" name="offer" id='offer' class="form-control">
							</h5>
						</div>
						<div class="form-group">
							<h5 style="margin: 0px">Unit Price:
								<input type="text" name="price" id='price' class="form-control">
							</h5>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="detail_id" id='detail_id' class="form-control">
						<input type="hidden" name="rfq_id" id='rfq_id' value="<?php echo $rfq_id; ?>">
						<input type="submit" class="btn btn-info btn-block" value='Save changes'>
					</div>
				</form>
			</div>
		</div>
	</div> -->


    <div  class="pad">
    	<form method='POST' action="<?php echo base_url(); ?>rfq/">  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="" onclick="return quitBox('quit');" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>					
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a> -->
					<!-- 	<a  href='<?php echo base_url(); ?>rfq/override_rfq/' onclick="return confirm('Are you sure you want to override RFQ?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Override <u><b>RFQ</b></u></a> -->
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save"> 	
						<!--	<a  href='<?php echo base_url(); ?>rfq/save_revisions/' onclick="return confirm('Are you sure you want to save revisions?')" class="btn btn-info btn-md p-l-25 p-r-25"> Save <b>Revisions</b></a> -->
					</div>
					<h4 class="text-white"> <b>INCOMING</b> RFQ</h4>
					<p class="text-white">Instructions: When printing REQUEST FOR QUOTATION make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" >   <!-- add cancel or served on this class	 -->	
		    	<table class="table-bodrdered" width="100%" style="border:2px solid #000">
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
		    		<tr><td colspan="20" align="center"><b>REQUEST FOR QUOTATION</b></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td class="f13" colspan="2">Date: </td>
		    			<td class="f13 bor-btm" colspan="9"><?php echo date('F j, Y', strtotime($rfq_date)); ?></td>
		    			<td class="f13" colspan="1"></td>
		    			<td class="f13" colspan="2">RFQ No.:</td>
		    			<td class="f13 bor-btm" colspan="6"><?php echo $rfq_no."-".COMPANY; ?></td>
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
		    			<td class="f13" colspan="2">Notes:</td>
					 	<td class="f13 bor-btm" colspan="9">
					 	<?php echo $notes; ?>
					 	</td>
		    			
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
		    						<td class="f13" align="center" width="5%"><b>Qty</b></td>
		    						<td class="f13" align="center"><b>Unit</b></td>
		    						<td class="f13" align="center"><b>Item Description</b></td>
		    						<td class="f13" align="center"><b>Brand/Offer</b></td>
		    						<td class="f13" align="center" width="20%"><b>Unit Price</b></td>
		    						<!-- <td class="f13 reco" align="center" width="5%"><b><span class="fa fa-bars"></span></b></td> -->
		    					<?php 
		    					$x=1;
		    					foreach($items AS $it) {  ?>
		    					<tr>
		    						<td class="f13" align="center" rowspan='3'><?php echo $x; ?></td>
		    						<td class="f13" align="center" rowspan='3'><?php echo $it->uom; ?></td>
		    						<td class="f13" align="center" rowspan='3' style='width:35%'><?php echo $it->item_desc; ?></td>
		    						<td class="f13" align="center">
		    							<textarea rows="1" name="" class="sel-des emphasis" ></textarea>
		    						</td>
		    						<td class="f13" align="center" style="background-color: #ffe5e5">		    					
		    							<input type="text" name="" class="sel-des emphasis" autocomplete="off">
		    						</td>
		    					</tr>
		    					<tr>		    					
		    						<td class="f13" align="center">
		    							<textarea rows="1" name="offer" class="sel-des emphasis"></textarea>
		    						</td>
		    						<td class="f13" align="center" style="background-color: #ffe5e5">		    						
		    							<input type="text" name="" class="sel-des emphasis" autocomplete="off">		    						
		    						</td>
		    					</tr>
		    					<tr>
		    						<td class="f13" align="center">
		    							<textarea rows="1" name="offer" class="sel-des emphasis" ></textarea>
		    						</td>
		    						<td class="f13" align="center" style="background-color: #ffe5e5">		    						
		    							<input type="text" name="price" class="sel-des emphasis" autocomplete="off">	    							
		    						</td>
		    					</tr>

		    					<input type='hidden' name='detail_id<?php echo $x; ?>' value=''>
		    						<?php 
		    						$x++; } ?>
		    						<tr>
		    						<td class="f13" align="center" style='width:5%' rowspan="3"></td>
		    						<td class="f13" align="center" style='width:5%'  rowspan="3" ></td>
		    						<td class="f13" style='width:40%' rowspan="3"></td>
		    						<td class='f13' style='width:40%'></td>
		    						<td class='f13' style='width:10%; text-align: center'></td>
		    							<!-- <td class="f13 reco" align="center"><button type="button" data-toggle="modal" data-target="#editbrp" class="btn btn-xs btn-info editbrp" data-offer="<?php echo $com['offer']; ?>" data-price="<?php echo $com['price']; ?>"  data-id="<?php echo $com['detail_id']; ?>"><span class="fa fa-pencil"></span></button></td> -->
		    						</tr>
	    							<!-- <tr>
	    								<td class='f13' style='width:40%'></td>
	    								<td class='f13' style='width:10%; text-align: center'></td>
	    								<td class="f13 reco" align="center"><button type="button" data-toggle="modal" data-target="#editbrp" class="btn btn-xs btn-info editbrp" data-offer="<?php echo $com['offer']; ?>" data-price="<?php echo $com['price']; ?>"  data-id="<?php echo $com['detail_id']; ?>"><span class="fa fa-pencil"></span></button></td>
	    							</tr> -->
		    					<input type='hidden' name='count' value=''>
		    				</table>
		    			</td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	    	
		    		<tr><td class="f13" colspan="20">1. Quotation must be submitted on or before DATE HERE</td></tr>	
		    		<tr><td class="f13" colspan="20">2. Please Fill - Up :</td></tr>	    	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">- a. Price Validity</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7">
		    				<input type="text" name="validity" class="sel-des emphasis" autocomplete="off">
		    			</td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">- b. Payment Terms</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7">
		    				<input type="text" name="terms" class="sel-des emphasis" autocomplete="off">
		    			</td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">- c. Date of Delivery</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7">
		    				<input type="date" name="delivery_date" class="sel-des emphasis" autocomplete="off"></td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<!-- <tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">&nbsp; d. Item's Warranty</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7">
		    				<input type="text" name="warranty" class="sel-des emphasis" autocomplete="off">
		    			</td>
		    			<td class="f13" colspan="3"></td>

		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="5">&nbsp; e. Company's TIN Number</td>
		    			<td class="f13" colspan="3"></td>
		    			<td class="f13 bor-btm" colspan="7">
		    				<input type="text" name="tin" class="sel-des emphasis">
		    			</td>
		    			<td class="f13" colspan="3"></td>
		    		</tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="18">&nbsp; f. Vat 
		    				<input type="checkbox" name="vat" value='1' class='emphasis' > 
		    				<span class='fa fa-check'></span>
		    				</td>
		    		</tr> -->
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">Prepared by:</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">Noted by:</td>
		    			<td class="f13" colspan="2"></td>
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
		    				<center><?php echo $prepared; ?></center>
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">
		    				<center><?php echo $noted; ?></center>
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="4">
		    				<center><?php echo $approved; ?></center>
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    		</tr>  	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="2">
		    				Conforme:
		    			</td>
		    			<td class="f13 bor-btm" colspan="6"></td>
		    			<td class="f13" colspan="6"></td>
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