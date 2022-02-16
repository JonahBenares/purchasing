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
		.bor-right{
			border-right: 1px solid #000;
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
	        .bor-right{
				border-right: 1px solid #000;
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
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>index.php/joi/save_coc'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>index.php/joi/jo_issuance_saved/<?php echo $joi_id; ?>" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<?php if($coc_saved==0){  ?>
						<input type='submit' class="btn btn-primary btn-md p-l-25 p-r-25" id="submit" name='submit' value="Save">	
						<?php } ?>
					</div>
					<h4 class="text-white"><b>CERTIFICATE OF COMPLETION</b></h4>
					<p class="text-white">Instructions: When printing JOB ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4, <u>Margin</u> : Default, <u>Scale</u>: 100</p>
				</center>
			</div>
	    	<div style="background: #fff;" class=""> <!-- <?php  if($cancelled==1){ echo 'cancel'; }?> -->
	    		<table width="100%">
	    			<tr>
	    				<td width="25%"><?php echo date("m/d/Y") ?></td>
	    				<td width="50%"><center>Procurement System Generated</center></td>
	    				<td width="25%"></td>
	    			</tr>
	    		</table>   		  			
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
		    		<tr><td colspan="20" align="center"><h4><b>CERTIFICATE OF COMPLETION</b></h4></td></tr>




		    		<tr>
		    			<td class="f13" colspan="20"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b>Date:</td>
		    			<td class="f13" colspan="15"><?php echo ($date_created!='') ? date("F d, Y",strtotime($date_created)) : date("F d, Y");?></td>	
		    			<td class="f13" ></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b>Ref:</td>
		    			<td class="f13" colspan="15"><?php echo $ref_year; ?>-<?php echo $ref_series; ?></td>
		    			<td class="f13" ></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="20"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3" style="vertical-align:top;"><b>Activity:</td>
		    			<td class="f13" colspan="15"><?php echo $project_title; ?></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" colspan="20"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3" style="vertical-align:top;"><b>JO Reference:</td>
		    			<td class="f13" colspan="15">
		    				<?php echo $jo_no."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : ""); ?> <br>
		    				 <?php echo $cenjo_no; ?><br>
		    			</td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b><br></td>
		    			<td class="f13" colspan="15"></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="18">This is to certify that <U><b><?php echo $vendor; ?></b></U> has already completed the following scope of works for:</td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b><br></td>
		    			<td class="f13" colspan="15"></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="18">A. Supply of labor, Materials, tools, equipment and technical expertise for the <b><?php echo $project_title; ?></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="1"><b><br></td>
		    			<td class="f13" colspan="18">Scope of work includes:</td>
		    		</tr>
		    		        <!--ITEMS-->
		    					<?php 
		    						$gtotal=array();
		    						if(!empty($details)){
		    						foreach($details AS $det){ 
		    					?>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="2"><b><br></td>
		    			<td class="f13" colspan="15"><?php echo nl2br($det->offer); ?></td>
		    			<td class="f13" ></td>		    			
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<?php } } ?>
		    		<?php if($materials_offer!='' && $materials_qty!=0){ ?>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="1"><b><br></td>
		    			<td class="f13" colspan="14">Materials:</td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<?php } ?>
		    					<!--MATERIALS-->
		    					<?php 
		    						if(!empty($details)){
		    						foreach($details AS $det){ 
		    							if($det->materials_offer!='' && $det->materials_qty!=0){
		    					?>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="2"><b><br></td>
		    			<td class="f13" colspan="15"><?php echo nl2br($det->materials_offer); ?></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    				<?php } } } ?>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="2"><b><br></td>
		    			<td class="f13" colspan="16"></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="18">The above scope of works was completed and tested by <U><b><?php echo $vendor; ?></b></U> on <U><b>
		    				<?php if($coc_saved==0){ ?>
		    					<input type="date" name="date_prepared" value="<?php echo date("Y-m-d"); ?>">
		    				<?php } else{ echo date("F d,Y",strtotime($date_prepared_coc)); }?>
		    			</b></U>. 
		    				<?php if($coc_saved==0){ ?>
		    					<textarea name="coc_warranty" class="form-control">One (1) year warranty for parts and three (3) months warranty for service.</textarea> 
		    				<?php }else{ echo $warranty; } ?>
		    			<br>
		    			<br>
		    			This certification is being issued on the above-name contractor for payment purposes only.
		    			</td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="2"><b><br></td>
		    			<td class="f13" colspan="16"></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13" colspan="8">Check and Endorsed by:</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="8">Approve by:</td>
		    			<td class="f13"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13" colspan="8"><br></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="8"></td>
		    			<td class="f13"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13 bor-btm" colspan="8">
		    				<?php 
			    				if($coc_saved==1){ 
			    				 	echo $checked; 
			    				}else{ 
		    				?> 	
		    				<select type="text" name="checked_by" class="btn-block" id="checked" onchange="chooseEmpchecked()">
		    					<option value=''>-Select-</option>
		    					<?php foreach($employee AS $emp){ ?>
				    				<option value='<?php echo $emp->employee_id; ?>' <?php echo (($checked_by==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
		    				</select>	
		    				<?php } ?>
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13 bor-btm" colspan="8">
		    				<?php 
			    				if($coc_saved==1){ 
			    				 	echo $approved; 
			    				}else{ 
		    				?> 	
							<select type="text" name="approved_by" class="btn-block" id="approved" onchange="chooseEmpapprove()">
		    					<option value=''>-Select-</option>
		    					<?php foreach($employee AS $emp){ ?>
				    				<option value='<?php echo $emp->employee_id; ?>' <?php echo (($approved_by==$emp->employee_id) ? ' selected' : ''); ?>><?php echo $emp->employee_name; ?></option>
				    			<?php } ?>
		    				</select>	
		    				<?php } ?>	
		    			</td>
		    			<td class="f13"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13 " colspan="8">
		    				<center><div id='altss' style="font-weight:bold"></div></center>
		    				<span id="positionchecked"><?php echo $pos_checked; ?></span>
		    				<!-- <input id="positionchecked" class="select" style="pointer-events:none" value="<?php echo $pos_checked; ?>"> -->
		    			</td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="8">
		    				<center><div id='altsss' style="font-weight:bold"></div></center>
		    				<span id="positionapproved"><?php echo $pos_approved; ?></span>
                        	<!-- <input id="positionapproved" class="select" style="pointer-events:none" value="<?php echo $pos_approved;?>	"> -->
		    			</td>
		    			<td class="f13"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13" colspan="8"><br></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="8"></td>
		    			<td class="f13"></td>
		    		</tr>
		    	</table>		    
	    	</div>
	    	<input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
	    	<input type="hidden" id="joi_id" name="joi_id" value="<?php echo $joi_id; ?>">
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