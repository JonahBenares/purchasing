   	<?php 
   	$CI =& get_instance();  ?>
   	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .pad{
        	padding:0px 30px 0px 30px
        }
        @media print{
        	.pad{
        	padding:0px 0px 0px 0px
        	}
        }
        .served{
        	background-image: url('<?php echo base_url(); ?>assets/img/served_aoq.png')!important;
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
		.table-borbold{
			border: 2px solid #000!important;
		}
		.table-borreg{
			border: 1px solid #000!important;
		}
		.f12{
			font-size:12px!important;
		}
		.f10{
			font-size:11px!important;
		}
		.f9{
			font-size:9px!important;
		}
		.bor-btm{
			border-bottom: 1px solid #000!important;
		}
		.bor-right{
			border-right: 1px solid #000!important;
		}
		.sel-des{
			border: 0px!important;
		}
		@media print{
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
	        }
			#prnt_btn{
				display: none;
			}
			#add_btn{
				display: none;
			}
			.f12{
				font-size:12px!important;
			}
			.f10{
				font-size:11px!important;
			}
			.text-red{
				color: red!important;
			}
			.bor-right{
				border-right: 1px solid #000!important;
			}
			.yellow-back{
			background-image: url('<?php echo base_url(); ?>assets/img/yellow.png')!important;
			}
			.green-back{
				background-image: url('<?php echo base_url(); ?>assets/img/green.png')!important;
			}
			.served{
	        	background-image: url('<?php echo base_url(); ?>assets/img/served_aoq.png')!important;
	        	background-repeat:no-repeat!important;
	        	background-size: contain!important;
	        	background-position: center center!important;
	        }
		}
		.text-white{
			color: #fff;
		}
		.text-red{
			color: red;
		}
		.yellow-back{
			background-image: url('<?php echo base_url(); ?>assets/img/yellow.png');
		}
		.green-back{
			background-image: url('<?php echo base_url(); ?>assets/img/green.png');
		}
		.emphasis{
			border-bottom: 2px solid red;
		}
		.v-al{
			vertical-align: text-top;
		}
		/* -------------------- Colors: Background */
		.slate   { background-color: #ddd; }
		.green   { background-color: #779126; }
		.blue    { background-color: #3b8ec2; }
		.yellow  { background-color: #eec111; }
		.black   { background-color: #000; }

		/* -------------------- Colors: Text */
		.slate select   { color: #000; }
		.green select   { color: #fff; }
		.blue select    { color: #fff; }
		.yellow select  { color: #000; }
		.black select   { color: #fff; }


		select#soflow, select#soflow-color {
			-webkit-appearance: button;
			-webkit-border-radius: 2px;
			-webkit-padding-end: 20px;
			-webkit-padding-start: 2px;
			-webkit-user-select: none;
			background-image: url(http://i62.tinypic.com/15xvbd5.png), -webkit-linear-gradient(#FAFAFA, #F4F4F4 40%, #E5E5E5);
			background-position: 98% center;
			background-repeat: no-repeat;
			border: 1px solid #AAA;
			color: #555;
			font-size: inherit;
			overflow: hidden;			
			padding: 6px 50px 6px 12px;
			text-overflow: ellipsis;
			white-space: pre-wrap;
			width: 100%;			
			font-size: 14px;
			box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
			-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
			-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
			transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		}


		select#soflow-color {
		    color: #fff;
		    background-image: url(http://i62.tinypic.com/15xvbd5.png), -webkit-linear-gradient( #ff9966 , #ec7955);
		    background-color: #da6946;
		    /*-webkit-border-radius: 20px;
		    -moz-border-radius: 20px;*/
		   /* border-radius: 20px;*/
		    padding-left: 15px;
		}
		select#soflow-color>option:hover{
			color: #000!important;
			background: #fff!important;
		}
		.op:hover{
			color: #000!important;
			background: #fff!important;
		}
    </style>
    <script>
    	function calculateAmount(count, vendor, position){
    
		   var quantity = document.getElementById("quantity_"+count).value;
		   var price = document.getElementById("price_"+count+"_"+vendor+"_"+position).value;
		   var amount = parseFloat(price) * parseFloat(quantity);
		   document.getElementById("amount_"+count+"_"+vendor+"_"+position).value  =amount;
		
		}

		function isNumberKey(txt, evt){
		   var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode == 46) {
		        //Check if the text already contains the . character
		        if (txt.value.indexOf('.') === -1) {
		            return true;
		        } else {
		            return false;
		        }
		    } else {
		        if (charCode > 31
		             && (charCode < 48 || charCode > 57))
		            return false;
		    }
		    return true;
		}

    </script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mixins.css">
    <script src="<?php echo base_url(); ?>assets/js/all-scripts.js"></script> 
    <?php if($saved==0){
    	$url = base_url()."aoq/save_aoq";
    } else { 
    	$url = base_url()."aoq/award_aoq";
     } ?>
    <div  class="pad">
    	<form method='POST' action='<?php echo $url ?>'>
    		<div id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>aoq/aoq_list" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
							<!-- <input type='submit' class="btn btn-info btn-md p-l-100 p-r-100" value='Done'> -->
							<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
							<?php if($saved==0){ ?>
								<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save AOQ">
							<?php } else { ?>
								<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Award">
							<?php } ?>
					</div>
					<p class="text-white p-l-250 p-r-250">Instructions: When printing ABSTRACT OF QUOTATION make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Landscape, <u>Paper Size</u>: A4 <u>Margin</u> : Custom (top: 0.11" , right:1.25", bottom: 0.11", left: 0.11") <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;width: 130%!important"> 
		    	<table class="table-boddered" width="100%" style="border:2px solid #000">
		    		<tr>
		    			<td width="2%"><br></td>
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
		    		<tr><td class="f10" colspan="21"  align="center"><h5><b>ABSTRACT OF QUOTATION</b></h5></td></tr>
		    		<?php foreach($head AS $h){ ?>
		    		<tr>
		    			<td class="f10" colspan="2" align="right">Department: &nbsp;</td>
		    			<td class="f10" colspan="9"><?php echo $h['department']; ?></td>		    			
		    			<td class="f10" colspan="2" align="right">Date: &nbsp;</td>
		    			<td class="f10" colspan="8"><?php echo $h['aoq_date']; ?></td>
		    		</tr>	
		    		<tr>
		    			<td class="f10" colspan="2" align="right">Purpose: &nbsp;</td>
		    			<td class="f10" colspan="9"><?php echo $h['purpose'];; ?></td>		    			
		    			<td class="f10" colspan="2" align="right">PR #: &nbsp;</td>
		    			<td class="f10" colspan="8"><?php echo $h['pr_no'];; ?> </td>
		    		</tr>
		    		<tr>
		    			<td class="f10" colspan="2" align="right">Enduse: &nbsp;</td>
		    			<td class="f10" colspan="9"><?php echo $h['enduse'];; ?></td>		    			
		    			<td class="f10" colspan="2" align="right">Date Needed: &nbsp;</td>
		    			<td class="f10" colspan="8"></td>
		    		</tr>	
		    		<tr>
		    			<td class="f10" colspan="2" align="right">Requested by: &nbsp;</td>
		    			<td class="f10" colspan="19"><?php echo $h['requestor'];; ?></td>
		    		</tr>
		    		<?php } ?>
		    		<tr>
		    			<td class="f10" colspan="6" align="center">
		    				<!-- <button id="add_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="">
							  <span class="fa fa-plus"></span> Add Item
							</button>	 -->						
		    			</td>
		    			<?php foreach($vendors AS $ven) { ?>
		    			<td colspan="5" class="f10 table-borbold"  align="center">
		    				<b><?php echo $ven['vendor']; ?></b><br>
		    				<?php echo $ven['phone']; ?><br>
		    				<?php echo $ven['contact']; ?>
		    			</td>
		    			<?php } ?>
		    			
		    		</tr>
		    		<tr>
		    			<td class="f9 table-borbold" align="center"><b>#</td>
		    			<td class="f9 table-borbold" align="center" colspan="3"><b>DESCRIPTION</td>
		    			<td class="f9 table-borbold" align="center"><b>QTY</td>
		    			<td class="f9 table-borbold" align="center"><b>UOM</td>
		    			<td class="f9 table-borbold" align="center" colspan="2"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    			<td class="f9 table-borbold" align="center" colspan="2"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    			<td class="f9 table-borbold" align="center" colspan="2"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    		</tr>

		    		<?php
		    		$x=1; 
		    		foreach($items AS $it){ ?>
		    			<input type='hidden' name='quantity_<?php echo $x; ?>' id="quantity_<?php echo $x; ?>" value='<?php echo $it->quantity; ?>'>
		    		<tr style='border:2px solid #000'>
		    			<td class="f10 table-borreg" align="center"><?php echo $x; ?></td>
		    			<td class="f10 table-borreg" align="left" colspan="3"><?php echo $it->item_description; ?></td>
		    			<td class="f10 table-borreg" align="center"><?php echo $it->quantity; ?></td>
		    			<td class="f10 table-borreg" align="center"><?php echo $it->uom; ?></td>


		    		<!-------------------------- VENDOR 1 ------------------------>
		    			<?php
		    			if($saved==0){
			    			$v=1; 
			    			foreach($vendors AS $ven) {
			    				 ?>
			    			<td colspan="5" style='border:1px solid #000;vertical-align: text-top;' >
			    				<table class="" width="100%" style='border:0px solid #000;'>						
			    					<tr>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_1" rows="1"></textarea>
				    					</td>
				    					<td width="20%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_1" name="price_<?php echo $x; ?>_<?php echo $v; ?>_1" 
				    						onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'1')" onkeypress="return isNumberKey(this, event)">
				    					</td>
				    					<td width="20%" class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_1" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_1">
				    					</td>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
				    					</td>
				    				</tr>
				    				<tr>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_2" rows="1"></textarea>
				    					</td>
				    					<td width="20%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_2" name="price_<?php echo $x; ?>_<?php echo $v; ?>_2"
				    						onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'2')" onkeypress="return isNumberKey(this, event)">
				    					</td>
				    					<td width="20%" class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_2" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_2">
				    					</td>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
				    					</td>
				    				</tr>
				    				<tr>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_3" rows="1"></textarea>
				    					</td>
				    					<td width="20%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_3" name="price_<?php echo $x; ?>_<?php echo $v; ?>_3" onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'3')" onkeypress="return isNumberKey(this, event)">
				    					</td>
				    					<td width="20%" class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_3" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_3">
				    					</td>
				    					<td width="40%" class="bor-btm bor-right"
				    						
				    					</td>
				    				</tr>
			    				</table>		    			
			    			</td>
			    			<input type='hidden' name='item_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $it->aoq_items_id; ?>'>
			    			<input type='hidden' name='vendor_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $ven['vendor_id']; ?>'>
			    			<?php 
			    			$v++;
			    			}
		    			} else { 
		    				
		    				foreach($vendors AS $ven) {
		    				
			    				 ?>
		    					
			    			<td colspan="5" style='border:1px solid #000;vertical-align: text-top;' >
			    				<table class="" width="100%" style='border:0px solid #000;'>		
			    				<?php 	
			    				$a=1;
			    				foreach($offers AS $of){
		    						if($ven['vendor_id'] == $of['vendor_id'] && $it->aoq_items_id == $of['item_id']){ ?>				
			    					<tr>
				    					<td width="40%" class="bor-btm bor-right">
				    						<?php echo $of['offer']; ?>
				    					</td>
				    					<td width="20%" class="bor-btm bor-right f10 <?php echo (($of['price']==$of['min']) ? 'yellow-back' : ''); ?> " align="center">
				    						<?php echo number_format($of['price'],2); ?>
				    					</td>
				    					<?php if($awarded==0){ ?>
				    					<td width="20%" class="bor-btm bor-right" align="center">				    						
				    						<?php echo number_format($of['amount'],2); ?><br>
				    						<input type="radio" name="award_<?php echo $a; ?>" value="1" >
				    					</td> 
				    					<?php } else { ?>
				    						<td width="20%" class="bor-btm bor-right <?php echo (($of['recommended'] == 1) ? 'green-back': ''); ?>" align="center">				    						
					    						<?php echo number_format($of['amount'],2); ?><br>
					    					</td> 
					    				<?php } 

					    				if($awarded==0){  ?>
				    					<td width="40%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="comments_<?php echo $a; ?>" rows="1"></textarea>
				    					</td>
				    					<?php } else { ?>
				    						<td width="40%" class="bor-btm bor-right">
				    							<?php echo $of['comments']; ?>
				    						</td>
				    					<?php } ?>
				    				</tr>
				    				<input type='hidden' name='offerid_<?php echo $a; ?>' value="<?php echo $of['aoq_offer_id']; ?>">
				    				<?php }
				    				$a++;
				    				}
				    				?>
				    			<input type='hidden' name='count_offer' value='<?php echo $a; ?>'>
			    				</table>		    			
			    			</td>
			    		
			    			<?php 
			    			
			    			}

		    			 } ?>
		    			
		    		
		    		</tr>	

		    	<?php $x++;
		    	} ?>
		    	<input type='hidden' name='item_count' value='<?php echo $x; ?>'>
		    	<input type='hidden' name='vendor_count' value='<?php echo $v; ?>'>
		    	<input type='hidden' name='aoq_id' value='<?php echo $aoq_id; ?>'>

		    		<tr>
		    			<td class="f10 table-borreg" align="center"><br></td>
		    			<td class="f10 table-borreg" align="left" colspan="3"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td class="f10 table-borreg" align="left" colspan="2"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg text-red" align="center"></td>

		    			<td class="f10 table-borreg" align="left" colspan="2"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td class="f10 table-borreg" align="left" colspan="2"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    		</tr>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"><br></td>
		    			<td colspan="5" class="f10 table-borreg text-red" align="center"><b>REMARKS</b></td>

		    			<td class="f10 table-borreg" align="left" colspan="5"></td>
		    			<td class="f10 table-borreg" align="left" colspan="5"></td>
		    			<td class="f10 table-borreg" align="left" colspan="5"></td>
		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    		<tr>
		    			<td class="" align="center">a.</td>
		    			<td colspan="5" class="f10" align="center">Price Validity</td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>		    			
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="" align="center">b.</td>
		    			<td colspan="5" class="f10" align="center">Payment Terms</td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>	    	
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>		    			
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>		
		    		</tr>
		    		<tr>
		    			<td class="" align="center">c.</td>
		    			<td colspan="5" class="f10" align="center">Date of Delivery</td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>

		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>

		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="" align="center">d.</td>
		    			<td colspan="5" class="f10" align="center">Item's Warranty</td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>		    			
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4" class="f10" align="center">Prepared by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10" align="center">Award Recommended by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10" align="center">Noted by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10" align="center">Approved by:</td>
		    			<td colspan="1"  class="" align="center"></td>
		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4" class="f10 bor-btm" align="center"></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10 bor-btm" align="center"></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10 bor-btm" align="center">
		    			<?php if($saved==0){ ?>
		    			<select name='approved' class='emphasis'>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else {
		    				echo $approved;
		    			} ?>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="3" class="f10 bor-btm" align="center">
		    			<?php if($saved==0){ ?>
		    				<select name='noted' class='emphasis'>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else {
		    				echo $noted;
		    			}?>
		    			</td>
		    			<td colspan="1" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="3"  class="" align="center">LEGEND:</td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="1"  class="green-back p-l-5 p-r-5" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4"  class="" align="left">Recommended Award</td>
		    			<td colspan="10"  class="" align="center"></td>

		    		</tr>
		    		<tr>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="3"  class="" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="1"  class="yellow-back p-l-5 p-r-5" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4"  class="" align="left">Lowest Price</td>
		    			<td colspan="10"  class="" align="center"></td>

		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    	</table>		    
	    	</div>
	    	</form>
	    	<br>
	    	<br>
    	
    </div>

    <!-- Modal -->
	
    
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}

    </script>