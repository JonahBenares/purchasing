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
	    <script src="<?php echo base_url(); ?>assets/js/dr.js"></script> 
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
    	<div class="modal fade" id="addpur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add 
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</h5>					
					</div>
					<div class="modal-body">
					<form method='POST' action="<?php echo base_url(); ?>dr/add_dr_purpose">
					<div class="form-group">
						<h5 class="nomarg">Notes:</h5>
						<h5 class="nomarg"><b>
							<input type='text' name='notes' class="form-control" autocomplete="off">
						</b></h5>
					</div>
					<div class="form-group">
						<h5 class="nomarg">Requestor:</h5>
						<h5 class="nomarg"><b>
							<select name='requested_by' class="form-control">
	                            <option value='' selected>-Select Employee-</option>
	                            <?php foreach($employee AS $emp){ ?>
	                            <option value="<?php echo $emp->employee_id; ?>">
	                            	<?php echo $emp->employee_name; ?>
	                            </option>
	                            <?php }  ?> 
	                        </select>
						</b></h5>
					</div>
					<div class="form-group">
						<h5 class="nomarg">Purpose:</h5>
						<h5 class="nomarg"><b>
							<input type="text" name='purpose' class="form-control">
						</b></h5>
					</div>

					<div class="form-group">
						<h5 class="nomarg">Enduse:</h5>
						<h5 class="nomarg"><b>
							 <input type="text" name='enduse' class="form-control">
						</b></h5>
					</div>
					
					</div>
					<div class="modal-footer">
						<input type='hidden' name='dr_id' value='<?php echo $dr_id; ?>'>
						<input type="submit" class="btn btn-primary btn-block" value="Save changes">
					</div>
				</form>
				</div>
			</div>
		</div>
    	<form method='POST' action='<?php echo base_url(); ?>dr/save_dr'>  
    		<div  id="prnt_btn">
	    		<center>
			    	<div class="btn-group">
			    		<a href="javascript:history.go(-1)" class="btn btn-success btn-md p-l-100 p-r-100"><span class="fa fa-arrow-left"></span> Back</a>
			    		<?php if($saved==0){ ?>
						<input type='submit' class="btn btn-primary btn-md p-l-100 p-r-100" value="Save">
						<?php }else { ?>
						<a  onclick="printPage()" class="btn btn-warning btn-md p-l-100 p-r-100"><span class="fa fa-print"></span> Print</a>
						<?php } ?>
					</div>
					<p class="text-white">Instructions: When printing DELIVERY RECEIPT make sure the following options are set correctly -- <u>Browser</u>: Chrome, <u>Layout</u>: Portrait, <u>Paper Size</u>: A4 <u>Margin</u> : Default <u>Scale</u>: 100 and the option: Background graphics is checked</p>
				</center>
			</div>
	    	<div style="background: #fff;" >    		  			
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
		    			<td colspan="20" class="all-border "><b class="nomarg">Date : <?php echo date('F j, Y', strtotime($date)); ?></b></td>		    			
		    		</tr>
		    		<tr>
		    			<td colspan="20" class="all-border "><b class="nomarg">
		    				<?php if($saved==0){ ?>
		    				<button type="button" data-toggle="modal" data-target="#addpur" class="btn btn-xs btn-primary" onclick="" >Add Purpose/ EndUse/ Requestor</button></b>
		    				<?php } ?>
		    			</td>		    			
		    		</tr>
		    		
		    		<!-- loop here start-->
		    		<?php foreach($details AS $d){ ?>
		    		<tr>
		    			<td colspan="13" class="all-border"><b class="nomarg"><?php echo $d['notes']; ?> </b></td>
		    			<td colspan="7" class="all-border">
		    				<b class="nomarg">Requestor: <?php echo $d['requestor']; ?></b>
		    				<div class="pull-right m-r-10">
		    					<?php if($saved==0){ ?>
		    					<a href="<?php echo base_url(); ?>dr/delete_purpose/<?php echo $d['dr_details_id']; ?>/<?php echo $dr_id ?>" onclick="return confirm('Are you sure you want to delete purpose?')" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
		    					<?php } ?>
		    				</div>
		    			</td>
		    		</tr>		    		
		    		<tr>
		    			<td colspan="13" class="all-border bor-btm2"><b class="nomarg">Purpose: <?php echo $d['purpose']; ?></b></td>
		    			<td colspan="7" class="all-border bor-btm2"><b class="nomarg">Enduse: <?php echo $d['enduse']; ?></b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="21" class="all-border "><b class="nomarg"></td>
		    		</tr>
		    		<?php } ?>
		    		<!-- loop here end-->
		    		<tr>
		    			<td colspan="20" class="all-border "><b class="nomarg">
		    				<?php if($saved==0){ ?>
		    				<a href="javascript:void()" onclick="additemdr('<?php echo base_url(); ?>','<?php echo $dr_id; ?>')" class="btn btn-xs btn-primary">Add Item/s</a></b>
		    				<?php } ?>
		    			</td>		    			
		    		</tr>
		    		<tr>
		    			<td class="all-border" align="center"><b class="nomarg">#</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Supplier</b></td>
		    			<td class="all-border" align="center" colspan="6"><b class="nomarg">Description</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Delivered</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">Received</b></td>
		    			<td class="all-border" align="center" colspan="2"><b class="nomarg">UOM</b></td>
		    			<td class="all-border" align="center" colspan="2"><b class="nomarg">Remarks</b></td>
		    			<td class="all-border" align="center"><b class="nomarg">
		    				</b>
		    			</td>
		    		</tr>
		    		<!-- <loop  start-->
		    		<?php
		    			$a=1;
			    		if(!empty($items)){
			    			foreach($items AS $it){ 
		    		?>
		       		<tr>
		    			<td class="all-border" align="center"><?php echo $a; ?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $it['supplier']; ?></td>
		    			<td class="all-border" align="left" colspan="6"><?php echo $it['item'] . ', ' . $it['specs']; ?></td>
		    			<td class="all-border" align="center"><?php echo $it['quantity']; ?></td>
		    			<td class="all-border" align="center"></td>
		    			<td class="all-border" align="center" colspan="2"><?php echo $it['unit']; ?></td>
		    			<td class="all-border" align="center" colspan="2"><?php echo $it['remarks']; ?></td>
		    			<td class="all-border" align="center" >
		    				<?php if($saved==0){ ?>
		    				<a href="<?php echo base_url(); ?>dr/delete_dritem/<?php echo $it['id']; ?>/<?php echo $dr_id ?>" onclick="return confirm('Are you sure you want to delete item?')" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
		    				<?php } ?>
		    			</td>
		    		</tr>
		    		<?php $a++; } } ?> 	
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
	    	<input type='hidden' name='dr_id' value='<?php echo $dr_id; ?>'>
    	</form>
    </div>
    <script type="text/javascript">
    	function printPage() {
		  window.print();
		}
    </script>