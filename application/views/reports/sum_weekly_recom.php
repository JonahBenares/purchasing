    <style type="text/css">
        tr td{
            white-space: nowrap!important;
        }
        tr td.nowrap{
            white-space: normal;
        }
        tr.fd td{
            background-color: #b9ffb9;
        }
        tr.pd td{
            background-color: #f3ff9e;
        }
        tr.cd td{
            background-color: #cacaca;
        }
    </style>
    <div id="filter_pr" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title"><span class="fa fa-filter"></span>Filter</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <form method="POST" action = "">
                    <div class="modal-body-lowpad">                        
                        <div class="form-group">
                            <p class="m-b-0">PR Number:</p>
                            <input type="date" name="date_receive" class="form-control">
                        </div> 
                        <div class="form-group">
                            <p class="m-b-0">Purpose:</p>
                            <input type="text" name="purpose" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Enduse:</p>
                            <input type="text" name="enduse" class="form-control">
                        </div>    
                        <div class="form-group">
                            <p class="m-b-0">Requestor:</p>
                            <input type="requestor" name="requestor" class="form-control">
                        </div>   
                        <div class="form-group">
                            <p class="m-b-0">Item Description:</p>
                            <input type = "text" name="description" class="form-control">
                        </div>  
                         <div class="form-group">
                            <p class="m-b-0">Supplier:</p>
                            <input type = "text" name="description" class="form-control">
                        </div>                 
                        <center>                           
                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Proceed">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area m-t-15 ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd p-b-0" >
                            <div class="main-sparkline8-hd">
                                <h1><button onclick="return quitBox('quit');" class=" btn btn-xs btn-success"><span class="fa fa-arrow-left"></span></button>
                                    Summary of Weekly Recommendations 
                                </h1>
                                <p class="p-l-25">&nbsp;<b style="color:blue">October 10, 2021 - October 17, 2021 </b></p> 
                                <div class="sparkline8-outline-icon">
                                    
                                    <a class="btn btn-custon-three btn-warning" data-toggle="modal" data-target="#pending_recom"> 
                                        <span class="fa fa-tasks" style="padding: 0px"></span> Pending
                                    </a>
                                    <a href="" class="btn btn-custon-three btn-info"> 
                                        <span class="fa fa-upload" style="padding: 0px"></span> Export to Excel
                                    </a>
                                    <a type='button' class="btn btn-custon-three btn-success"  data-toggle="modal" data-target="#filter_pr"> 
                                        <span class="fa fa-filter p-l-0"></span> Filter
                                    </a>
                                </div>
                            </div>
                        </div>     
                          
                        <!-- <span class='btn btn-success disabled'>Filter Applied</span>, <a href='' class='remove_filter alert-link pull-right btn'><span class="fa fa-times"></span></a>    -->                 
                              
                        <div class="sparkline8-graph" >
                            <div class="datatable-dashv1-list custom-datatable-overright" style="overflow-x: scroll;">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>                                       
                                        <tr>
                                            <th>Enduse</th>
                                            <th>Request by</th>
                                            <th>QTY as per PR</th>
                                            <th>UOM</th>
                                            <th>Description</th>
                                            <th>Supplier</th>
                                            <th>Site PR/JO No.</th>
                                            <th>Delivery Lead Time/ Work Duration</th>
                                            <th>Unit Price (Peso)</th>
                                            <th>Total Peso</th>
                                            <th>15 Days PDC</th>
                                            <th>30 Days PDC</th>
                                            <th>60 Days PDC</th>
                                            <th>Terms</th>	
                                            <th align="center" width="10%"><span class="fa fa-bars"></span></th>								
                                        </tr>                                       
                                    </thead>                                    
                                    <tbody>
                                        <tr>
                                            <td class="nowrap">Purpose: Top-Up of Units 1- 5 Low Levelled Charged Air Filter Lubricant; End Use: Running Units Auxiliary Equipment</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>      
                                    </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="pending_recom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Summary of Pending Weekly Recommendation
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>                            
                </div>
                <form method='POST' action="<?php echo base_url(); ?>reports/generate_unserved_report" target='_blank'>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <small>Date From:</small>
                                <input placeholder="Date From" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                            </div>
                            <div class="col-lg-6">
                                <small>Date To:</small>
                                <input placeholder="Date To" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary btn-block" value='Proceed'>
                        <a href="<?php echo base_url(); ?>index.php/reports/pending_weekly_recom"  class="btn btn-primary " target="_blank">Proceed</a>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        function goBack() {
            window.history.back();
        }
    </script>

    
    <!-- Data table area End-->