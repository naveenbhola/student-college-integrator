<?php
$footerComponents = array(
    'commonJSV2'=>true,
    'js'=> array('countryPageSA'),
    'doNotLoadJS' => array('jquery.ui.touch-punch.min'),
    'trackingPageKeyIdForReg' => 489,
    'pages'=> array('commonModule/layers/brochureWithRequestCallback')
);
$this->load->view('commonModule/footerV2', $footerComponents);
?>