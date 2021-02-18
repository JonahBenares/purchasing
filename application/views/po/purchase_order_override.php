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
		.v-align{
			vertical-align: top;
		}
    </style>
    <!-- Modal -->
	<div class="modal fade" id="add-pr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add PR
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</h5>					
				</div>
				<form method="POST" action="<?php echo base_url(); ?>po/add_pr">
					<div class="modal-body">
						<div class="form-group">
							<h5 class="nomarg">PR NO:</h5>
							<select name='pr' id='pr' class="form-control" onchange='getPRInfo()'>
							<option value="" selected=""></option>
							<?php foreach($pr AS $p){ ?>
								<option value="<?php echo $p->pr_id; ?>"><?php echo $p->pr_no; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<h5 class="nomarg">Requestor:</h5>
							<h5 class="nomarg"><b><span id='requestor'></span></b></h5>
						</div>
						<div class="form-group">
							<h5 class="nomarg">Purpose:</h5>
							<h5 class="nomarg"><b><span id='purpose'></span></b></h5>
						</div>

						<div class="form-group">
							<h5 class="nomarg">Enduse:</h5>
							<h5 class="nomarg"><b><span id='enduse'></span></b></h5>
						</div>
						<input type="hidden" class="form-control" name="po_id" id="po_id">
					</div>
					<div class="modal-footer">
					<input type="submit" class="btn btn-primary btn-block" value='Add'>
					<input type='hidden' name='enduse_id' id='enduse_id' >
					<input type='hidden' name='purpose_id' id='purpose_id' >
					<input type='hidden' name='requested_by' id='requested_by'>
					</div>
					
					<input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
				</form>
			</div>
		</div>
	</div>

	

    <div  class="pad">

    	<form method='POST' action='<?php echo base_url(); ?>po/po_override'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="abtn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==1){ ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<?php } else if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">	
						<?php } ?>
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
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
		    		<tr><td colspan="20" align="center"><h4><b>PURCHASE ORDER</b></h4></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Date</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg"><b><?php echo date('F j, Y', strtotime($h['po_date'])); ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b>P.O. No.: <?php echo $h['po_no']; ?></b></h6></td>
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
		    		<tr>
		    			<td colspan="3"><h6 class="nomarg"><b>Telefax #:</b></h6></td>
		    			<td colspan="12"><h6 class="nomarg bor-btm"><b><?php echo $h['fax']; ?></b></h6></td>
		    			<td colspan="5"><h6 class="nomarg"><b></b></h6></td>
		    		</tr>
		    	<?php } ?>
		    		<tr id="pr-btn">
		    			<td colspan="20" style="padding-left: 10px">

		    				<a class="addPR btn btn-primary btn-xs" data-toggle="modal" href="#add-pr" data-id="<?php echo $po_id; ?>">
							  Add PR
							</a>
		    			</td>
		    		</tr>	
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
					    			<td class="" colspan="6" align="Left"><?php echo $prd['pr_no']; ?></td>
					    			<td class="" colspan="12" align="right">Requestor: <?php echo $prd['requestor']; ?> &nbsp;</td>
					    		</tr>
					    		<tr>
					    			<td class="" colspan="2" align="Left">&nbsp;Purpose:</td>
					    			<td class="" colspan="6" align="Left"><?php echo $prd['purpose']; ?></td>
					    			<td class="" colspan="12" align="right"><a href='<?php echo base_url(); ?>po/remove_pr/<?php echo $prd['po_pr_id']; ?>/<?php echo $po_id; ?>' style='color:red' onclick="return confirm('Are you sure you want to remove PR?')">[Remove PR]</a></td>
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
					    			<td colspan="" class="all-border v-align" align="center"><b><?php echo $x; ?></b></td>
					    			<td colspan="" class="all-border v-align" align="center"><b><input type='text' name='quantity<?php echo $x; ?>' id='quantity<?php echo $x; ?>' class='quantity' value='<?php echo $it['quantity']; ?>' style='width:50px; color:red' onblur='changePrice(<?php echo $x; ?>,<?php echo $a; ?>)'></b></td>
					    			<td colspan="" class="all-border v-align" align="center"><b><?php echo $it['unit']; ?></b></td>
					    			<td colspan="12" class="all-border v-align" align="left"><b class="nomarg"><?php echo $it['offer'].", ".$it['item']." " . $it['item_specs']; ?></b></td>
					    			<td colspan="2" class="all-border v-align" align="center"><b><input type='text' name='price<?php echo $x; ?>' id='price<?php echo $x; ?>'  value='<?php echo $it['price']; ?>' onblur='changePrice(<?php echo $x; ?>,<?php echo $a; ?>)' style='color:red; width:100px'></b></td>
					    			<td colspan="3" class="all-border v-align" align="right"><b class="nomarg"><input type='text' name='tprice<?php echo $x; ?>' id='tprice<?php echo $x; ?>' class='tprice' value="<?php echo number_format($tprice,2); ?>" style='text-align:right' readonly></b></td>
					    			
					    			
					    			<input type='hidden' name='reco_id<?php echo $x; ?>'  value='<?php echo $it['reco_id']; ?>'>
					    			<input type='hidden' name='item_id<?php echo $x; ?>'  value='<?php echo $it['item_id']; ?>'>
					    			<input type='hidden' name='offer<?php echo $x; ?>'  value='<?php echo $it['offer']; ?>'>
					    			<input type='hidden' name='po_items_id<?php echo $x; ?>'  value='<?php echo $it['po_items_id']; ?>'>
					    		</tr>
					    		<input type='hidden' name='po_pr_id<?php echo $x; ?>'  value='<?php echo $prd['po_pr_id']; ?>'>
					    		<?php 
					    		$x++;
					    			}
					    		} 

					    		$prprice = array_sum($pr_price);
					    		$total[] = $prprice; ?>
					    		<!--<tr>
					    			<td colspan="17" class="all-border" align="right"><b class="nomarg">TOTAL</b></td>
					    			<td colspan="3" class="all-border" align="right"><b class="nomarg"><input type='text' class='prtotal' id='total_pr<?php echo $a; ?>' value="<?php //echo number_format($prprice,2); ?>" readonly style='text-align:right'></b></td>
					    		<!--</tr>-->
					    		<input type="hidden" name = "prepared_by" value = "<?php echo $_SESSION['user_id'];?>">
					    		<input type='hidden' name='count_item'  value='<?php echo $x; ?>'>
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
		    				3. Sub-standard items shall be returned to supplier @ no cost to <?php echo JO_NAME;?>.<br>
		    				4. Payment term: <?php echo $terms; ?><br>
		    				5. Delivery Term: Exstock of Supplier.
		    			</td>
		    		</tr>
		    		<tr><td colspan="20"><br></td></tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Prepared by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b>Checked by:</b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>Approved by:</b></td>
		    			<td colspan="1"></td>4
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5" class="bor-btm"><b><br></b></td>
		    			<td colspan="1"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b><?php echo $prepared; ?></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="6"><b>
		    			<select name='approved' class="select-des emphasis" style="width: 100%" required>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select></b></td>
		    			<td colspan="1"></td>
		    			<td colspan="5"><b>
		    			<select name='approved' class="select-des emphasis" style="width: 100%" required>
			    			<option value=''>-Select-</option>
		    			</select></b></td>
		    			<td colspan="1"></td>
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
	    	<input type='hidden' name='po_id' value='<?php echo $po_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>