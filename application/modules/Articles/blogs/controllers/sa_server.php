<?php
class Sa_server extends MX_Controller
{

	/*
	*	index function to recieve the incoming request
	*/

    function index(){

            //load XML RPC Libs
            $this->load->library('xmlrpc');
            $this->load->library('xmlrpcs');
            $this->dbLibObj = DbLibCommon::getInstance('SAContent');
            
            $config['functions']['updateViewCountforAbroadContent'] = array('function' => 'Sa_Server.updateViewCountforAbroadContent');

            //initialize
            $args = func_get_args(); $method = $this->getMethod($config,$args);
            return $this->$method($args[1]);
    }

    function updateViewCountforAbroadContent($request){   error_log("2222222222");
            $this->db = $this->_loadDatabaseHandle('write');
            $parameters = $request->output_parameters();
            $content_type=$parameters['1'];
            $content_id=$parameters['2'];
            
            $selectSql = "select content_id, updated_on from sa_content where content_id=? and status='live' ";
            $selectedContent = $this->db->query($selectSql, array($content_id))->result_array();
            $sql = "update sa_content set view_count=view_count + 1 , updated_on = ? where content_id=? and status = '" . ENT_SA_PRE_LIVE_STATUS . "'";
            if(!$this->db->query($sql, array($selectedContent[0]['updated_on'], $content_id))){
			$response = array(array('error'=>'Query Failed','struct'),'struct');
			return $this->xmlrpc->send_response($response);
	    }
            $sql = "INSERT INTO sa_content_view_count (content_type,content_id,view_date,view_count) VALUES (?,?,CURDATE(),1) ON DUPLICATE KEY UPDATE view_count = view_count+1";
        
            if(!$this->db->query($sql, array($content_type,$content_id) )){
			$response = array(array('error'=>'Query Failed','struct'),'struct');
			return $this->xmlrpc->send_response($response);
	    }
            $response = array('added','struct');
	    return $this->xmlrpc->send_response($response);
    }
}
?>
