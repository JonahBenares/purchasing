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
		.bor-btm2{
			border-bottom: 2px solid #000!important;
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

    	<form method='POST' action=''>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
					</div>
					<p class="text-white">Instructions: When printing DELIVERY RECEIPT make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" > 
	    		<table width="100%">
	    			<tr>
	    				<td width="25%"><?php echo date("m/d/Y") ?></td>
	    				<td width="50%"><center>Procurement System Generated</center></td>
	    				<td width="25%"></td>
	    			</tr>
	    		</table>   		  			
		    	<table class="table-boddered" width="100%" style="border:0px solid #000">
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
		    		<tr><td colspan="20" align="center"><h5><b class="text-red">DELIVERY RECEIPT</b></h5></td></tr>
		    		<!-- <tr><td class="f13" colspan="20" align="center"><br></td></tr> -->
		    		<tr>
		    			<td colspan="13" class="all-border "><b class="text-red nomarg">DR No. <?php echo $dr_no."-".COMPANY; ?></b></td>
		    			<td colspan="7" class="all-border " align="right"><b style="color: blue;margin-right: 5px">Direct Purchase</b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" class="all-border "><b class="nomarg">Date :  <?php echo date('F j, Y', strtotime($date)); ?></b></td>		    			
		    		</tr>
		    		<!-- loop here start-->
		    		<?php foreach($details AS $d){ ?>
		    		<tr>
		    			<td colspan="13" class="all-border"><b class="nomarg"><?php echo $d['notes']; ?></b></td>
		    			<td colspan="7" class="all-border"><b class="nomarg">Requestor: <?php echo $d['requestor']; ?></b></td>
		    		</tr>		    		
		    		<tr>
		    			<td colspan="13" class="all-border bor-btm2"><b class="nomarg">Purpose: <?php echo $d['purpose']; ?></b></td>
		    			<td colspan="7" class="all-border bor-btm2"><b class="nomarg">Enduse: <?php echo $d['enduse']; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="20" class="all-border "><b class="nomarg"><br></b></td>		    			
		    		</tr>
		    		<?php } ?>
		    		<!-- loop here end-->
		    		<tr>
		    			<td class="all-border" align="center"><b class="nomarg">#</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Supplier</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Description</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Delivered</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Received</b></td>
		    			<td class="all-border" align="center" colspan="2"><b class="nomarg">UOM</b></td>
		    			<td class="all-border" align="center" colspan="3"><b class="nomarg">Remarks</b></td>
		    		</tr>
		    		<!-- <loop  start-->
		    		<?php $x = 1; foreach($items AS $i){ ?>
		       		<tr>
		    			<td class="all-border" align="center"><?php echo $x; ?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $vendor; ?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $i['item'] . ", " . $i['specs']; ?></td>
		    			<td class="all-border" align="center"><?php echo number_format($i['quantity'],2); ?></td>
		    			<td class="all-border" align="center"></td>
		    			<td class="all-border" align="center" colspan="2"><?php echo $i['unit']; ?></td>
		    			<td class="all-border" align="center" colspan="3"></td>
		    		</tr>	
		    		<?php } ?>	    	
		    		<!-- Loop end here-->
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6"><b>Prepared by:</b></td>
		    			<td colspan="5"></td>
		    			<td colspan="6"><b>Received by:</b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6" class="bor-btm"><b><br><?php echo $_SESSION['fullname']; ?></b></td>
		    			<td colspan="5"></td>
		    			<td colspan="6" class="bor-btm"></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6"></td>
		    			<td colspan="5"></td>
		    			<td colspan="6">Print Name & Signature with Date Received</td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6"><b></b></td>
		    			<td colspan="5"></td>
		    			<td colspan="6"><b>Witnessed by:</b></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6"><b><br></b></td>
		    			<td colspan="5"></td>
		    			<td colspan="6" class="bor-btm"></td>
		    			<td colspan="2"></td>
		    		</tr>
		    		<tr>
		    			<td></td>
		    			<td colspan="6"></td>
		    			<td colspan="5"></td>
		    			<td colspan="6">Print Name & Signature with Date Received</td>
		    			<td colspan="2"></td>
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
    </script>