<?php
$footerComponents = array(
                'commonJSV2'=>true,
                'js'=> array('scholarshipCategoryPageSA','scholarshipResponseSA'),
                'trackingPageKeyIdForReg' => $trackingPageKeyIdForReg,
                'pages'=> array('scholarshipPage/scholarshipCategoryPage/widgets/nMoreLayer')
            );
$this->load->view('commonModule/footerV2', $footerComponents);
?>