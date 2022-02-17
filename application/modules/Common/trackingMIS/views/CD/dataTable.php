<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="dataTable">
                <div class="x_title">
                    <h2 id="dataTableHeading"></h2>
                    
                    <div class="clearfix"></div>
                </div>
                <?php if($page == 'DomesticOverview') {?>
                <div class="loader_small_overlay" style="" id="dataTableLoader">
                    <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
            </div>
                <?php }?>
                <div class="x_content">
                    <table id="example" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">

                    </table>
                </div>
            </div>  
</div>

                            
<!-- Datatables -->
<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<script type="text/javascript">
var dataTableHeading= '<?php echo $respondentsResult[0];?>';
var dataTableData = '<?php echo $respondentsResult[1];?>';
var dataForCSV = '<?php echo $respondentsResult[2];?>';
prepareDataTableForData(dataTableHeading,dataTableData,dataForCSV);
</script>



