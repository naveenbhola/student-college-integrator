<?php
$footerComponents = array(
                'commonJSV2'=>true,
                'loadLazyJSFile'=>true,
                'js'=> array('scholarshipHomePageSA'),
                'trackingPageKeyIdForReg' => $trackingPageKeyIdForReg,
                'pages'=> array()
            );
$this->load->view('commonModule/footerV2', $footerComponents);
?>