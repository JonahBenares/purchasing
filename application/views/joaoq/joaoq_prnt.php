   	<?php 
   	$CI =& get_instance();  ?>
   	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mixins.css">
    <style type="text/css">
    	html,body{
    		font-size:13px!important;
    	}
    	textarea, input, select{
    		font-size:13px!important;
    	}
    	.padding-0{
    		padding: 0!important;
    	}
    	.bor-btm{
			border-bottom: 1px solid #000!important;
		}
		.bor-btm-red{
			border-bottom: 2px solid red!important;
		}
		.bor-right{
			border-right: 1px solid #000!important;
		}
		.bor-left{
			border-left: 1px solid #000!important;
		}
		.bor-top{
			border-top: 1px solid #000!important;
		}
		.sel-des{
			border: 0px!important;
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
        .served{
        	background-image: url('<?php echo base_url(); ?>assets/img/served_aoq.png')!important;
        	background-repeat:no-repeat!important;
        	background-size: contain!important;
        	background-position: center center!important;
        }
}
		/*.f12{
			font-size:12px!important;
		}
		.f10{
			font-size:12px!important;
		}
		.f9{
			font-size:9px!important;
		}*/
    </style>

    <script>
    	function calculateAmount(count, vendor, position){    
		   var offerqty = document.getElementById("offerqty_"+count+"_"+vendor+"_"+position).value;
		   //var quantity = document.getElementById("quantity_"+count).value;
		   var price = document.getElementById("price_"+count+"_"+vendor+"_"+position).value;
		   var amount = parseFloat(price) * parseFloat(offerqty);
		   document.getElementById("amount_"+count+"_"+vendor+"_"+position).value  =amount;		
		}

		function calculateAmount2(count){
        //alert(count);
		   var offerqty = document.getElementById("offerqty_"+count).value;
		   /*var quantity = document.getElementById("quantity_"+count).value;*/
		   var price = document.getElementById("price_"+count).value;
		   //alert(quantity);
		   //alert(price);
		    var p = price.replace(",", "");
		   
		   var amount = parseFloat(p) * parseFloat(offerqty);
		   document.getElementById("amount_"+count).value  =amount;		
		}

		function calculateAmount3(counta,countb){
        //alert(count);
		   var offerqty = document.getElementById("materialsqty_"+counta+"_"+countb).value;
		   /*var quantity = document.getElementById("quantity_"+count).value;*/
		   var price = document.getElementById("price_"+counta+"_"+countb).value;
		   //alert(quantity);
		   //alert(price);
		    var p = price.replace(",", "");
		   
		   var amount = parseFloat(p) * parseFloat(offerqty);
		   document.getElementById("amount_"+counta+"_"+countb).value  =amount;		
		}

		function calculateAmount4(count, vendor, position){    
		   var offerqty = document.getElementById("materialsqty_"+count+"_"+vendor+"_"+position).value;
		   //var quantity = document.getElementById("quantity_"+count).value;
		   var price = document.getElementById("price_"+count+"_"+vendor+"_"+position).value;
		   var amount = parseFloat(price) * parseFloat(offerqty);
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

    <script src="<?php echo base_url(); ?>assets/js/all-scripts.js"></script> 

    <?php if($saved==0 && $open==0 && $draft==0){
    	$url = base_url()."joaoq/save_aoq";
    } else if($saved==1 && $open==0){ 
    	$url = base_url()."joaoq/award_aoq";
     } else if($saved==1 && $open==1){ 
    	$url = base_url()."joaoq/update_aoq";
     }else if($saved==0 && $open==0 && $draft==1){ 
    	$url = base_url()."joaoq/save_aoq_draft";
     }?>

    <div  class="pad">
    	<form method='POST' action='<?php echo $url ?>' onsubmit="return confirm('Do you really want to submit the form?');">
    		<div id="prnt_btn " style="position:fixed; left:300px">
		    	<div class="btn-group">
					<a href="<?php echo base_url(); ?>joaoq/joaoq_list" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
					<?php if($saved==1 && $open==0){ ?> 
					<a href='<?php echo base_url(); ?>joaoq/open_aoq_before/<?php echo $jor_aoq_id; ?>' class="btn btn-info btn-md p-l-100 p-r-100">Open AOQ</a>
					<?php } if($saved==1 ){ ?>
					<a href="<?php echo base_url(); ?>joaoq/export_aoq_prnt/<?php echo $jor_aoq_id; ?>" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-export"></span> Export</a>
					<?php } ?>
					<?php  if($saved==0 && $open==0){ ?>
						<input type='submit' name ="submit" class="btn btn-warning btn-md p-l-100 p-r-100" value="Save AOQ As Draft" >
						<input type='submit' name ="submit" class="btn btn-primary btn-md p-l-100 p-r-100" value="Save AOQ" >
						
					<?php } else if ($saved==1 && $open==0 && $awarded==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Award">
					<?php } else if ($saved==1 && $open==1 && $awarded==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save Changes">
					<?php } ?>
				</div>
			</div>		

	    	<div style="padding-top:50px;" class = "">  <!-- -insert in class this <?php if($served==1){ echo 'served';} ?> -->
		    	<table class="table-bosdered" style="width:4000px;background: #fff;border:1px solid #000;margin:10px ;" >
		    		<tr><td ><h5><b>JO AOQ - <?php echo $jor_aoq_id; ?></b></h5></td></tr>
		    		<tr>
		    			<td colspan="2">
		    				<table class="tables-bordersded" width="100%">
		    					<tr>
		    						<td width="30%"></td>
		    						<td class="f10"align=""><h5><b>ABSTRACT OF QUOTATION</b></h5></td>
					    			<td class="f10"></td>		    		
					    			<td class="f10"></td>
		    					</tr>
		    				</table>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="2">
		    				<table class="tables-borderedds" width="100%">
		    					<?php foreach($head AS $h){ ?>
					    		<tr>
					    			<td width="1%"></td>
					    			<td width="9%" class="f10" align="right">JO No.: &nbsp;</td>
					    			<td width="99%"class="f10"><?php echo $h['jo_no']."-".COMPANY; ?></td>		    		
					    			<td width="1%" class="f10"></td>
					    		</tr>	
					    		<tr>
					    			<td></td>
					    			<td class="f10" align="right">Project Title: &nbsp;</td>
					    			<td class="f10"><?php echo $h['purpose']; ?></td>		    		
					    			<td class="f10"></td>
					    		</tr>
					    		<tr>
					    			<td></td>
					    			<td class="f10" align="right">Requested By: &nbsp;</td>
					    			<td class="f10"><?php echo $h['requested_by']; ?></td>		    		
					    			<td class="f10" ></td>
					    		</tr>	
					    		<tr>
					    			<td></td>
					    			<td class="f10" align="right">Department: &nbsp;</td>
					    			<td class="f10"><?php echo $h['department']; ?></td>
					    		</tr>
					    		<?php } ?>
		    				</table>
		    			</td>
		    		</tr>
		    		
		    		<tr>
		    			<td class="f10"  align="center" width="20%" style="<?php echo ($count_vendors==3) ? "width:350px" : "width:600px"; ?>">
		    				<!-- <button id="add_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="">
							  <span class="fa fa-plus"></span> Add Item
							</button>	 -->						
		    			</td>
		    			<?php foreach($vendors AS $ven) { ?>
		    			<td  class="f10 bor-right bor-left bor-top"  align="center" style="width:700px">
		    				<b><?php echo $ven['vendor']; ?></b><br>
		    				<?php echo $ven['phone']; ?><br>
		    				<?php echo $ven['contact']; ?>
		    			</td>
		    			<?php } ?>
		    		</tr>

		    		<tr style='border:1px solid #000'>
		    			<td style="padding: 0px;border-right: 1px solid #000;">
		    				<table class="f" width="100%" style='border:px solid #000;margin: 0px;'>
		    					<tr style="height:40px;border-bottom:1px solid #000">
		    						<td width="3%" class="f9 table-borbold" width="5%" align="center"><b>#</td>
					    			<td width="72%" class="f9 table-borbold" align=""><b>DESCRIPTION</td>
					    			<td width="12%" class="f9 table-borbold" width="10%" align="center"><b>QTY</td>
					    			<td width="12%" class="f9 table-borbold" width="10%" align="center"><b>UOM</td>
					    		</tr>	
		    				</table>	
		    			</td>
		    			<td style="padding: 0px;border-right: 1px solid #000;">
		    				<table class="tablde-bordered" width="100%" style='border:px solid #000;margin: 0px;'>
		    					<tr style="height:40px;border-bottom:1px solid #000">
		    						<td width="25%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
					    			<td width="6%" class="f9 table-borbold" align="center"><b>CURR</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
					    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    					</tr>		
		    				</table>
		    			</td>	
		    			<td style="padding: 0px;border-right: 1px solid #000;">
		    				<table class="f" width="100%" style='border:px solid #000;margin: 0px;'>
		    					<tr style="height:40px;border-bottom:1px solid #000">
		    						<td width="25%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
					    			<td width="6%" class="f9 table-borbold" align="center"><b>CURR</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
					    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    					</tr>		
		    				</table>
		    			</td>
		    			<td style="padding: 0px;border-right: 1px solid #000;">
		    				<table class="f" width="100%" style='border:px solid #000;margin: 0px;'>
		    					<tr style="height:40px;border-bottom:1px solid #000">
		    						<td width="25%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
					    			<td width="6%" class="f9 table-borbold" align="center"><b>CURR</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
					    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
					    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
		    					</tr>		
		    				</table>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td class="f10" align="" colspan="3" style="border-left:0px solid #000; border-right:0px solid #000">&nbsp;<b><?php echo $h['general_desc']; ?></td>
					</tr>
		    		<?php
		    		$x=1; 
		    		foreach($items AS $it){ ?>
		    		<input type='hidden' name='quantity_<?php echo $x; ?>' id="quantity_<?php echo $x; ?>" value='<?php echo $it->quantity; ?>'>
		    		
		    		<tr style='border:1px solid #000'>
		    			<td style="padding: 0px;border-right: 1px solid #000;vertical-align: top;">
		    				<table class="f" width="100%" style='border:px solid #000;margin: 0px;'>		    					
		    					<tr>
		    						<td></td>
					    			<td class="f10 " align="" colspan="3" style="border-left:0px solid #000; border-right:0px solid #000"><b><!-- <?php echo $h['general_desc']; ?> --></td>
		    					</tr> 
		    					<tr >
		    						<td width="3%" class="f10 table-borreg" style="vertical-align: text-top;" align="center"><?php echo $x; ?></td>
					    			<td width="72%" class="f10 table-borreg" style="vertical-align: text-top;" align="left" ><?php echo nl2br($it->scope_of_work); ?></td>
					    			<td width="12%" class="f10 table-borreg" style="vertical-align: text-top;" align="center"><?php echo $it->quantity; ?></td>
					    			<td width="12%" class="f10 table-borreg" style="vertical-align: text-top;" align="center"><?php echo $it->uom; ?></td>
					    		</tr>
		    				</table>
		    			</td>

		    			<?php
		    			if($saved==0 && $open==0 && $draft==0){
			    			$v=1; 
			    			foreach($vendors AS $ven) {
			    		?>
			    			<td style='vertical-align:text-bottom;padding-right: 1px;' >
			    				<table class="tabale" width="100%" height="30%" style='margin-bottom: 0px;'>	
				    				<!-- <tr style="border-bottom:1px solid #000">
			    						<td width="51%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
						    			<td width="6%" class="f9 table-borbold" align="center"><b>CURR</b></td>
						    			<td width="11%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
						    			<td width="11%" class="f9 table-borbold" align="center"><b>U/P</b></td>
						    			<td width="11%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
			    					</tr>		 -->
			    					<!-- -------------------------------- FOR SERVICE --------------------------------	 -->			
			    					<tr style="height:100px">
				    					<td width="25%" class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_1" 
				    							style="width: 100%;height: 100%;background:#f6fffe"></textarea>
				    					</td>
				    					<td width="6%" class="bor-btm bor-right f10" align="center">
				    							<select style="width: 100%;height: 100%;background:#f6fffe" name='currency_<?php echo $x; ?>_<?php echo $v; ?>_1'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_1" name="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_1" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_1" name="price_<?php echo $x; ?>_<?php echo $v; ?>_1" onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'1')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td width="10%" class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_1" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_1" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td  width="15%"class="bor-btm bor-right" style="background:#f6fffe">
				    					</td>
				    				</tr>
				    				<!-- -------------------------------- FOR SERVICE --------------------------------	 -->			
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->			
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="materials_offer_<?php echo $x; ?>_<?php echo $v; ?>_1" 
				    							style="width: 100%;height: 100%;background:#f3ffed"></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<select style="width: 100%;height: 100%;background:#f3ffed" name='materials_currency_<?php echo $x; ?>_<?php echo $v; ?>_1'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_2" name="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_1" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_2" name="materials_price_<?php echo $x; ?>_<?php echo $v; ?>_1" onblur="calculateAmount4(<?php echo $x; ?>, <?php echo $v; ?>,'2')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_2" name="materials_amount_<?php echo $x; ?>_<?php echo $v; ?>_1" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" style="background:#f3ffed">
				    					</td>
				    				</tr>	
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->		
				    				<!-- -------------------------------- FOR SERVICE --------------------------------	 -->			
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f6fffe"></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<select style="width: 100%;height: 100%;background:#f6fffe" name='currency_<?php echo $x; ?>_<?php echo $v; ?>_2'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td  width="10%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_3" name="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_3" name="price_<?php echo $x; ?>_<?php echo $v; ?>_2" onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'3')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_3" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right" style="background:#f6fffe">
				    					</td>
				    				</tr>
				    				<!-- -------------------------------- FOR SERVICE --------------------------------	 -->			
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->			
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="materials_offer_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f3ffed"></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<select style="width: 100%;height: 100%;background:#f3ffed" name='materials_currency_<?php echo $x; ?>_<?php echo $v; ?>_2'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_4" name="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_4" name="materials_price_<?php echo $x; ?>_<?php echo $v; ?>_2" onblur="calculateAmount4(<?php echo $x; ?>, <?php echo $v; ?>,'4')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_4" name="materials_amount_<?php echo $x; ?>_<?php echo $v; ?>_2" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" style="background:#f3ffed">
				    					</td>
				    				</tr>	
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->		
				    				<!-- -------------------------------- FOR SERVICE --------------------------------	 -->	
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="offer_<?php echo $x; ?>_<?php echo $v; ?>_3" style="width: 100%;height: 100%;background:#f6fffe"></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    							<select style="width: 100%;height: 100%;background:#f6fffe" name='currency_<?php echo $x; ?>_<?php echo $v; ?>_3'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td  width="10%" class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_5" name="offerqty_<?php echo $x; ?>_<?php echo $v; ?>_3" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_5" name="price_<?php echo $x; ?>_<?php echo $v; ?>_3" onblur="calculateAmount(<?php echo $x; ?>, <?php echo $v; ?>,'5')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_5" name="amount_<?php echo $x; ?>_<?php echo $v; ?>_3" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td class="bor-btm bor-right" style="background:#f6fffe">				    						
				    					</td>
				    				</tr>
				    				<!-- -------------------------------- FOR SERVICE --------------------------------	 -->			
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->			
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea type="text" class="form-control f10" name="materials_offer_<?php echo $x; ?>_<?php echo $v; ?>_3" 
				    							style="width: 100%;height: 100%;background:#f3ffed"></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<select style="width: 100%;height: 100%;background:#f3ffed" name='materials_currency_<?php echo $x; ?>_<?php echo $v; ?>_3'>
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr=='PHP') ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_6" name="materialsqty_<?php echo $x; ?>_<?php echo $v; ?>_3" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right f10" align="center">
				    						<input type="text" class="form-control f10" id="price_<?php echo $x; ?>_<?php echo $v; ?>_6" name="materials_price_<?php echo $x; ?>_<?php echo $v; ?>_3" onblur="calculateAmount4(<?php echo $x; ?>, <?php echo $v; ?>,'6')" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" align="center">
				    						<input type="text" class="form-control f10" readonly="readonly" id="amount_<?php echo $x; ?>_<?php echo $v; ?>_6" name="materials_amount_<?php echo $x; ?>_<?php echo $v; ?>_3" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm bor-right" style="background:#f3ffed">
				    					</td>
				    				</tr>	
				    				<!-- -------------------------------- FOR ITEMS ----------------------------------	 -->		
			    				</table>		    			
			    			</td>
			    			<input type='hidden' name='uom_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $it->uom; ?>'>
			    			<input type='hidden' name='quantity_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $it->quantity; ?>'>
			    			<input type='hidden' name='item_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $it->jor_aoq_items_id; ?>'>
			    			<input type='hidden' name='jor_items_id_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $it->jor_items_id; ?>'>
			    			<input type='hidden' name='vendor_<?php echo $x; ?>_<?php echo $v; ?>' value='<?php echo $ven['vendor_id']; ?>'>
			    			<?php 
			    			$v++;
			    			} ?>
			    			<input type='hidden' name='vendor_count' value='<?php echo $v; ?>'>

		    			<?php 

		    			} else if($saved==1 && $open==0) { 
		    				foreach($vendors AS $ven) {
		    			?>
		    					
			    			<td style='vertical-align:text-bottom;padding-right: 1px;' >
			    				<table class="tabale" width="100%" height="50%" style='margin-bottom: 0px;'>	
				    				<!-- <tr style="border-bottom:1px solid #000">
			    						<td width="50%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
						    			<td width="5%" class="f9 table-borbold" align="center"><b>CURR</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
						    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
			    					</tr>		 -->
			    				<?php 	
			    				$a=1;
			    				foreach($offers AS $of){
		    						if($ven['vendor_id'] == $of['vendor_id'] && $it->jor_aoq_items_id == $of['item_id']){ ?>	

		    						<!-- ---------------------------- FOR SERVICE ---------------------------- -->			
			    					<tr style="height:100px">
				    					<td width="25%" class="bor-btm bor-right">
				    						<textarea class="form-control" style="width: 100%;height: 100%;" readonly><?php echo $of['offer']; ?></textarea>
				    					</td>
				    						<td width="6%" class="bor-btm bor-right">
				    						<center><?php echo $of['currency']; ?></center>
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10" align="center">
				    						<?php echo number_format($of['quantity'],2); ?>
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10 <?php echo (($of['price']==$of['min']) ? 'yellow-back' : ''); ?> " align="center">
				    						<?php echo number_format($of['price'],4); ?>
				    					</td>

				    					<?php if($awarded==0){ ?>
				    					<td width="10%" class="bor-btm-red bor-right" align="center">				    						
				    						<?php echo number_format($of['amount'],4); ?><br>
				    						<input type="checkbox" name="award_<?php echo $a; ?>" value="1" >
				    					</td> 
				    					<?php } else { ?>
				    						<td width="10%" class="bor-btm bor-right <?php echo (($of['recommended'] == 1) ? 'green-back': ''); ?>" align="center">				    						
					    						<?php echo number_format($of['amount'],4); ?><br>
					    					</td> 
					    				<?php } 

					    				if($awarded==0){  ?>
				    					<td width="15%" class="bor-btm-red bor-right">
				    						<textarea type="text" class="form-control f10" name="comments_<?php echo $a; ?>"
				    							style="width: 100%;height: 100%"
				    							><?php echo $of['comments']; ?>
				    						</textarea>
				    					</td>
				    					<?php } else { ?>
				    						<td width="15%" class="bor-btm bor-right">
				    							<textarea class="form-control" style="width: 100%;height: 100%;" readonly>
				    								<?php echo $of['comments']; ?>
				    							</textarea>				    							
				    						</td>
				    					<?php } ?>
				    				</tr>
				    				<!-- ---------------------------- FOR SERVICE ---------------------------- -->
				    				<?php if($of['materials_offer']!='' && $of['materials_qty']!=0){ ?>
				    				<!-- ---------------------------- FOR ITEMS ---------------------------- -->
				    				<tr style="height:100px">
				    					<td width="25%" class="bor-btm bor-right">
				    						<textarea class="form-control" style="width: 100%;height: 100%;" readonly><?php echo $of['materials_offer']; ?></textarea>
				    					</td>
				    						<td width="6%" class="bor-btm bor-right">
				    						<center><?php echo $of['materials_currency']; ?></center>
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10" align="center">
				    						<?php echo number_format($of['materials_qty'],2); ?>
				    					</td>
				    					<td width="10%" class="bor-btm bor-right f10 <?php echo (($of['materials_unitprice']==$of['minmaterials']) ? 'yellow-back' : ''); ?> " align="center">
				    						<?php echo number_format($of['materials_unitprice'],4); ?>
				    					</td>

				    					<?php if($awarded==0){ ?>
				    					<td width="10%" class="bor-btm-red bor-right" align="center">				    						
				    						<?php echo number_format($of['materials_amount'],4); ?><br>
				    						<input type="checkbox" name="materials_recommended_<?php echo $a; ?>" value="1" >
				    					</td> 
				    					<?php } else { ?>
				    						<td width="10%" class="bor-btm bor-right <?php echo (($of['materials_recommended'] == 1) ? 'green-back': ''); ?>" align="center">				    						
					    						<?php echo number_format($of['materials_amount'],4); ?><br>
					    					</td> 
					    				<?php } 

					    				if($awarded==0){  ?>
				    					<td width="15%" class="bor-btm-red bor-right">
				    						<textarea type="text" class="form-s f10" name="comments_<?php echo $a; ?>"style="width: 100%;height: 100%"
				    						><?php echo $of['comments']; ?></textarea>
				    					</td>
				    					<?php } else { ?>
			    						<td width="15%" class="bor-btm bor-right">
			    							<textarea class="form-control" style="width: 100%;height: 100%;" readonly>
			    								<?php echo $of['comments']; ?>
			    							</textarea>	
			    						</td>
				    					<?php } ?>
				    				</tr>
				    				<?php } ?>
				    				<!-- ---------------------------- FOR ITEMS ---------------------------- -->

				    				<input type='hidden' name='offerid_<?php echo $a; ?>' value="<?php echo $of['jor_aoq_offer_id']; ?>">
				    				<?php }
				    				$a++;
				    				}
				    				?>
				    			<input type='hidden' name='count_offer' value='<?php echo $a; ?>'>
			    				</table>		    			
			    			</td>
			    		
			    		<?php }

		    			} else if($saved==1 && $open==1) { 
		    				foreach($vendors AS $ven) {
			    		?>
		    					
		    				<!-- ------------------------ OPEN AOQ ----------------------- -->	
			    			<td style='vertical-align:text-bottom;padding-right: 1px;' >
			    				<table class="tabale" width="100%" height="50%" style='margin-bottom: 0px;'>	
				    				<!-- <tr style="border-bottom:1px solid #000">
			    						<td width="50%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
						    			<td width="5%" class="f9 table-borbold" align="center"><b>CURR</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
						    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
			    					</tr> -->		
				    				<?php 	
				    					$a=1;
				    					$b=1;
				    					foreach($offers AS $of){
				    				?>
				    				<input type='hidden' name='quantity_<?php echo $a; ?>' id='quantity_<?php echo $a; ?>' value='<?php echo $of['quantity']; ?>'>
			    					<?php if($ven['vendor_id'] == $of['vendor_id'] && $it->jor_aoq_items_id == $of['item_id']){ ?>

			    					<!-- ---------------------------------------FOR SERVICES------------------------------------ -->
			    					<tr style="height:100px">
				    					<td width="25%" class="bor-btm bor-right padding-0">
				    						<textarea  class="form-control f10" name='offer_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f6fffe"><?php echo $of['offer']; ?></textarea>
				    					</td>
				    					<td width="6%" class="bor-btm bor-right f10 padding-0" align="center">
				    						<select name='currency_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f6fffe">
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr==$of['currency']) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td width="10%" class="bor-btm-red bor-right padding-0" align="center">				    						
				    						<input type='text' class="form-control f10" name='offerqty_<?php echo $a; ?>' id='offerqty_<?php echo $a; ?>' value="<?php echo number_format($of['quantity'],2); ?>" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td> 
				    					<td width="10%" class="bor-btm bor-right f10 padding-0" align="center">
				    						<input type='text' class="form-control f10" name='price_<?php echo $a; ?>' id='price_<?php echo $a; ?>' value="<?php echo number_format($of['price'],4); ?>" onblur="calculateAmount2(<?php echo $a; ?>)" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>
				    					<td width="10%" class="bor-btm-red bor-right padding-0" align="center">				    						
				    						<input type='text' class="form-control f10" name='amount_<?php echo $a; ?>' id='amount_<?php echo $a; ?>' readonly="readonly" value="<?php echo number_format($of['amount'],4); ?>" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td> 
				    					<td width="15%"  class="bor-btm-red bor-right padding-0" style="background:#f6fffe">
				    					</td>
				    				</tr>

				    				<!-- ---------------------------------------FOR ITEMS------------------------------------ -->
				    				<?php if($of['materials_offer']!='' && $of['materials_qty']!=0){ ?>
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right padding-0">
				    						<textarea  class="form-control f10" name='materials_offer_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f3ffed"><?php echo $of['materials_offer']; ?></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10 padding-0" align="center">
				    						<select name='materials_currency_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f3ffed">
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr==$of['materials_currency']) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td class="bor-btm-red bor-right padding-0" align="center">				    						
				    						<input type='text' class="form-control f10" name='materialsqty_<?php echo $a; ?>' id='materialsqty_<?php echo $a; ?>_<?php echo $b; ?>' value="<?php echo number_format($of['materials_qty'],2); ?>" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td> 
				    					<td class="bor-btm bor-right f10 padding-0" align="center">
				    						<input type='text' class="form-control f10" name='materials_price_<?php echo $a; ?>' id='price_<?php echo $a; ?>_<?php echo $b; ?>' value="<?php echo number_format($of['materials_unitprice'],4); ?>" onblur="calculateAmount3(<?php echo $a; ?>,<?php echo $b; ?>)" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>
				    					<td class="bor-btm-red bor-right padding-0" align="center">				    						
				    						<input type='text' class="form-control f10" name='materials_amount_<?php echo $a; ?>' id='amount_<?php echo $a; ?>_<?php echo $b; ?>' readonly="readonly" value="<?php echo number_format($of['materials_amount'],4); ?>" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td> 
				    					<td class="bor-btm-red bor-right padding-0" style="background:#f3ffed">
				    					</td>
				    				</tr>
				    				<?php } ?>
				    				<input type='hidden' name='offerid_<?php echo $a; ?>' value="<?php echo $of['jor_aoq_offer_id']; ?>">
				    				<?php }
				    				$a++;
				    				$b++;
				    				}
				    				?>
					    			<input type='hidden' name='count_offer' value='<?php echo $a; ?>'>
			    				</table>	    			
			    			</td>
			    			<!-- ------------------------ OPEN AOQ ----------------------- -->	


			    		<?php } 

			    		}else if($saved==0 && $open==0 && $draft==1){ 
				    		foreach($vendors AS $ven) {
			    		?>
		    					
			    			<td style='vertical-align:text-bottom;padding-right: 1px;' >
			    				<table class="tabale" width="100%" height="50%" style='margin-bottom: 0px;'>	
				    				<!-- <tr style=a"border-bottom:1px solid #000">
			    						<td width="50%" class="f9 table-borbold" align="center"><b>OFFER</b></td>
						    			<td width="5%" class="f9 table-borbold" align="center"><b>CURR</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>OFFER QTY</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>U/P</b></td>
						    			<td width="10%" class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
						    			<td width="15%" class="f9 table-borbold" align="center"><b>COMMENTS</b></td>
			    					</tr>	 -->
			    				<?php 	
			    					$a=1;
			    					$b=1;
			    					foreach($offers AS $of){
			    				?>
			    				<input type='hidden' name='quantity_<?php echo $a; ?>' id='quantity_<?php echo $a; ?>' value='<?php echo $of['quantity']; ?>'>
		    					<?php if($ven['vendor_id'] == $of['vendor_id'] && $it->jor_aoq_items_id == $of['item_id']){ ?>	

		    						<!-- ---------------------------------------FOR SERVICES------------------------------------ -->			
			    					<tr style="height:100px">
				    					<td width="25%" class="bor-btm bor-right">
				    						<textarea  class="form-control f10" name='offer_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f6fffe" ><?php echo $of['offer']; ?></textarea>
				    					</td>
				    					<td width="6%" class="bor-btm bor-right f10 " align="center">
				    						<select name='currency_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f6fffe">
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr==$of['currency']) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td width="10%" class="bor-btm-red bor-right" align="center">				    						
				    						<input type='text' class="form-control f10" name='offerqty_<?php echo $a; ?>' id='offerqty_<?php echo $a; ?>' value="<?php echo number_format($of['quantity'],2); ?>" style="width: 100%;height: 100%;background:#f6fffe">				    						
				    					</td> 
				    					<td width="10%" class="bor-btm bor-right f10 " align="center">
				    						<input type='text' class="form-control f10" name='price_<?php echo $a; ?>' id='price_<?php echo $a; ?>' value="<?php echo number_format($of['price'],4); ?>" onblur="calculateAmount2(<?php echo $a; ?>)" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f6fffe">
				    					</td>				    					
				    					<td width="10%" class="bor-btm-red bor-right" align="center">				    						
				    						<input type='text' class="form-control f10" name='amount_<?php echo $a; ?>' id='amount_<?php echo $a; ?>' readonly="readonly" value="<?php echo number_format($of['amount'],4); ?>" style="width: 100%;height: 100%;background:#f6fffe">				    						
				    					</td> 
				    					<td width="15%" class="bor-btm-red bor-right" style="background:#f6fffe">				    						
				    					</td>				    					
				    				</tr>

				    				<!-- ---------------------------------------FOR Items------------------------------------ -->
				    				<?php //if($of['materials_offer']!='' && $of['materials_qty']!=0){ ?>
				    				<tr style="height:100px">
				    					<td class="bor-btm bor-right">
				    						<textarea  class="form-control f10" name='materials_offer_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f3ffed" ><?php echo $of['materials_offer']; ?></textarea>
				    					</td>
				    					<td class="bor-btm bor-right f10 " align="center">
				    						<select name='materials_currency_<?php echo $a; ?>' style="width: 100%;height: 100%;background:#f3ffed">
						    					<?php foreach($currency AS $curr){ ?>
						    						<option value="<?php echo $curr; ?>" <?php echo (($curr==$of['materials_currency']) ? ' selected' : ''); ?>><?php echo $curr; ?></option>
						    					<?php } ?>
						    				</select>
				    					</td>
				    					<td class="bor-btm-red bor-right" align="center">				    						
				    						<input type='text' class="form-control f10" name='materialsqty_<?php echo $a; ?>' id='materialsqty_<?php echo $a; ?>_<?php echo $b; ?>' value="<?php echo number_format($of['materials_qty'],2); ?>" style="width: 100%;height: 100%;background:#f3ffed">			    						
				    					</td> 
				    					<td class="bor-btm bor-right f10 " align="center">
				    						<input type='text' class="form-control f10" name='materials_price_<?php echo $a; ?>' id='price_<?php echo $a; ?>_<?php echo $b; ?>' value="<?php echo number_format($of['materials_unitprice'],4); ?>" onblur="calculateAmount3(<?php echo $a; ?>,<?php echo $b; ?>)" onkeypress="return isNumberKey(this, event)" style="width: 100%;height: 100%;background:#f3ffed">
				    					</td>				    					
				    					<td class="bor-btm-red bor-right" align="center">				    						
				    						<input type='text' class="form-control f10" name='materials_amount_<?php echo $a; ?>' id='amount_<?php echo $a; ?>_<?php echo $b; ?>' readonly="readonly" value="<?php echo number_format($of['materials_amount'],4); ?>" style="width: 100%;height: 100%;background:#f3ffed">				    						
				    					</td> 
				    					<td class="bor-btm-red bor-right" style="background:#f3ffed">				    						
				    					</td>				    					
				    				</tr>
				    				<?php //} ?>

				    				<input type='hidden' name='offerid_<?php echo $a; ?>' value="<?php echo $of['jor_aoq_offer_id']; ?>">
				    				<?php } $a++; $b++; } ?>
				    			<input type='hidden' name='count_offer' value='<?php echo $a; ?>'>
			    				</table>		    			
			    			</td>
			    			<?php } } ?>
		    		</tr>	

		    	<?php $x++;
		    	} ?>
		    	<input type='hidden' name='item_count' value='<?php echo $x; ?>'>
		    	<input type='hidden' name='aoq_id' value='<?php echo $jor_aoq_id; ?>'>
		    		<tr>
		    			<td class="f10 table-borreg" colspan="4" align="center" ><br></td>
		    		</tr>
		    		<tr>
		    			<td class="f10 table-borreg text-red" align="right"><b>REMARKS</b></td>

		    			<td class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="left"></td>
		    		</tr>
		    		<tr><td class="f10" align="center"><br></td></tr>

		    		<tr>
		    			<td class="f10" align="right">
		    				<table width="100%">
		    					<tr>
		    						<td width="75%" align="right">a. </td>
		    						<td>&nbsp; Price Validity</td>
		    					</tr>
		    				</table>
		    			</td>
			    			<?php
			    			if($saved==0 && $open==0 && $draft==0){
			    			$q=1; 
			    			foreach($vendors AS $ven) { ?>
			    			<td class="f10" align="left">
			    				<input type="text" class="btn-block" name="price_validity<?php echo $q; ?>" autocomplete='off'>
			    				<input type='hidden' name='id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
			    			</td>
			    			<?php $q++; } 
			    			} else if($saved==1 && $open==0) {
			    				foreach($vendors AS $ven) { ?>
			    				<td class="f10 bor-btm bor-right" align="left"><?php echo $ven['validity']; ?></td>
			    			<?php }
			    			} else if($saved==1 && $open==1){ 
			    				$q=1;
			    				foreach($vendors AS $ven) { ?>
			    				<td class="f10 bor-btm" align="left">
			    					<input type='text' class="btn-block" autocomplete='off' name="price_validity<?php echo $q; ?>" value="<?php echo $ven['validity']; ?>">
			    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
			    				</td>
			    			<?php   $q++; } } 
			    			else if($saved==0 && $open==0 && $draft==1){
			    			$q=1; 
			    			foreach($vendors AS $ven) { ?>
		    			<td class="f10 bor-btm" align="left">
		    				<input type='text' class="btn-block" autocomplete='off' name="price_validity<?php echo $q; ?>" value="<?php echo $ven['validity']; ?>">
		    				<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    			</td>
		    			<?php $q++; } } ?>  	  
		    		</tr>
		    		<tr>
		    			<td class="f10" align="right">
		    				<table width="100%">
		    					<tr>
		    						<td width="75%" align="right">b. </td>
		    						<td>&nbsp; Payment Terms</td>
		    					</tr>
		    				</table>
		    			</td>
		    			<?php
		    			if($saved==0 && $open==0 && $draft==0){
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    			<td class="f10" align="left">
		    				<input type="text" class="btn-block" name="payment_terms<?php echo $q; ?>" autocomplete='off' >
		    				<input type='hidden' name='id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    			</td>
		    			<?php  $q++;  }
		    			} else if($saved==1 && $open==0) {
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm bor-right" align="left"><?php echo $ven['terms']; ?></td>
		    			<?php } 
		    			} else if($saved==1 && $open==1){ 
		    					$q=1; 
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="payment_terms<?php echo $q; ?>" value="<?php echo $ven['terms']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php   $q++; } } 
		    			else if($saved==0 && $open==0 && $draft==1){ 
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="payment_terms<?php echo $q; ?>" value="<?php echo $ven['terms']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php   $q++; } } ?>  		
		    		</tr>
		    		<tr>
		    			<td class="f10" align="right">
		    				<table width="100%">
		    					<tr>
		    						<td width="75%" align="right">c. </td>
		    						<td>&nbsp; Work Duration</td>
		    					</tr>
		    				</table>
		    			</td>
		    			<?php
		    				if($saved==0 && $open==0 && $draft==0){
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    			<td class="f10" align="left">
		    				<input type="text" class="btn-block" name="delivery_date<?php echo $q; ?>" autocomplete='off'>
		    				<input type='hidden' name='id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    			</td>
		    			<?php $q++; } } 
		    			else if($saved==1 && $open==0) {
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm bor-right" align="left"><?php echo $ven['delivery_date']; ?></td>
		    			<?php }
		    			}  else if($saved==1 && $open==1){ 
		    				$q=1;
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="delivery_date<?php echo $q; ?>" value="<?php echo $ven['delivery_date']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php $q++; } }  
		    			else if($saved==0 && $open==0 && $draft==1){ 
		    			$q=1;
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="delivery_date<?php echo $q; ?>" value="<?php echo $ven['delivery_date']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php $q++; } } ?>  	  
		    		</tr>
		    		<tr>
		    			<td class="" align="right">
		    				<table width="100%">
		    					<tr>
		    						<td width="75%" align="right">d. </td>
		    						<td>&nbsp; Item's Warranty</td>
		    					</tr>
		    				</table>
		    			</td>
		    			<?php
		    			if($saved==0 && $open==0  && $draft==0){
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    			<td class="f10" align="left">
		    				<input type="text" class="btn-block" name="item_warranty<?php echo $q; ?>" autocomplete='off'>
		    				<input type='hidden' name='id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    			</td>
		    			<?php  $q++; } 
		    			} else if($saved==1 && $open==0){ 
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm bor-right" align="left"><?php echo $ven['warranty']; ?></td>
		    			<?php }
		    			} else if($saved==1 && $open==1){ 
		    				$q=1; 
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="item_warranty<?php echo $q; ?>" value="<?php echo $ven['warranty']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php $q++; } } 
		    			else if($saved==0 && $open==0 && $draft==1){ 
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="item_warranty<?php echo $q; ?>" value="<?php echo $ven['warranty']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php $q++; } } ?>
		    		</tr>
		    		<tr>
		    			<td class="" align="right">
		    			<table width="100%">
		    					<tr>
		    						<td width="75%" align="right">e. </td>
		    						<td>&nbsp; In-land Freight</td>
		    					</tr>
		    				</table>
		    			</td>
		    			<?php
		    			if($saved==0 && $open==0 && $draft==0){
		    			$q=1; 
		    			foreach($vendors AS $ven) { ?>
		    			<td class="f10" align="left">
		    				<input type="text" class="btn-block" name="freight<?php echo $q; ?>" autocomplete='off'>
			    			<input type='hidden' name='id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
			    		</td>
		    			<?php  $q++; } 
		    			} else if($saved==1 && $open==0) {
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm bor-right" align="left"><?php echo $ven['freight']; ?>
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php }
		    			}  else if($saved==1 && $open==1){ 
		    				$q=1;
		    				foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="freight<?php echo $q; ?>" value="<?php echo $ven['freight']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php   $q++; } } 
		    			else if($saved==0 && $open==0 && $draft==1){ 
		    			$q=1;
		    			foreach($vendors AS $ven) { ?>
		    				<td class="f10 bor-btm" align="left">
		    					<input type='text' class="btn-block" autocomplete='off' name="freight<?php echo $q; ?>" value="<?php echo $ven['freight']; ?>">
		    					<input type='hidden' name='vendor_id<?php echo $q; ?>' value="<?php echo $ven['id']; ?>">
		    				</td>
		    			<?php $q++; } } ?>  	  
		    		</tr>
		    		<tr><td class="f10" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="3">
		    				<table class="table-bordsered" width="100%">
		    					<tr>
					    			<td width="5%" class="" align="center"></td>
					    			<td width="15%" class="f10" align="center">Prepared by:</td>
					    			<td width="5%" class="f10" align="center"><br></td>
					    			<td width="15%" class="f10" align="center">Reviewed and Checked by</td>
					    			<td width="5%" class="f10" align="center"><br></td>
					    			<td width="15%" class="f10" align="center">Award Recommended by:</td>
					    			<td width="5%" class="f10" align="center"><br></td>
					    			<td width="15%" class="f10" align="center">Recommending Approval:</td>
					    			<td width="5%" class="f10" align="center"><br></td>
					    			<td width="15%" class="f10" align="center">Approved by:</td>
					    		</tr>
					    		<tr><td class="f10" align="center"><br></td></tr>
					    		<tr>
					    			<td class="" align="center"></td>
					    			<td class="f10 bor-btm" align="center"><?php echo (empty($prepared)) ? $_SESSION['fullname'] : $prepared; ?></td>
					    			<td class="f10" align="center" ><br></td>		    			
					    			<td class="f10  bor-btm" align="left">
					    			<?php if($saved==0 && $draft==0){ ?>
					    				<input type = "text" name='reviewed' class='emphasis btn-block'>
					    			<?php }else if($saved==0 && $draft==1){ ?>
					    				<input type = "text" name='reviewed' class='emphasis btn-block' value="<?php echo $reviewed; ?>">
					    			<?php } else {
					    				echo $reviewed;
					    			} ?></td>
					    			<td class="f10" align="left" style="width:20px"><br></td>
					    			<td class="f10 bor-btm" align="center"></td>
					    			<td class="f10" align="left" style="width:20px"><br></td>

					    			<td class="f10 bor-btm" align="center">
					    			<?php if($saved==0 && $draft==0){ ?>
					    				<input type = "text" name='approved' class='emphasis btn-block'>
					    			<?php }else if($saved==0 && $draft==1){ ?>
					    				<input type = "text" name='approved' class='emphasis btn-block' value="<?php echo $approved; ?>">
					    			<?php } else {
					    				echo $approved;
					    			} ?>
					    			</td>
					    			<td class="f10" align="left" style="width:20px"><br></td>
					    			<td class="f10 bor-btm" align="center">
					    			<?php if($saved==0 && $draft==0){ ?>
					    				<input type = "text" name='noted' class='emphasis btn-block'>
					    			<?php }else if($saved==0 && $draft==1){ ?>
					    				<input type = "text" name='noted' class='emphasis btn-block' value="<?php echo $noted; ?>">
					    			<?php } else {
					    				echo $noted;
					    			} ?>
					    			</td>
					    		</tr>
					    		<tr><td class="f10" align="center"><br></td></tr>
					    		<tr>
					    			<td  class="" align="center"></td>
					    			<td  class="" align="right">LEGEND:</td>
					    			<td  class="green-back p-l-5 p-r-5" align="center"></td>
					    			<td  class="" align="left">Recommended Award</td>
					    			<td  class="" align="center"></td>
					    			<td  class="" align="center"></td>

					    		</tr>
					    		<tr>
					    			<td  class="" align="center"></td>
					    			<td  class="" align="center"></td>
					    			<td  class="yellow-back p-l-5 p-r-5" align="center"></td>
					    			<td  class="" align="left">Lowest Price</td>
					    			<td  class="" align="center"></td>
					    			<td  class="" align="center"></td>
		    				</table>
		    			</td>
		    		</tr>

		    		</tr>
		    		<tr><td class="f10" colspan="21" align="center"><br></td></tr>
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='count' value="3">
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