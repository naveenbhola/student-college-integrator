<?php

class scholarshipMobileCommonLib{
    private $CI;
    function __construct(){
        $this->CI = &get_instance();
    }

    public function getCTAData(&$displayData){
        $displayData['allCTAData'] = array(
                'topCTA'    => array(
                    'ctaBoxId' => 'topCTA',
                    'downloadBrochure' => array('id'=>'db1', 'sourcePage'=>'SLP_MOBILE_DOWNLOAD_BROCHURE_TOP_CTA', 'tkey'=>1325),
                    'applyNow' => array('id'=>'sa1', 'sourcePage'=>'SLP_MOBILE_APPLY_NOW_TOP_CTA', 'tkey'=>1326),
                    ),
                'bottomCTA'    => array(
                    'ctaBoxId' => 'bottomCTA',
                    'downloadBrochure' => array('id'=>'db2', 'sourcePage'=>'SLP_MOBILE_DOWNLOAD_BROCHURE_BOTTOM_CTA', 'tkey'=>1337),
                    'applyNow' => array('id'=>'sa2', 'sourcePage'=>'SLP_MOBILE_APPLY_NOW_BOTTOM_CTA', 'tkey'=>1338),
                    ),
                'stickyCTA'    => array(
                    'ctaBoxId' => 'stickyCTA',
                    'downloadBrochure' => array('id'=>'db3', 'sourcePage'=>'SLP_MOBILE_DOWNLOAD_BROCHURE_STICKY_CTA', 'tkey'=>1354),
                    'applyNow' => array('id'=>'sa3', 'sourcePage'=>'SLP_MOBILE_APPLY_NOW_STICKY_CTA', 'tkey'=>1355),
                    ),
            );
    }
    
}
