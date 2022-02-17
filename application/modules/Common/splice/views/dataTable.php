<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/buttons.bootstrap.min.css" rel="stylesheet">

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12" style="width:100%">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $dataTable['heading'];?></h2>                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php
                    $allowableRequestType = array('request','task');
                    if(in_array($requestTypeForViewDetails, $allowableRequestType)){
                      $this->load->view('showAdvancedFilters');
                    }
                  ?>
                  <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px;padding:0px !important;">
                  
                      <!-- <div class="col-md-1 col-sm-1 col-xs-3" style="padding:0px !important;text-align:left"><b>Showing :</b></div> -->
                      <div id="resultCountForTable" class="col-md-10 col-sm-10 col-xs-8"  style="margin-left: 0px; padding: 0px ! important;">
                        <?php 
                        /*if($dataTable['showingTotalRows'] && $dataTable['showingTotalRows'] >0){
                          echo $dataTable['showingTotalRows'].'   of    '.count($dataTable['tbody']);
                        }*/
                        ?>
                      </div>
                    </div>
            
                    
                  </div>
                    <table class="table table-striped table-bordered dataTable no-footer dtr-inline" id="datatable-buttons" role="grid" aria-describedby="datatable-buttons_info" style="width: 100% !important;">
                      <thead style="width: 100% !important;">
                        <tr role="row" style="width: 100% !important;">
                        <?php
                          foreach ($dataTable['thead'] as $key => $heading) { ?>
                            <th class="sorting" tabindex="0" aria-controls="datatable-buttons" style="width:<?php echo $dataTable['coloumWidth'][$key];  ?>% !important" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending"><?php echo $heading;?></th>
                        <?php  } ?>                          
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        foreach ($dataTable['tbody'] as $rowArray => $row) {
                      ?>                        
                          <tr role="row" class="<?php echo $class;?>">
                            <?php                            
                              foreach ($row as $column ) { ?>                                
                                <td class="" ><?php echo $column;?></td>
                            <?php } ?> 
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
    
    <!-- /Datatables -->

  <?php if($dataTable['isOnPageLoad']) {?>
    <script type="text/javascript">
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

        </script>

  <?php }?>