<?php

class TrackingCode
{
    private static $CI;
    private static $isUserLoggedIn = FALSE;
    
    private static function _init()
    {
        self::$CI = & get_instance();
        $user = self::$CI->checkUserValidation();
        if(is_array($user) && is_array($user[0]) && $user[0]['userid']) {
            self::$isUserLoggedIn = TRUE;
        }
    }
    
    /* public static function vizury($device)
    {
        self::_init();
        if($device == 'mobile') {
            return self::$CI->load->view('common/TrackingCodes/VizuryMobile', NULL, TRUE);    
        }
        else {
            return self::$CI->load->view('common/TrackingCodes/VizuryDesktop', NULL, TRUE);    
        }
    } */
    
    public static function SCANAudienceBuildingPixel()
    {
        self::_init();
        return self::$CI->load->view('common/TrackingCodes/SCANAudienceBuildingPixel', NULL, TRUE);    
    }
    
    public static function FBConvertedAudiencePixel()
    {
        self::_init();
        if(self::$isUserLoggedIn) {
            return self::$CI->load->view('common/TrackingCodes/FBConvertedAudiencePixel', NULL, TRUE);
        }
    }
    
    public static function GoogleConvertedAudiencePixel()
    {
        self::_init();
        if(self::$isUserLoggedIn) {
            return self::$CI->load->view('common/TrackingCodes/GoogleConvertedAudiencePixel', NULL, TRUE);
        }
    }
    
    public static function SCANSmartPixel($params)
    {
        if(is_array($params) && count($params) > 0) {
            self::_init();
            //$params['v'] = 'k';
            $data = array('params' => $params);
            return self::$CI->load->view('common/TrackingCodes/SCANSmartPixel', $data, TRUE);
        }
    }
}