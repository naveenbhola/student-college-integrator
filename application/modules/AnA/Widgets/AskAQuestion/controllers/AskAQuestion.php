<?php

class AskAQuestion extends MX_Controller
{
    function index($skin,$careerName='',$catId='',$subCatId='',$trackingPageKeyId='')
    {
        switch($skin) {
            case 'categoryPageBottom':
		$arr['crs_pg_prms'] = $subCatId."_".$catId;
        $arr['questionTrackingPageKeyId'] = 528;
                $this->load->view('CategoryPageBottom',$arr);
                break;
            case 'categoryPageRight':
		$arr['crs_pg_prms'] = $subCatId."_".$catId;
        if(empty($trackingPageKeyId))
                        $trackingPageKeyId = 206;
        $arr['trackingPageKeyId'] = $trackingPageKeyId;
                $this->load->view('CategoryPageRight',$arr);
                break;
	    case 'careerPageBottom';
		 $arr['careerName'] = $careerName;
         $arr['trackingPageKeyId']=69;
		 $this->load->view('CareerPageBottom',$arr);
                break;

        }
    }
}
