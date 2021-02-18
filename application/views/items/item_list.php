    <div class="breadcome-area mg-b-30 small-dn">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="breadcome-heading">
                                    <form role="search" class="">
                                        <input type="text" placeholder="Search..." class="form-control">
                                        <a href=""><i class="fa fa-search"></i></a>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <ul class="breadcome-menu">
                                    <li><a href="<?php echo base_url(); ?>">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Item List</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-dashone-data-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline8-list shadow-reset">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>Item List</h1>
                                <div class="sparkline8-outline-icon">
                                    <a class="btn btn-custon-three btn-primary" href="#" data-toggle="modal" data-target="#PrimaryModalhdbgcl">
                                        <span class="fa fa-plus p-l-0"></span>
                                        Add Item
                                    </a>
                                    <div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header header-color-modal bg-color-1">
                                                    <h4 class="modal-title">Add New Item</h4>
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                                <form method="POST" action = "<?php echo base_url();?>index.php/items/insert_item">
                                                    <div class="modal-body-lowpad">
                                                        <div class="form-group">
                                                            <p class="m-b-0">Item Description:</p>
                                                            <textarea name="item" class="form-control" cols="2"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Specification:</p>
                                                            <textarea name="spec" class="form-control" cols="2"></textarea>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <p class="m-b-0">Unit:</p>
                                                            <select class = "form-control" name = "unit">
                                                                <option value="">--Select Unit--</option>
                                                                <?php foreach($unit AS $u){ ?>
                                                                    <option value = "<?php echo $u->unit_id;?>"><?php echo $u->unit_name;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div> -->
                                                        <div class="form-group">
                                                            <p class="m-b-0">Brand:</p>
                                                            <input type="text" name="brand" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="m-b-0">Part Number:</p>
                                                            <input type="text" name="pn" class="form-control">
                                                        </div>
                                                        <center>
                                                            <!-- <a href="#" class="btn btn-custon-three btn-primary btn-block">Save</a> -->
                                                            <input type = "submit" class="btn btn-custon-three btn-primary btn-block" value = "Save">
                                                        </center>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                            <th data-checkbox="true"></th>
                                            <th>Item Description</th>
                                            <th>Specification</th>
                                            <th>Brand</th>
                                            <th>Part No</th>
                                            <th><center>Action</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($item as $i){ ?>
                                        <tr>
                                            <td></td>
                                            <td>
                                               <?php echo $i->item_name;?>
                                            </td>
                                            <td> <a href="javascript:void(0)" class="btn-link txt-primary" onclick="itemDetails('<?php echo base_url(); ?>','<?php echo $i->item_id;?>')"><?php echo $i->item_specs;?></a></td>
                                            <td><?php echo $i->brand_name;?></td>
                                            <td><?php echo $i->part_no;?></td>
                                            <td>
                                                <center>
                                                    <a onclick="updateItem('<?php echo base_url(); ?>','<?php echo $i->item_id;?>')" class="btn btn-custon-three btn-info btn-xs">
                                                        <span class="fa fa-pencil"></span>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>index.php/items/delete_item/<?php echo $i->item_id;?>" class="btn btn-custon-three btn-danger btn-xs" onclick="confirmationDelete(this);return false;">
                                                        <span class="fa fa-times"></span>
                                                    </a>
                                                </center>
                                            </td>
                                        </tr> 
                                        <?php } ?>                                       
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