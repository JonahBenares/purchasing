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
	        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
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
    
    <div  class="pad">
    	<form method='POST' action='<?php echo base_url(); ?>'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>joi/joi_list" onclick="return quitBox('quit');" class="btn btn-success btn-md p-l-25 p-r-25"><span class="fa fa-arrow-left"></span> Back</a>
						<a  href='<?php echo base_url(); ?>jod/jo_direct_rev/<?php echo $joi_id; ?>' onclick="return confirm('Are you sure you want to revise JO?')" class="btn btn-info btn-md p-l-25 p-r-25"><span class="fa fa-pencil"></span> Revise <u><b>JO</b></u></a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print</a>
						<a  href="<?php echo base_url(); ?>jod/jod_rfd/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>RFD</b></a>
						<!-- <?php foreach($dr AS $d){ ?>
						<a  href="<?php echo base_url(); ?>jod/jod_dr/<?php echo $d->joi_id; ?>/<?php echo $d->joi_dr_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<?php } ?> -->
						<a class="btn btn-warning btn-md" data-toggle="dropdown" href="#"><span class="fa fa-print"></span> Print <b>DR</b></a>
						<ul class="dropdown-menu dropdown-alerts animated fadeInDown" style="width:200px;top:30px;border:1px solid #e66614;left:650px;">
							<?php foreach($dr AS $d){ ?>
								<li style="text-align: left!important"><a href="<?php echo base_url(); ?>jod/delivery_receipt/<?php echo $d->joi_id; ?>/<?php echo $d->joi_dr_id; ?>" target='_blank' class="btn btn-link"><?php echo "DR# ".$d->joi_dr_no; ?></a></li>
							<?php } ?>
						</ul>
						<a  href="<?php echo base_url(); ?>jod/jod_ac/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>AR</b></a>
						<a  href="<?php echo base_url(); ?>jod/jod_coc/<?php echo $joi_id; ?>" class="btn btn-warning btn-md p-l-25 p-r-25"><span class="fa fa-print"></span> Print <b>COC</b></a>
						
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
		    	<table class="table-bordesdred" width="100%" style="border:2px solid #000">
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
		    			<td class="f13" ></td>
		    			<td class="f13" ><br></td>
		    			<td class="f13" colspan="10"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b>Date:</td>
		    			<td class="f13" colspan="15"><?php echo date("F d, Y",strtotime($date_prepared));?></td>	
		    			<td class="f13" ></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3"><b>Ref:</td>
		    			<td class="f13" colspan="15"><?php echo $ref_year; ?>-<?php echo $ref_series; ?></td>
		    			<td class="f13" ></td>
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" ><br></td>
		    			<td class="f13" colspan="10"></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" colspan="3" style="vertical-align:top;"><b>Activity:</td>
		    			<td class="f13" colspan="15"><?php echo $project_title; ?></td>
		    			<td class="f13" ></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f13" ></td>
		    			<td class="f13" ><br></td>
		    			<td class="f13" colspan="10"></td>		    			
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
		    			<td class="f13" colspan="18">This is to ceretify that <U><b><?php echo $vendor; ?></b></U> has already completed the following scope of works for:</td>
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
		    			<td class="f13" colspan="14">Scope of work includes:</td>
		    			<td class="f13" ></td>		    			
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
		    						if(!empty($details_materials)){
		    						foreach($details_materials AS $det){ 
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
		    			<td class="f13" colspan="18">The above scope of works was completed and tested on <U><b><?php echo $vendor; ?></b></U> on <U><b><?php echo date("F d, Y",strtotime($date_prepared));?></b></U>. One (1) year warranty for parts and three (3) months warranty for service. 
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
		    			<td class="f13 bor-btm" colspan="8"><?php echo $checked; ?></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13 bor-btm" colspan="8"><?php echo $approved;?></td>
		    			<td class="f13"></td>
		    		</tr>
		    		<tr>
		    			<td class="f13"></td>
		    			<td class="f13 " colspan="8"><?php echo $pos_checked; ?></td>
		    			<td class="f13" colspan="2"></td>
		    			<td class="f13" colspan="8"><?php echo $pos_approved;?></td>
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