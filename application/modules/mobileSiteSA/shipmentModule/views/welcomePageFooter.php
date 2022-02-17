<?php
// if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
                                'pages' => array('shipmentModule/widgets/userAlertLayer'),
                                'commonJSV2'=>true,
                                'doNotLoadJS' => array('jquery.ui.touch-punch.min'),
                                'js'=> array('shipmentSA'),
                            );
    $this->load->view('commonModule/footerV2',$footerComponents);
?>

<script type="text/javascript">
    var shipment;
</script>