<?php
class OnlineFormEnterprise_server extends MX_Controller
{
	function index()
	{
                $this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('OnlineFormConfig');error_log("sCheckOnlineFormEnterpriseTabStatus==");
        	$this->load->library('ndnc_lib');
                $this->load->model('onlineformenterprise_model');
		$config['functions']['getBreadCrumbForEnterpriseUser'] = array('function' => 'OnlineFormEnterprise_server.getBreadCrumbForEnterpriseUser');
                $config['functions']['sCheckOnlineFormEnterpriseTabStatus'] = array('function' => 'OnlineFormEnterprise_server.sCheckOnlineFormEnterpriseTabStatus');
                $config['functions']['sSendAlertFromEnterpriseToUser'] = array('function' => 'OnlineFormEnterprise_server.sSendAlertFromEnterpriseToUser');
                $config['functions']['sgetAllAlerts'] = array('function' => 'OnlineFormEnterprise_server.sgetAllAlerts');
                $config['functions']['updateOnlineFormEnterpriseStatus'] = array('function' => 'OnlineFormEnterprise_server.updateOnlineFormEnterpriseStatus');
		$config['functions']['getOnlineFormLabelsAndValues'] = array('function' => 'OnlineFormEnterprise_server.getOnlineFormLabelsAndValues');
                $args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

        function getBreadCrumbForEnterpriseUser(){
            
        }
        function sCheckOnlineFormEnterpriseTabStatus($request){ 
		$parameters = $request->output_parameters();
		$userId = $parameters[0];
        $instituteId = $parameters[1];
                $final = $this->onlineformenterprise_model->checkOnlineFormEnterpriseTabStatus($userId, $instituteId);
                //error_log("sCheckOnlineFormEnterpriseTabStatus==".print_r($final,true));
                $response = array(
		    array(
		    'instituteName'=>$final['listing_title'],
                    'instituteId'=>$final['listing_type_id'],
		    'courseId'=>$final['courseId'],
		     ),
		    'struct');
        	return $this->xmlrpc->send_response($response);

	}

        function sSendAlertFromEnterpriseToUser($request){
            $parameters = $request->output_parameters(); error_log("sendAlertFromEnterpriseToUserModel server<<==".print_r( $parameters,true));
            $userAndFormIds = $parameters[0];
            $actionType = $parameters[1];
            $instituteId = $parameters[2];
            $calenderDate = $parameters[3];
            $typeOfUser  = $parameters[4];
	        $instituteSpecId  = $parameters[5];
            $final = $this->onlineformenterprise_model->sendAlertFromEnterpriseToUser($userAndFormIds,$actionType,$instituteId,$calenderDate,$typeOfUser,$instituteSpecId);
            $response = array(
                    array(
                   'result'=>array($actionType,'string'),
                     ),
                    'struct');
            return $this->xmlrpc->send_response($response);
        }

        function sgetAllAlerts($request){
              $parameters = $request->output_parameters();error_log("getAllAlerts server<<==".print_r( $parameters,true));
              $onlineFormEnterpriseInfo = $parameters[0];
              $actionType = $parameters[1]; error_log("whatinit==<<<>>".print_r($onlineFormEnterpriseInfo,true));
              $alertsInfo = json_encode($this->onlineformenterprise_model->getAllAlerts($onlineFormEnterpriseInfo,$actionType));error_log("getAllAlerts Server<<==>>".print_r($alertsInfo ,true));
              $response = array(
		    array(
		   'alerts'=>array($alertsInfo,'string'),
		     ),
		    'struct');
        	return $this->xmlrpc->send_response($response);

        }
        function updateOnlineFormEnterpriseStatus($request){
                $parameters = $request->output_parameters();
                $instituteid=$parameters['0'];
                $userid=$parameters['1'];
                $formid=$parameters['2'];
                $data = array();
                $data = $this->onlineformenterprise_model->updateOnlineFormEnterpriseStatus($instituteid,$userid,$formid);
        }

	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format Start
	/***************************************************/
	function getOnlineFormLabelsAndValues($request){
                $parameters = $request->output_parameters();
                $courseId = $parameters['0'];
		$instituteId  =$parameters['1'];
                $data = array();
                $data = $this->onlineformenterprise_model->getOnlineFormLabelsAndValues($courseId,$instituteId);
                $responseString = base64_encode(gzcompress(json_encode($data)));
                $response = $responseString;
                return $this->xmlrpc->send_response($response);
        }
	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format End
	/***************************************************/

}
?>
