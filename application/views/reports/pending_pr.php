    <div class="breadcome-area mg-b-30 small-dn">
        <div class="container-fluid">
            <div class="row">
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="dash-adminpro-project-title">
                            <h2 class="m-b-0" >
                                <b>
                                    <span>Calendar</span>
                                    <div class="btn-group pull-right ">
                                        <button type="button" class="btn btn-success btn-md btn-custon-three" data-toggle="modal" data-target="#filter_pending" title="Filter">
                                            <span class="fa fa-filter"></span>
                                        </button>
                                    </div>                                    
                                </b>
                                <p class="m-b-0">Pending PR</p>
                                <div class="modal fade" id="filter_pending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Filter
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </h5>                                                
                                            </div>
                                            <form method='POST' action="<?php echo base_url(); ?>masterfile/filter_pending">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input placeholder="Date From" name="filter_date_from" class="form-control" type="text" onfocus="(this.type='date')" id="filter_date_from">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input placeholder="Date To" class="form-control" name="filter_date_to" type="text" onfocus="(this.type='date')" id="filter_date_to">
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                        
                                                    <input type="submit" class="btn btn-primary btn-block" value='Search'>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </h2>                            
                        </div>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th>Purpose</th>
                                        <th>End Use</th>
                                        <th>Site Pr No</th>
                                        <th>Requestor</th>
                                        <th>QTY</th>
                                        <th>UOM</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
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
    <!-- Data table area End-->