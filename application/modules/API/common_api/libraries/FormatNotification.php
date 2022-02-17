<?php
/**
 * FormatNotification Class.
 * Class for formatting In-app and GCM notification
 * @date    2015-11-02
 * @author  Romil Goel
 * @todo    none
*/

class FormatNotification {

    /**
     * constructor
     */
    public function __construct(){

    }

    /**
     * Method to format the GCM and local notifications to a common format
     * Common Format : 
    {
       notificationId:"15651623",
       notificationType:"0",
       commandType:"0",
       landingPage:"0",
       userId:"RERT1524",
       messageTitle:"User Followed",
       messageDescription:"User has followed you on blah blah blah",
       primaryId:"TRTRT565665",
       secondaryData:["413141342","16562526"],
       iconUrl:"",
       actions:[
         {
           actionType:"0",
           actionTitle:"Follow"
         }
         ],
         trackingUrl:""
    }
     */
    
    public static function format($details){

        $formattedData = array();

        $formattedData['notificationId']     = $details['notificationId'];
        $formattedData['notificationType']   = $details['notificationType'];
        $formattedData['commandType']        = $details['commandType'] ? $details['commandType'] : 0;
        $formattedData['landingPage']        = $details['landingPage'];
        $formattedData['userId']             = $details['userId'];
        $formattedData['messageTitle']       = $details['messageTitle'];
        $formattedData['messageDescription'] = $details['messageDescription'];
        $formattedData['primaryId']          = $details['primaryId'];
        $formattedData['secondaryData']      = $details['secondaryData'];

         if($details['readStatus'] == 'read'){
            $formattedData['readStatus'] = true;    
          } else{
            $formattedData['readStatus'] = false;    
          }

        $formattedData['iconUrl']            = $details['iconUrl'] ? $details['iconUrl'] : 'D';
        $formattedData['actions']            = $details['actions'] ? $details['actions'] : array();
        $formattedData['trackingUrl']        = $details['trackingUrl'] ? $details['trackingUrl'] : "";
        $formattedData['time']               = $details['time'] ? $details['time'] : "";

        if($details['dynamicFieldList']){
            foreach ($details['dynamicFieldList'] as $key => $value) {
              $formattedData[$key] = $value;
            }
          }

        return $formattedData;
    }
}
