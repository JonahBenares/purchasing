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
	</head>

  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .cancel{
        	background-image: url('../../assets/img/cancel.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
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
		.sel-des{
			border: 0px!important;
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
		.text-red{
			color: red;
		}
		.nomarg{
			margin: 0px 2px 0px 2px;
		}
    </style>
    <!-- Modal -->
	
    <div  class="pad ">

    	<form method='POST' action='<?php echo base_url(); ?>po/po_complete'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="<?php echo base_url(); ?>po/po_list" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<!-- <a  href='<?php echo base_url(); ?>po/revise_po/<?php echo $po_id; ?>' onclick="return confirm('Are you sure you want to revise PO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>PO</b></u></a> -->
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>PO</b></u></a>
							<a  href="<?php echo base_url(); ?>po/delivery_receipt/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>DR</b></u></a>
						<a  href="<?php echo base_url(); ?>po/rfd_prnt/<?php echo $po_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <u><b>RFD</b></u></a>
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;">    		  			
		    	<table class="table-borddered" width="100%" style="border:2px solid #000">
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
		    			<td colspan="15"><h4 style="margin: 0px"><b>CENTRAL NEGROS POWER RELIABILITY, INC.</b></h4></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center">Office: 88 Corner Rizal-Mabini Sts., Bacolod City</td></tr>
		    		<tr><td class="f13" colspan="20" align="center">Tel. No.: (034) 435-1932/476-7382</td></tr>
		    		<tr><td class="f13" colspan="20" align="center">Telefax: (034) 435-1932</td></tr>
		    		<tr><td class="f13" colspan="20" align="center">Plant Site: Purok San Jose, Barangay Calumangan, Bago City</td></tr>
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b><br><small class="text-red">REVISED</small></h4></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no'] . (($revision==0) ? '' : '.r'.$revision); ?></b></h6></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Supplier:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['supplier']; ?></b></h6></td>
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
		    		<?php } ?>
		    		<!-- LOOp Here -->
		    		<?php 
		    		if(!empty($prdetails)){
		    			$a=1;
		    			$x=1;
		    			foreach($prdetails AS $prd){ ?>	
		    		<tr>
		    			<td class="f13" colspan="20" align="center" style="padding: 10px!important">
		    				<table  class="table-bodrdered" width="100%" style="border:1px solid #000;">
		    					<tr>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    		</tr>
					    		<tr>
					    			<td class="" colspan="2" align="Left">&nbsp;PR No:</td>
					    			<td class="" colspan="12" align="Left"><?php echo $prd['pr_no']; ?></td>
					    			<td class="" colspan="6" align="right">Requestor: <?php echo $prd['requestor']; ?> &nbsp;</td>
					    		</tr>
					    		<tr>
					    			<td class="" colspan="2" align="Left">&nbsp;Purpose:</td>
					    			<td class="" colspan="18" align="Left"><?php echo $prd['purpose']; ?></td>
					    		</tr>
					    		<tr>
					    			<td class="" colspan="2" align="Left">&nbsp;Enduse:</td>
					    			<td class="" colspan="18" align="Left"><?php echo $prd['enduse']; ?></td>
					    		</tr>
					    	<!--	<tr id="item-btn">
					    			<td colspan="20" style="padding-left: 10px">
					    				<button type="button" class="btn btn-info btn-xs" onclick="addItemPo() ">
										  Add Item/s
										</button>
					    			</td>
					    		</tr>-->
		    					<tr>
					    			<td colspan="" class="all-border" align="center"><b>#</b></td>
					    			<td colspan="" class="all-border" align="center"><b>Qty</b></td>
					    			<td colspan="" class="all-border" align="center"><b>Unit</b></td>
					    			<td colspan="12" class="all-border" align="center"><b>Description</b></td>
					    			<td colspan="2" class="all-border" align="center"><b>Unit Price</b></td>
					    			<td colspan="3" class="all-border" align="center"></td>
					    		</tr>
					    		<?php
					    		
					    		$pr_price=array();
					    		 foreach($items AS $it){ 
					    		 	if($prd['po_pr_id']==$it['po_pr_id']){
					    		 	$tprice = $it['quantity'] * $it['price'];
					    		 	$pr_price[] = $tprice; ?>
					    		<tr>
					    			<td colspan="" class="all-border" align="center"><b><?php echo $x; ?></b></td>
					    			<td colspan="" class="all-border" align="center"><b><?php echo $it['quantity']; ?></b></td>
					    			<td colspan="" class="all-border" align="center"><b><?php echo $it['unit']; ?></b></td>
					    			<td colspan="12" class="all-border" align="left"><b class="nomarg"><?php echo $it['offer'].", ".$it['item']." " . $it['item_specs']; ?></b></td>
					    			<td colspan="2" class="all-border" align="center"><b><?php echo number_format($it['price'],2); ?></b></td>
					    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><?php echo number_format($tprice,2); ?></b></td>
					    			
					    			
					    		</tr>
					    	
					    		<?php 
					    		$x++;
					    			}
					    		} 

					    		$prprice = array_sum($pr_price);
					    		$total[] = $prprice; ?>
					    	

					    	
		    				</table>
			    		</td>
			    	</tr>
			    	<?php 
			    	$a++;
			    	} 
			    	$total = array_sum($total);
			    	?>
			    	<!-- LOOp Here --> 
			    	<tr>
		    			<td class="f13" colspan="20" align="center" style="padding: 10px!important">
		    				<table  class="table-bodrdered" width="100%" style="border:0px solid #000;">
		    					<tr>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    		</tr>
					    		<tr>
					    			<td colspan="17" class="all-border" align="right"><b class="nomarg">GRAND TOTAL</b></td>
					    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><span class="pull-left">₱</span><span id='grandtotal'><?php echo number_format($total,2); ?></span></b></td>
					    		</tr>
		    				</table>
			    		</td>
			    		<input type="hidden" name = "prepared_by" value = "<?php echo $_SESSION['user_id'];?>">
			    	</tr>
			    	<?php } else { ?>
			    	<tr>
		    			<td class="f13" colspan="20" align="center" style="padding: 10px!important">
		    				<table  class="table-bodrdered" width="100%" style="border:0px solid #000;">
		    					<tr>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    			<td width="5%"></td>
					    		</tr>
					    		
		    				</table>
			    		</td>
			    	</tr>
			    	<?php } ?> 
		    		<tr>
		    			<td colspan="20">
		    				<i><?php echo $notes; ?></i>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" style="padding: 10px!important">
		    				<br>Terms & Conditions:<br>
		    				1. Price is inclusive of taxes.<br>
		    				2. PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.<br>
		    				3. Sub-standard items shall be returned to supplier @ no cost to CENPRI.<br>
		    				4. Payment term: <?php echo $terms; ?><br>
		    				5. Delivery Term: Exstock of Supplier.
		    			</td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="7"><b>Prepared by:</b></td>
		    			<td colspan="2"></td>
		    			<td colspan="7"><b>Approved by:</b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="7" class="bor-btm"><b><br></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="7" class="bor-btm"><b><br></b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="7"><b><?php echo $prepared; ?></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="7"><b><?php echo $approved; ?></b></td>
		    			<td colspan="2"></td>
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
	    	<input type='hidden' name='po_id' value=''>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>