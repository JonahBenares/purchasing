   	<?php 
   	$CI =& get_instance(); ?>
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

        .pad-lr{
        	padding: 0px 2px;
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
		    /*border-radius: 20px;*/
		    padding-left: 15px;
		}
		.op:hover{
			color: #000!important;
			background: #fff!important;
		}
    </style>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mixins.css">
    <script src="<?php echo base_url(); ?>assets/js/all-scripts.js"></script> 
    <div  class="pad">
   			<?php if($saved==1){
			$url =  base_url().'aoq/aoq_complete';
		} else {
			$url =  base_url().'aoq/aoq_save';
		}
	?>
    		<form method='POST' action='<?php echo $url ?>'>
    		<div id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a onclick="return quitBox('quit');" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==1){ 
					
							if($completed==0){ ?>
							<input type='submit' class="btn btn-info btn-md p-l-100 p-r-100" value='Done'>
							<?php } if($completed==1){ ?>
							<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>

						<?php }
						 } if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">
						<?php } ?>    				
					</div>
					<p class="text-white p-l-250 p-r-250">Instructions: When printing ABSTRACT OF QUOTATION make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Landscape, <u>Paper Size</u>: A4 <u>Margin</u> : Custom (top: 0.11" , right:1.25", bottom: 0.11", left: 0.11") <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" <?php echo (($served==1) ? 'class="served"' : ''); ?>>    		  			
		    	<table class="table-borsered" width="100%" style="background: #fff;border: 1px solid #000">
		    		<tr>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    			<td width="3%"><br></td>
		    		</tr>		    	
		    		<tr><td colspan="33" class="f10"  align="center"><h5><b>ABSTRACT OF QUOTATION</b></h5></td></tr>
		    		<?php foreach($head AS $h) { ?>
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Department: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo $h['department']; ?></td>		    			
		    			<td colspan="3" class="f12" align="right">Date: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo date('F j, Y', strtotime($h['aoq_date'])); ?></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Purpose: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo $h['purpose']; ?></td>		    			
		    			<td colspan="3"class="f12" align="right">PR #: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo $h['pr']; ?></td>
		    		</tr>
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Enduse: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo $h['enduse']; ?></td>		    			
		    			<td colspan="3"class="f12" align="right">Date Needed: &nbsp;</td>
		    			<td colspan="13" class="f12" ><?php echo date('F j, Y', strtotime($h['date_needed'])); ?></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="4" class="f12"  align="right">Requested by: &nbsp;</td>
		    			<td colspan="29" class="f12" ><?php echo $h['requested']; ?></td>
		    		</tr>
		    		<?php } ?>
		    		<!-- <tr><td class="f10"  align="center"><br></td></tr> -->
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="9" class="f10"  align="center">
		    			<?php if($saved==0){ ?>
		    				<button id="add_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="<?php echo $aoq_id; ?>">
							  <span class="fa fa-plus"></span> Add Item
							</button>
							<?php } ?>
										
		    			</td>


		    			<!-- loop ka here -->
		    			<?php foreach($supplier AS $sup){ ?>
		    			<td colspan="6" class="f10 table-borbold"  align="center">
		    				<b><?php echo $sup['supplier_name']; ?></b><br>
		    				<?php echo $sup['contact']; ?><br>
		    				<?php echo $sup['phone']; ?>
		    			</td>
		    			<?php } ?>
		    			
		    		
		    		</tr>
		    		<tr>
		    			<td class="f9 table-borbold "align="center"><b class="p-r-10 p-l-10">#</td>
		    			<td colspan="6" class="f9 table-borbold" align="center"><b>DESCRIPTION</td>
		    			<td class="f9 table-borbold" align="center"><b>QTY</td>
		    			<td class="f9 table-borbold" align="center"><b>UOM</td>

		    			<td colspan="3" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="3" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="3" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="3" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td class="f9 table-borbold" align="center"><b>U/P</b></td>
		    			<td class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			
		    		</tr>
		    		<?php 
		    		if(!empty($aoq_item)){
		    			$x=1;
		    			$a=1;
		    			$b=1;
		    			foreach($aoq_item AS $it){ ?>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"><?php echo $x; ?></td>
		    			<td colspan="6" class="f10 table-borreg" align="left"> <?php echo $it['item']; ?></td>
		    			<td class="f10 table-borreg" align="center"><?php echo $it['qty']; ?></td>
		    			<td class="f10 table-borreg" align="center"><?php echo $it['uom']; ?></td>
		    			<?php foreach($supplier AS $sup){ 

	    				$reco = $CI->get_rfq_item("recommended", $sup['supplier_id'], $it['item_id']); 
	    				$up = $CI->get_rfq_item("unit_price", $sup['supplier_id'], $it['item_id']);
	    				$user_reco = $CI->get_aoq_others('reco', $sup['supplier_id'], $it['item_id'], $aoq_id);
						$comment = $CI->get_aoq_others('comments', $sup['supplier_id'], $it['item_id'], $aoq_id);
						$offer= $CI->get_rfq_item("offer", $sup['supplier_id'], $it['item_id']);
	    				$total = $it['qty']*$up;
	    				?>
		    			<td colspan="3" class="f10 table-borreg pad-lr" align="left">
		    		
		    				<b class="text-red">
		    				<?php echo $offer; ?>
		    				</b>
		    			, <?php echo $CI->get_rfq_item("item", $sup['supplier_id'], $it['item_id']); ?></td>
		    			<td class="f10 table-borreg <?php echo (($it['min']==$up && $up!=0) ? 'yellow-back' :''); ?> p-l-5 p-r-5" align="center">
		    			<?php echo number_format($up,2); ?>
		    			<?php if($saved=='1' && $completed==0){ ?>
		    				<br>
		    			<input type="radio" name="reco<?php echo $a; ?>" value='<?php echo $sup['supplier_id']."_".$it['item_id']."_".$up."_".$it['qty']."_".$offer ; ?>' required>
		    			
		    			<?php } ?>
		    			</td>
		    			<td class="f10 table-borreg <?php echo (($user_reco != 0) ? ' green-back' : ''); ?> p-l-5 p-r-5" align="center"><?php echo number_format($total,2); ?></td>
		    			<td class="f10 table-borreg text-red" align="center">
		    			<?php if($saved=='1' && $completed==0){ ?>
		    				<textarea cols="5" rows="3" name='comments<?php echo $b; ?>'></textarea>
		    				<input type='hidden' name='supplier<?php echo $b; ?>' value="<?php echo $sup['supplier_id']; ?>">
		    				<input type='hidden' name='item<?php echo $b; ?>' value="<?php echo $it['item_id']; ?>">
		    			<?php } else if($saved=='1' && $completed == '1'){ 
		    				echo $comment;
		    			} ?>
		    			</td>
		    			<!-- and delete the other two below salamats -->
		    			<?php $b++; } ?>
		    			<input type='hidden' name='count_comment' value='<?php echo $b; ?>'>
		    			<?php 
		    			$a++; ?>		    	
		    		</tr>
		    		<?php $x++; } 
		    		} ?>
		    		<input type='hidden' name='count_item' value='<?php echo $a; ?>'>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="6" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<!-- loop ka here -->
		    			<td colspan="3" class="f10 table-borreg" align="left"><br></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<!-- and delete the other two below salamats -->

		    			<td colspan="3" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td colspan="3" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td colspan="3" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    		</tr>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="8" class="f10 table-borreg text-red" align="center"><b>REMARK</b></td>

		    			<!-- loop ka here -->
		    			<!-- and delete the other two below salamats -->

		    			<td colspan="6" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="6" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="6" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="6" class="f10 table-borreg" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td class="" align="center">a.</td>
		    			<td colspan="8" class="f10" align="center">Price Validity</td>
		    			<?php foreach($supplier AS $sup){ ?>
		    			<td colspan="4" class="f10 bor-btm" align="left"><?php echo $sup['validity']; ?><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<?php } ?>
		    			<!-- <td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td> -->
		    		</tr>
		    		<tr>
		    			<td class="" align="center">b.</td>
		    			<td colspan="8" class="f10" align="center">Payment Terms</td>
		    			<?php foreach($supplier AS $sup){ ?>
		    			<td colspan="4" class="f10 bor-btm" align="left"><?php echo $sup['terms']; ?><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<?php } ?>
		    			<!-- <td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td> -->
		    		</tr>
		    		<tr>
		    			<td class="" align="center">c.</td>
		    			<td colspan="8" class="f10" align="center">Date of Delivery</td>
		    			<?php foreach($supplier AS $sup){ ?>
		    			<td colspan="4" class="f10 bor-btm" align="left"><?php echo (($sup['delivery']!='') ? date('F j, Y', strtotime($sup['delivery'])) : ''); ?><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<?php } ?>
		    			<!-- <td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td> -->
		    		</tr>
		    		<tr>
		    			<td class="" align="center">d.</td>
		    			<td colspan="8" class="f10" align="center">Item's Warranty</td>
		    			<?php foreach($supplier AS $sup){ ?>
		    			<td colspan="4" class="f10 bor-btm" align="left"><?php echo $sup['warranty']; ?><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<?php } ?>
		    			<!-- <td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td>
		    			<td colspan="2" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="3" class="f10" align="left"><br></td> -->
		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="5" class="f10" align="center">Prepared by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10" align="center">Award Recommended by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10" align="center">Noted by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10" align="center">Approved by:</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="5" class="f10 bor-btm" align="center"><?php echo $_SESSION['fullname']; ?></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center"><?php echo $requested; ?></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center">
		    				<?php if($saved==0){ ?>
		    			<select name='approved' required>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else { 
		    			 echo $approved; 
		    			 } ?>
		    			</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center">
		    				<?php if($saved==0){ ?>
		    				<select name='noted' required>
			    			<option value=''>-Select-</option>
			    			<?php foreach($employee AS $emp){ ?>
			    				<option value='<?php echo $emp->employee_id; ?>'><?php echo $emp->employee_name; ?></option>
			    			<?php } ?>
		    			</select>
		    			<?php } else { 
		    			 echo $noted; 
		    			 } ?>
		    			</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="3"  class="" align="center">LEGEND:</td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="1"  class="green-back p-l-5 p-r-5" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4"  class="" align="left">Recommended Award</td>
		    			<td colspan="19"  class="" align="center"></td>

		    		</tr>
		    		<tr>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="2"  class="" align="center"></td>
		    			<td colspan="3"  class="" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="1"  class="yellow-back p-l-5 p-r-5" align="center"></td>
		    			<td colspan="1"  class="" align="center"></td>
		    			<td colspan="4"  class="" align="left">Lowest Price</td>
		    			<td colspan="19"  class="" align="center"></td>

		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>

		    	</table>	    
	    	</div>
	    	<input type='hidden' name='count' value="4">
    		<input type='hidden' name='aoq_id' value="<?php echo $aoq_id; ?>">
    		</form>
    </div>
<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel" style="text-align: left">Add Item 
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          		<span aria-hidden="true">&times;</span>
			        	</button>
			        </h5>							       
		      	</div>
		      	<form method='POST' action="<?php echo base_url(); ?>aoq/add_item">
		      	<div class="modal-body">
		        <div class="form-group">
                    <h5 class="m-b-0" style="text-align: left">Item Description:</h5>
                    <select name="item" id="soflow-color" class="semi-square" required="">
					  	<option value='' selected>-Select Item-</option>
					  	<?php foreach($items AS $it){ ?>
					  		<option value="<?php echo $it->item_id; ?>"><?php echo $it->item_name . ", " . $it->item_specs; ?></option>
					  	<?php } ?>
					</select>
                </div>                                   
                <div class="form-group">
                    <h5 class="m-b-0" style="text-align: left">Qty:</h5>
                    <input type='text' name="qty" class="form-control" required="" autocomplete="off" onkeypress="return isNumberKey(this, event)">
                </div>

		      	</div>
		      	<div class="modal-footer">
			        <input type="submit" class="btn btn-primary btn-block" value="Add">
		      	</div>
		      	<input type='hidden' name='aoq_id' value="<?php echo $aoq_id; ?>">
		      	<input type='hidden' name='count' value="4">
		      	</form>
		    </div>
	  	</div>
	</div>		    	
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>