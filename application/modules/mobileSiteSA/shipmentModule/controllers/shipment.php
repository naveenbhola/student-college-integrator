<?php

class shipment extends MX_Controller{

    private function _init(& $displayData){
        $displayData['validateuser'] = $this->checkUserValidation();

        $this->load->config('shipment/shipmentConfig');
        $this->load->config('shipment/studentTestimonialsConfig');
        $this->shipmentCommonLib = $this->load->library('shipment/shipmentCommonLib');
    }

    function welcomePage(){
        $displayData = array();
        $this->_init($displayData);
        // url validation
        $seoDetails = $this->config->item('seoDetails');
        $this->shipmentCommonLib->validateURL($seoDetails['welcomePage']['url']);

        // prepare seo details
        $displayData['schedulePickupPageUrl'] = $seoDetails['schedulePickupPage']['url'];
        $displayData['confirmationPageUrl'] = $seoDetails['confirmationPage']['url'];
        $displayData['seoDetails'] = $seoDetails['welcomePage'];

        // student testimonials
        $displayData['studentTestimonialsForShipment'] = $this->config->item('studentTestimonialsForShipment');

        $displayData['shipmentPriceComparision'] = $this->config->item('shipmentPriceComparision');

        $displayData['alreadySchedulePickups'] = false;
        if($displayData['validateuser'] !== "false"){
            // check if user already placed a shipment request.
            $displayData['alreadySchedulePickups'] = $this->shipmentCommonLib->checkIfUserAlreadySchdulePickups((integer)$displayData['validateuser'][0]['userid']);
        }

        // MIS Tracking
        $displayData['beaconTrackData'] = $this->shipmentCommonLib->prepareTrackingData('shipmentWelcomePage');
        $displayData['dontLoadRegistrationJS'] = true;
        $displayData['trackingKeyIdBookYourPickupTop'] = 1245;
        $displayData['trackingKeyIdBookYourPickupBottom'] = 1246;
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        //$displayData['jqMobileFlag'] =true;
        $this->load->view('shipmentModule/welcomePageOverview',$displayData);
    }
}
