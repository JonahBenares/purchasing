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
        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
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
			html, body{
	            background: #fff!important;
	            font-size:12px!important;
	        }
			.pad{
        	padding:0px 0px 0px 0px
        	}
			#prnt_btn{
				display: none;
			}
			.emphasis{
				border: 0px solid #fff!important;
			}
			.text-red{
				color: red!important;
			}
			.cancel{
	        	background-image: url('<?php echo base_url(); ?>assets/img/cancel.png')!important;
	        	background-repeat:no-repeat!important;
	        	background-size: contain!important;
	        	background-position: center center!important;
	        }
	        .all-border
	        {
			    border: 1px solid #000!important;
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
    
    <div  class="pad">

    	<form method='POST' action='<?php echo base_url(); ?>jo/save_ar'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<?php if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">
						<?php } else { ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<?php } ?>
					</div>
					<p class="text-white">Instructions: When printing ACKNOWLEDGEMENT RECEIPT make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" class="<?php  if($cancelled==1){ echo 'cancel'; }?>"> <!-- add class cancel -->
		    	<table class="table-bordsered" width="100%" style="border:1px solid #000">
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
		    		<tr><td colspan="20" align="center"><h5><b class="">ACKNOWLEDGEMENT RECEIPT</b></h5></td></tr>

		    		<tr>
		    			<td colspan="3" class="all-border "><b class="nomarg">DR No. </b></td>
		    			<td colspan="17" class="all-border "><h4 style="margin:0px"><b><?php echo $ar_no."-".COMPANY; ?></b></h4> </td>
		    		</tr>
		    		<?php foreach($jo_head AS $jh){ ?>			    		
		    		<tr>
		    			<td colspan="3" class="all-border "><b class="nomarg">Date :</b></td>
		    			<td colspan="17" class="all-border "><b class="nomarg"><?php echo date("F d, Y",strtotime($jh->date_prepared))?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">Delivered to: </b></td>
		    			<?php if($saved==0){ ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><textarea name = "delivered_to"></textarea></b></td>
		    			<?php } else { ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><?php echo $delivered_to; ?></b></td>
		    			<?php } ?>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">Address: </b></td>
		    			<?php if($saved==0){ ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><textarea name = "address"></textarea></b></td>
		    			<?php } else { ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><?php echo $address; ?></b></td>
		    			<?php } ?>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">JO No.: </b></td>
		    			<td colspan="17" class="all-border"><b class="nomarg"><?php echo $jh->jo_no."-".COMPANY; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">Gate Pass No.: </b></td>
		    			<?php if($saved==0){ ?>
			    			<td colspan="17" class="all-border"><b class="nomarg"><textarea name = "gatepass"></textarea></b></td>
			    			<?php } else { ?>
			    			<td colspan="17" class="all-border"><b class="nomarg"><?php echo $gatepass_no; ?></b></td>
			    			<?php } ?>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">Requested by: </b></td>
		    			<?php if($saved==0){ ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><textarea name = "requested_by"></textarea></b></td>
		    			<?php } else { ?>
		    			<td colspan="17" class="all-border"><b class="nomarg"><?php echo $requested_by; ?></b></td>
		    			<?php } ?>
		    		</tr>
		    		<tr>
		    			<td colspan="3" class="all-border"><b class="nomarg">Project Title: </b></td>
		    			<td colspan="17" class="all-border"><h4 style="margin:0px"><b><?php echo $jh->project_title; ?></b></h4></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" align="center"><br></td>
		    		</tr>
		    		<?php } ?>
		    		<!-- Loop -->
		    		<tr>
		    			<td class="all-border" align="center"><b class="nomarg">#</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Supplier</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Description</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Delivered</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Received</b></td>
		    			<td class="all-border" align="center" colspan="2"><b class="nomarg">UOM</b></td>
		    			<td class="all-border" align="center" colspan="3"><b class="nomarg">Remarks</b></td>
		    		</tr>
		    		<?php $x=1; foreach($jo_det AS $jd){ ?>
		       		<tr>
		    			<td class="all-border" align="center"><?php echo $x; ?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $jd['supplier'];?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $jd['scope_of_work'];?></td>
		    			<td class="all-border" align="center"><?php echo $jd['quantity'];?></td>
		    			<td class="all-border" align="center"></td>
		    			<td class="all-border" align="center" colspan="2"><?php echo $jd['uom'];?></td>
		    			<td class="all-border" align="center" colspan="3"></td>
		    		</tr>
		    		<?php $x++; } ?>
		    		<!-- Loop end here-->
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b>Prepared by:</b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b>Received by:</b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b>Noted by:</b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4" class="bor-btm"><b><br></b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="4" align="center"><?php echo $_SESSION['fullname']; ?></td>
		    			<td colspan="2"></td>
		    			<td colspan="4" align="center">Print Name & Signature with Date Received</td>
		    			<td colspan="2"></td>
		    			<td colspan="4" align="center"></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4" class="bor-btm"><b></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="2"></td>
		    			<td colspan="4" align="center">Witness</td>
		    			<td colspan="2"></td>
		    			<td colspan="4"><b></b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>		
		    	</table>		    
	    	</div>
	    	<input type='hidden' name='jo_id' value='<?php echo $jo_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>