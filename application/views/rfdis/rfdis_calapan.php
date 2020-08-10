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
	<script type="text/javascript">
		$(document).on("click", "#addnotes_button", function () {
		     var rfd_id = $(this).attr("data-id");
		     $("#rfd_id").val(rfd_id);

		});
	</script>
  	<style type="text/css">
        html, body{
            background: #2d2c2c!important;
            font-size:12px!important;
        }
        .pad{
        	padding:0px 250px 0px 250px
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
		.bor-top{
			border-top: 1px solid #000;
		}
		.bor-right{
			border-right: 1px solid #000;
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
			.bor-right{
				border-right: 1px solid #000;
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
    	<form method='POST' action='<?php echo $url; ?>'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						
						<!-- <a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a> -->
					
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">	
					
					</div>
					<p class="text-white">Instructions: When printing PURCHASE ORDER make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" class=""> <!-- add class cancel -->
	    		<table width="100%">
	    			<tr>
	    				<td width="25%"><?php echo date("m/d/Y") ?></td>
	    				<td width="50%"><center>Procurement System Generated</center></td>
	    				<td width="25%"></td>
	    			</tr>
	    		</table>
		    	<table class="table-borddered" width="100%" style="border:1px solid #000">
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
		    				<h4 class="company-st company-h-st" >
			    				<img class="logo-st" width="120px" src="<?php echo base_url().LOGO;?>">
			    				<b><?php echo COMPANY_NAME;?></b>
			    			</h4>
		    			</center>
	    			</td>
		    		</tr>
		    		<tr><td colspan="20" align="center"><h5><b>REQUEST FOR DISBURSEMENT</b></h5></td></tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Sender:</b></td>
		    			<td colspan="9" class="bor-btm">
		    				<input type="text" style="width:100%" name="company" autocomplete="off"></td>
		    			<td colspan="3" align="right"><b class="nomarg">RFCD No.:</b></td>
		    			<td colspan="5" class="bor-btm">
		    					<input type="text" style="width:100%" name="apv_no" autocomplete="off">
		    			</td>
		    		</tr>
		    		<tr>

		    			<td colspan="3"><b class="nomarg">Pay To:</b></td>
		    			<td colspan="9" class="bor-btm"><b class="nomarg"></b></td>
		    			<td colspan="3" align="right"><b class="nomarg">Date Requested:</b></td>
		    			<td colspan="5" class="bor-btm"></td>
		    		</tr>		    		
		    		<tr>
		    			<td></td>
		    			<td class="bor-btm" align="center">
		    				<input type="radio"  name="cash" value='1'>
		    			</td>
		    			<td><b class="nomarg">Cash</b></td>
		    			<td class="bor-btm" align="center">
	    					<input type="radio" name="cash" value='2'>
	    				</td>
		    			<td><b class="nomarg">Check</b></td>
		    			<td></td>
		    			<td colspan="2"><b class="nomarg">Bank / no.</b></td>
		    			<td colspan="4" class="bor-btm">
	    					<input type="text" style="width:100%" name="bank_no" autocomplete="off">
	    				</td>
		    			<td colspan="3" align="right"><b class="nomarg">Due Date:</b></td>
		    			<td colspan="5" class="bor-btm">
		    				<input type="date" style="width:100%" name="check_due" >
		    			</td>
		    		</tr>
		    		<tr>
		    			<td colspan="20"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="all-border" align="left" colspan="17"><b class="nomarg">Explanation</b></td>
		    			<td class="all-border" align="center" colspan="3"><b class="nomarg">Amount</b></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right"><br></td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right"><b class="nomarg">Payment for:</b></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		
		    		<tr>
		    			<td align="left" colspan="17" class="bor-right">
		    				<b class="nomarg">2.00 pc Ping Pong Racket, 2pcs, with Ping Pong ball, 3pcs, Brand: Dunlop, @ 765.00 per pc</b>
		    			</td>
		    			<td align="right" colspan="3">
		    				<span class="pull-left nomarg">P</span>
		    				<span class="nomarg" id=''><b>9980</b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right"><br>
		    				<br>
		    				<br>
		    				<br>
		    				<br>
		    				<br>
		    				<br>
		    				<br>
		    			</td>
		    			<td align="center" colspan="3"><br></td>
		    		</tr>	    		
		    		<tr>
		    			<td align="left" colspan="7" ></b></td>
		    			<td align="right" colspan="10" class="bor-right"><b class="nomarg" style="font-weight: 900"></b></td>
		    			<td align="right" colspan="3" >
		    				<span class="pull-left nomarg"></span>
		    				<span class="nomarg" id=''><b style="font-weight: 900"></b></span>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td align="left" colspan="7" ><b class="nomarg"></b></td>
		    			<td align="right" colspan="10" class="bor-right"></td>
		    			<td align="right" colspan="3"></td>
		    		</tr>
		    		<tr>
		    			<td align="center" colspan="17" class="bor-right bor-btm"><br></td>
		    			<td align="center" colspan="3" class="bor-btm"><br></td>
		    		</tr>
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Prepared by:</b></td>
		    			<td colspan="3"><b>Checked by:</b></td>
		    			<td colspan="3"><b>Noted by:</b></td>
		    			<td colspan="3"><b>Approved by:</b></td>
		    			<td colspan="3"><b>Request Initiated	 by:</b></td>
		    			<td colspan="5"><b>Payment Received by:</b></td>
		    		</tr>	
		    		<tr><td class="f13" colspan="20" align="center"><br></td></tr>	
		    		<tr>
		    			<td colspan="3"><b class="nomarg">Full Names</b></td>
		    			<td colspan="3">
		    			<b>
		    			<select name='checked' class="select-des emphasis"  style="width:90%">		    				
			    			<option value='' selected>-Select Employee-</option>
			    		</select>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<select name='noted' class="select-des emphasis"  style="width:90%">		    				
			    			<option value='' selected>-Select Employee-</option>
			    		</select>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<select name='endorsed' class="select-des emphasis"  style="width:90%">
			    			<option value=''>-Select Employee-</option>
		    			</select>
		    			</b>
		    			</td>
		    			<td colspan="3">
		    			<b>
		    			<select name='approved' class="select-des emphasis" required style="width:90%">
			    			<option value=''>-Select Employee-</option>
		    			</select>
		    			</b>
		    			</td>
		    			<td colspan="5">
		    			<b>
		    			<select name='received' class="select-des emphasis"  style="width:90%">
			    			<option value='' selected>-Select Employee-</option>
			    		</select>
		    			
		    			</b>
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
    </script>