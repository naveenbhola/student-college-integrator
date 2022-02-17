<?php
/**
 * ResponseFactory Class.
 * Factory Class for creating desired response object on the fly.
 * @date    2015-07-14
 * @author  Romil Goel
 * @todo    none
*/

class ResponseFactory{

     const
            RESPONSE_DEFAULT_ERROR               = -1,
            SUCCESS                              = 0,
            RESPONSE_INVALID_API_KEY             = 1,
            RESPONSE_XML_PARSING_ERROR           = 2,
            RESPONSE_INVALID_INPUT               = 3,
            RESPONSE_TIMEOUT                     = 4,
            RESPONSE_IP_NOT_VALID                = 5,
            RESPONSE_SERVICE_UNAVAILABLE         = 6,
            RESPONSE_INVALID_LOGIN_DETAILS       = 7,
            RESPONSE_METHOD_NOT_ALLOWED          = 8,
            RESPONSE_DEVICE_DISABLED             = 9,
            RESPONSE_UNKNOWN_DEVICE              = 10,
            RESPONSE_SHARED_IDENTIFIER_NOT_FOUND = 11,
            RESPONSE_NOT_FOUND                   = 404;

     function __construct(){
          
     }

     public static function createResponse($responseType) {
        $response = new Response();       

        switch ($responseType) {
            case ResponseFactory::SUCCESS:
                $response->setHttpCode(Response::OK);

            case ResponseFactory::RESPONSE_INVALID_API_KEY:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("INVALID KEY");
                break;

            case ResponseFactory::RESPONSE_XML_PARSING_ERROR:
                $response->setHttpCode(Response::BADREQUEST);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("XML Parsing Error");
                break;
            case ResponseFactory::RESPONSE_INVALID_INPUT:
                $response->setHttpCode(Response::BADREQUEST);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("INVALID INPUT");
                break;
            case ResponseFactory::RESPONSE_TIMEOUT:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("REQUEST TIMEOUT");
                break;
            case ResponseFactory::RESPONSE_IP_NOT_VALID:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("REQUEST IP NOT VALID");
                break;
            case ResponseFactory::RESPONSE_SERVICE_UNAVAILABLE:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("SERVICE AVAILABLE");
                break;
            case ResponseFactory::RESPONSE_INVALID_LOGIN_DETAILS:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("INVALID LOGIN DETAILS");
                break;
            case ResponseFactory::RESPONSE_METHOD_NOT_ALLOWED:
                $response->setHttpCode(Response::METHODNOTALLOWED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("The HTTP method \"" . $request->method . "\" used for the request is not allowed for the resource \"" . $request->uri . "\".");
                break;
            case ResponseFactory::RESPONSE_DEVICE_DISABLED:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("DEVICE NOT ENABLED");
                break;
            case ResponseFactory::RESPONSE_UNKNOWN_DEVICE:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("Unknown Device");
                break;
            case ResponseFactory::RESPONSE_SHARED_IDENTIFIER_NOT_FOUND:
                $response->setHttpCode(Response::UNAUTHORIZED);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("Shared Identifier Not Found");
                break;
            case ResponseFactory::RESPONSE_NOT_FOUND:
                $response->setHttpCode(Response::NOTFOUND);
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->setResponseMsg("Nothing was found for the resource \"" . $request->uri . "\".");
                break;
            default:
                $response->setHttpCode(Response::NOTMODIFIED);
        };

        return $response;
    }

    private static function getErrorResponseBody($code, $msg) {
          $errResponse = array(
            array('response' => array(
                    array('code' => $code, 'msg' => $msg)
                )
            )
          );
        
          return $errResponse;
    }
}