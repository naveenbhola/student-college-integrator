<?php
/**
 * GCMNotification Class.
 * Class for sending GCM Notifications to the User's APP
 * @date    2015-11-02
 * @author  Romil Goel
 * @todo    none
*/

class GCMNotification {

    private $url = 'https://android.googleapis.com/gcm/send';

    /**
     * constructor
     */
    public function __construct(){
        $CI = &get_instance();
        $CI->load->library("common_api/FormatNotification");
        $CI->load->model("common_api/apicommonmodel");
        $CI->load->config("apiConfig");
    }

    function sendNotification($registrationIds, $details){

        $notificationId = $details['notificationId'];

        if(empty($notificationId))
            return false;

        // format the data fields to the common format
        $data = FormatNotification::format($details);
        $data = array("extraData" => $data);

        $fields = array(
                'registration_ids' => $registrationIds,
                'data' => $data
        );

        $headers = array(
                'Authorization: key='.GCM_API_KEY,
                'Content-Type: application/json'
        );


        if($details['TTL']!='' && $details['TTL']!=0){
            $fields['time_to_live'] = intval($details['TTL']);
        }
        else{
            $fields['time_to_live'] = 172800; // 2 days
        }

        if($details['collapse_key']){
            $fields['collapse_key'] = $details['collapse_key'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result     = curl_exec($ch);
        
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response   = json_decode($result, true);

        // update response and status of the notification
        $apicommonmodel = new apicommonmodel();
        $apicommonmodel->updateGCMResponseStatus($notificationId , $response, $httpStatus, 'sent');

        return $response;

    }
   
}
