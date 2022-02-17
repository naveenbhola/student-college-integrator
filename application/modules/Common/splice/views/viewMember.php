<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/buttons.bootstrap.min.css" rel="stylesheet">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12" style="width:100%">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Members Detail</h2>                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">                    
                    <table class="table table-striped table-bordered dataTable no-footer dtr-inline" id="datatable-buttons" role="grid" aria-describedby="datatable-buttons_info" style="width: 100% !important;">
                      <thead style="width: 100% !important;">
                        <tr role="row" style="width: 100% !important;">
                          <th class="sorting" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 20%;" aria-label="Name: activate to sort column ascending">User Name</th>
                          <th class="sorting" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 15%;" aria-label="Position: activate to sort column ascending">Email Id</th>                        
                          <th class="sorting" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 10%;" aria-label="Start date: activate to sort column ascending">Added By</th>
                          <th class="sorting_desc" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 12%;" aria-label="Salary: activate to sort column ascending" aria-sort="descending">Added On</th>
                          <th class="sorting" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 12%;" aria-label="Age: activate to sort column ascending">Last Login Date</th>
                          <th class="sorting" tabindex="0" aria-controls="datatable-buttons" rowspan="1" colspan="1" style="width: 8%;" aria-label="Start date: activate to sort column ascending">isActive</th>
                        </tr>
                      </thead>

                      <tbody>
                      <?php 
                        $class = 'even';
                        foreach ($membersDetails as $key => $userInfo) {
                          if($class == 'odd'){
                              $class = 'even';
                          }else{
                              $class = 'odd';
                          }
                      ?>
                        <tr role="row" class="<?php echo $class;?>" style="width: 100% !important;">
                          <?php
                            foreach ($userInfo as $user => $detail) {
                          ?>
                              <td class="" ><?php echo $detail;?></td>
                          <?php
                            }
                          ?>                          
                        </tr>    
                      <?php  
                        }
                      ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
</div>

<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/buttons.flash.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/buttons.html5.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/buttons.print.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/buttons.bootstrap.min.js"></script>

<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/pdfmake.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datatables/vfs_fonts.js"></script>


<!-- Datatables -->
    <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").removeAttr('width').DataTable({
              dom: "Bfrtip",
              buttons: [              
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdf",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              'autoWidth' : false,
              'columnDefs' : [
                                    { "width": "20% !important" },
                                    { "width": "15% !important" },
                                    { "width": "13% !important" },
                                    { "width": "10% !important" },
                                    { "width": "10% !important" },
                                    { "width": "12% !important" },
                                    { "width": "12% !important" },
                                    { "width": "8% !important" }
                                ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        var table = $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        TableManageButtons.init();
      });
    </script>
    <!-- /Datatables -->