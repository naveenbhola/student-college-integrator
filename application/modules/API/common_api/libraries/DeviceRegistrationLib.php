<?php
class DeviceRegistrationLib
{
    private $CI;
    
    function __construct() {
        $this->CI             = &get_instance();
        $this->apiSecurityLib = $this->CI->load->library("common_api/APISecurityLib");
        $this->apicommonmodel = $this->CI->load->model("common_api/apicommonmodel");
    }

    /**
     * Generate a unique Authentication key for the device if shared identifier is verified
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-09
     * @param  [string]     $sharedIdentifier [description]
     */
    function generateAuthKey($sharedIdentifier){
        
        $response = array();

        // if shared identifier is correct
        if($this->verifyIdentifier($sharedIdentifier)){

            // get a unique id
            $uid = uniqid();

            // encrypt the uid
            $authkey = $this->apiSecurityLib->encrypt($uid);

            // insert details in DB
            if($authkey){
                $this->apicommonmodel->insertDeviceInfo($uid);
                $response['authKey']         = $authkey;
                $response['responseCode']    = 0;
            }
            else{
                $response['responseCode']    = 1;
                $response['responseMessage'] = "Not able to generate AuthKey";
            }
        }else{
            $response['responseCode']    = 1;
            $response['responseMessage'] = "Shared Identifier mismatch";
        }

        return $response;
    }

    /**
     * Verify the given shared identifier whether it is valid or not
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-13
     * @param  [string]     $sharedIdentifier 
     * @return [boolean]    validity of given shared identifier
     */
    function verifyIdentifier($sharedIdentifier){

        $decryptedIdentifier = $this->apiSecurityLib->decrypt($sharedIdentifier);

        if($decryptedIdentifier == API_IDENTIFIER || SKIP_KEYWORD_VERIFICATION){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * to get UID from given authKey
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-13
     * @param  [string]     $authKey [Authentication Key]
     * @return [string]              [Plain UID]
     */
    function getUIDfromAuthkey($authKey){

        $uid = $this->apiSecurityLib->decrypt($authKey);
        $uid = strrev($uid);

        return $uid;
    }

    /**
     * to check validity of given authKey from DB
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-13
     * @param  [string]     $authKey [Authentication Key]
     * @return [boolean]    [whether given authKey is valid or not]
     */
    function checkAuthKey($authKey){

        // $uid    = $this->getUIDfromAuthkey($authKey);
        $uid    = $authKey;
        $status = $this->apicommonmodel->checkIfUIDExists($uid);

        return $status;
    }

    function enableDevice($authKey){

        $response = array();
        $uid      = $this->getUIDfromAuthkey($authKey);

        $this->apicommonmodel->enableUID($uid);

        $response['authKey']         = $uid;
        $response['responseCode']    = 0;

        return $response;
    }
}