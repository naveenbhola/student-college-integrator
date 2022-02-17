<?php 

$footerComponents = array(
    'js'              => array(),
    'skipRegistrationLayer' => true
    //'commonJSV2'=>true
);

$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
$j(document).ready(function(){
    initializeSearchBarV2();
})
</script>
