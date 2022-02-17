<?php
if($type=='default')
    $this->load->view('onlineFormEnterprise/university_inteface');
else if($type=='Alerts')
    $this->load->view('onlineFormEnterprise/institute_dashboard_formdetail');
else if($type=='viewForm')
    $this->load->view('onlineFormEnterprise/viewForms/app_form_review');
else if($type=='formDatadashBoard')
    $this->load->view('onlineFormEnterprise/formDataDashboard');
else
    $this->load->view('onlineFormEnterprise/university_management_inteface');
?>
<script>
var gdPiName = '<?=$gdPiName?>';
</script>
<!--<div style="margin-top:40px">
<div id="hack_ie_operation_aborted_error"></div>-->

