            </div><!-- content-inner-all -->
        </div><!-- wrapper-pro -->
        <!-- <div class="footer-copyright-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-copy-right">
                            <p>Copyright &#169; 2018 Colorlib All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Footer End-->
        <!-- Chat Box Start-->
        <!-- <div class="chat-list-wrap">
            <div class="chat-list-adminpro">
                <div class="chat-button">
                    <span data-toggle="collapse" data-target="#chat" class="chat-icon-link"><i class="fa fa-comments"></i></span>
                </div>
                <div id="chat" class="collapse chat-box-wrap shadow-reset animated zoomInLeft">
                    <div class="chat-main-list">
                        <div class="chat-heading">
                            <h2>Messanger</h2>
                        </div>
                        <div class="chat-content chat-scrollbar">
                            <div class="author-chat">
                                <h3>Monica <span class="chat-date">10:15 am</span></h3>
                                <p>Hi, what you are doing and where are you gay?</p>
                            </div>
                            <div class="client-chat">
                                <h3>Mamun <span class="chat-date">10:10 am</span></h3>
                                <p>Now working in graphic design with coding and you?</p>
                            </div>
                            <div class="author-chat">
                                <h3>Monica <span class="chat-date">10:05 am</span></h3>
                                <p>Practice in programming</p>
                            </div>
                            <div class="client-chat">
                                <h3>Mamun <span class="chat-date">10:02 am</span></h3>
                                <p>That's good man! carry on...</p>
                            </div>
                        </div>
                        <div class="chat-send">
                            <input type="text" placeholder="Type..." />
                            <span><button type="submit">Send</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Chat Box End-->
        <!-- jquery
    		============================================ -->
        
        <!-- bootstrap JS
    		============================================ -->
        <script type="text/javascript">
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
        <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#rfqtable').DataTable( {
                "order": [[0, 'asc' ],[4, 'desc' ]],
                "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]]
            } );
        } );
    </script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <!-- meanmenu JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.meanmenu.js"></script>
        <!-- mCustomScrollbar JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
        <!-- sticky JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.sticky.js"></script>
        <!-- scrollUp JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.scrollUp.min.js"></script>
        <!-- counterup JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/counterup/jquery.counterup.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/counterup/waypoints.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/counterup/counterup-active.js"></script>
        <!-- peity JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/peity/jquery.peity.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/peity/peity-active.js"></script>
        <!-- sparkline JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/sparkline/sparkline-active.js"></script>
        <!-- flot JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/flot/Chart.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/flot/flot-active.js"></script>
        <!-- map JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/map/raphael.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/map/jquery.mapael.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/map/france_departments.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/map/world_countries.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/map/usa_states.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/map/map-active.js"></script>
        <!-- data table JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/data-table/bootstrap-table.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/tableExport.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/data-table-active.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/bootstrap-table-editable.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/bootstrap-editable.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/bootstrap-table-resizable.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/colResizable-1.5.source.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/data-table/bootstrap-table-export.js"></script>
        <!-- main JS
    		============================================ -->
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/all-scripts.js"></script>
    </body>
</html>