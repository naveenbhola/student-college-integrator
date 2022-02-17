<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_amp_response_header() {
    //error_log('-----------------------------'.$_GET['__amp_source_origin']);
        if(!empty($_GET['__amp_source_origin']) && $_GET['__amp_source_origin'] == SHIKSHA_HOME)
        {
            header('access-control-allow-credentials:true');
            /*header('access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token');
            header('access-control-allow-methods:POST, GET, OPTIONS');*/
            header('access-control-allow-origin:'.AMP_SHIKSHA_DOMAIN);
            header('access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin');
            header('amp-access-control-allow-source-origin:'.SHIKSHA_HOME);
        }
}

