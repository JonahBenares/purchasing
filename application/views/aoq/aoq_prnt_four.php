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
			font-size:10px!important;
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
				font-size:10px!important;
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

			.bor-right{
			border-right: 1px solid #000!important;
		}

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
    	<form method='POST' action=''>
    		<div id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
						<a href="<?php echo base_url(); ?>aoq/aoq_list" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
						<input type='submit' class="btn btn-info btn-md p-l-100 p-r-100" value='Done'>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">	
					</div>
					<p class="text-white p-l-250 p-r-250">Instructions: When printing ABSTRACT OF QUOTATION make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Landscape, <u>Paper Size</u>: A4 <u>Margin</u> : Custom (top: 0.11" , right:1.25", bottom: 0.11", left: 0.11") <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;width: 130%!important">    		  			
		    	<table class="table-bordesred" width="100%" style="background: #fff;border: 1px solid #000">
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
		    		
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Department: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>		    			
		    			<td colspan="3" class="f12" align="right">Date: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Purpose: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>		    			
		    			<td colspan="3"class="f12" align="right">PR #: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>
		    		</tr>
		    		<tr>
		    			<td colspan="4" class="f12" align="right">Enduse: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>		    			
		    			<td colspan="3"class="f12" align="right">Date Needed: &nbsp;</td>
		    			<td colspan="13" class="f12" ></td>
		    		</tr>	
		    		<tr>
		    			<td colspan="4" class="f12"  align="right">Requested by: &nbsp;</td>
		    			<td colspan="29" class="f12" ></td>
		    		</tr>
		    		<!-- <tr><td class="f10"  align="center"><br></td></tr> -->
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td colspan="5" class="f10"  align="center">
		    			</td>
		    			<td colspan="7" class="f10 table-borbold"  align="center">
		    				<b>Supplier name</b><br>
		    				Contact Person<br>
		    				Phone Number
		    			</td>
		    			<td colspan="7" class="f10 table-borbold"  align="center">
		    				<b>Supplier name</b><br>
		    				Contact Person<br>
		    				Phone Number
		    			</td>
		    			<td colspan="7" class="f10 table-borbold"  align="center">
		    				<b>Supplier name</b><br>
		    				Contact Person<br>
		    				Phone Number
		    			</td>
		    			<td colspan="7" class="f10 table-borbold"  align="center">
		    				<b>Supplier name</b><br>
		    				Contact Person<br>
		    				Phone Number
		    			</td>
		    		</tr>
		    		<tr>
		    			<td class="f9 table-borbold "align="center"><b class="p-r-10 p-l-10">#</td>
		    			<td colspan="2" class="f9 table-borbold" align="center"><b>DESCRIPTION</td>
		    			<td class="f9 table-borbold" align="center"><b>QTY</td>
		    			<td class="f9 table-borbold" align="center"><b>UOM</td>

		    			<td colspan="2" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center" ><b>U/P</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="2" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center" ><b>U/P</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="2" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center" ><b>U/P</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>

		    			<td colspan="2" class="f9 table-borbold" align="center"><b>OFFER</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center" ><b>U/P</b></td>
		    			<td colspan="2"class="f9 table-borbold" align="center"><b>AMOUNT</b></td>
		    			<td class="f9 table-borbold" align="center"><b>COMMENTS</b></td>		    			
		    		</tr>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="2" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="7" style='border:1px solid #000;vertical-align: text-top;' >
		    				<table class="table-borsdered" width="100%" style='border:0px solid #000'>						
		    					<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
		    				</table>
		    			</td>
		    			<td colspan="7" style='border:1px solid #000;vertical-align: text-top;' >
		    				<table class="table-borsdered" width="100%" style='border:0px solid #000'>						
		    					<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
		    				</table>
		    			</td>
		    			<td colspan="7" style='border:1px solid #000;vertical-align: text-top;' >
		    				<table class="table-borsdered" width="100%" style='border:0px solid #000'>						
		    					<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
		    				</table>
		    			</td>
		    			<td colspan="7" style='border:1px solid #000;vertical-align: text-top;' >
		    				<table class="table-borsdered" width="100%" style='border:0px solid #000'>						
		    					<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
			    				<tr class="bor-btm">
			    					<td style='width:28%' class="bor-right f10" >
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    					<td style='width:29%' class="bor-right f10" align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td style='width:28%'  class="bor-right f10 " align="center">
			    						<input type="text" class="form-control f10" name="">
			    					</td>
			    					<td  class="bor-right text-red f10 ">
			    						<textarea type="text" class="form-control f10" name="" rows="1"></textarea>
			    					</td>
			    				</tr>
		    				</table>
		    			</td>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="2" class="f10 table-borreg" align="left"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<!-- loop ka here -->
		    			<td colspan="2" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<!-- and delete the other two below salamats -->

		    			<td colspan="2" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td colspan="2" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    			<td colspan="2" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td colspan="2"class="f10 table-borreg" align="center"></td>
		    			<td class="f10 table-borreg" align="center"></td>

		    		</tr>
		    		<tr>
		    			<td class="f10 table-borreg" align="center"></td>
		    			<td colspan="4" class="f10 table-borreg text-red" align="center"><b>REMARK</b></td>

		    			<!-- loop ka here -->
		    			<!-- and delete the other two below salamats -->

		    			<td colspan="7" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="7" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="7" class="f10 table-borreg" align="left"><br></td>
		    			<td colspan="7" class="f10 table-borreg" align="left"><br></td>
		    		</tr>
		    		<tr><td class="f10" colspan="33" align="center"><br></td></tr>
		    		<tr>
		    			<td class="" align="center">a.</td>
		    			<td colspan="8" class="f10" align="center">Price Validity</td>
		    			<td colspan="4" class="f10 bor-btm" align="left">asddas<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left">asddas<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left">asddas<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>		    			
		    			<td colspan="4" class="f10 bor-btm" align="left">asddas<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="" align="center">b.</td>
		    			<td colspan="8" class="f10" align="center">Payment Terms</td>
		    			<td colspan="4" class="f10 bor-btm" align="left">adasd<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left">adasd<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left">adasd<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left">adasd<br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="" align="center">c.</td>
		    			<td colspan="8" class="f10" align="center">Date of Delivery</td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    		</tr>
		    		<tr>
		    			<td class="" align="center">d.</td>
		    			<td colspan="8" class="f10" align="center">Item's Warranty</td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="4" class="f10 bor-btm" align="left"><br></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
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
		    			<td colspan="5" class="f10 bor-btm" align="center">name</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center"></td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center">
		    			<select name='approved' required style="width: 100%">
			    			<option value=''>-Select-</option>
		    			</select>
		    			</td>
		    			<td colspan="2" class="f10" align="left"><br></td>
		    			<td colspan="6" class="f10 bor-btm" align="center">
		    				<select name='noted' required style="width: 100%">
			    			<option value=''>-Select-</option>
		    			</select>
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
    		</form>
    </div>
<!-- Modal -->
		
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>