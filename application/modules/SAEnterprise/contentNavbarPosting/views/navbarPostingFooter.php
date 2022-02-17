<div id="rmcModalOverlay" style="display:none;"></div>
<?php
    // load footer file from listingPosting module
    $this->load->view('listingPosting/abroad/abroadCMSFooter');
?>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/jquery_ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3");?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/multiSelect/jquery.multiselect.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/datatable/jquery.dataTables.min.js"></script>
<script>
    $j(document).ready(function()
    {
        if(formname == 'viewContentNavbars'){
            initializeNavbarDatatable();
        }else if(formname == 'addEditContentNavbarLinks'){
            initializeNavbarForm();
        }
    });
</script>