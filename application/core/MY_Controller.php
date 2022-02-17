<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * Most of our controllers share something in common with each other...
 * MY_Controller is a basic core library extension. Whenever we create a
 * class with the MY_ prefix the CodeIgniter Loader class will load this
 * after loading the core library, allowing your code to replace/extend
 * the core library
 * Create a base class that all of our Controllers and "controller types"
 * will inherit. Anything we put in here and assign to $this will be
 * available to anything that extends this class.
 *
 * user validation & othr stuff shud move here.
 */

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {

    }

    /**
     * THIS FUNCTION Called by defualt first.Nice Feature provided by CI
     * Actually When we put _remap FUNCTION in any controller, Codeigniter
     * Execute it first (ofcourse after constructer :P)
     */
    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->show404();
        }
    }

    /**
     *  Custom show404 Function. it handle cases when class found but menthod not found or both are unknown
     *
     */
    function show404()
    {
        GLOBAL $flag_mobile_user_agent; 
        GLOBAL $flag_mobile_js_support_user_agent;
        $this->flag_mobile_js_support_user_agent = $flag_mobile_js_support_user_agent;
        $this->flag_mobile_user_agent =  $flag_mobile_user_agent; 
        if((($this->flag_mobile_user_agent == "mobile") || ($_COOKIE['ci_mobile'] == 'mobile')) && ($_COOKIE['user_force_cookie']  != "YES"))
        {
             log_message('error', '404 Page Not Found --> ' . $page);
            header("Sorry, we couldn't find the page you requested.", true, 404);
            $this->config = & get_config();
            $base_url = $this->config['base_url'];
            $ch = curl_init();
	    if( ($this->flag_mobile_js_support_user_agent == "yes") || ($_COOKIE['ci_mobile_js_support'] == 'yes') ){
	            curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon5/MobileSiteStatic/mobile404");
	    }
	    else{
	            curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon/MobileSiteStatic/mobile404");
	    }
            curl_exec($ch);
            curl_close($ch);
            exit();
        }
        header("Sorry, we couldn't find the page you requested.", true, 404);
        $data['errorPageFlag'] = 'true';
        echo $this->load->view('shikshaHelp/404Page',$data,true);
        exit();
    }

    protected function index($config)
    {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->xmlrpc->set_debug(0);
        $this->xmlrpcs->initialize($config);
        $this->xmlrpcs->serve();
    }

    protected function sendXmlRpcResponse($response,$encode = TRUE)
    {
        if($encode) {
            $response = utility_encodeXmlRpcResponse($response);
        }

        try{
            return $this->xmlrpc->send_response($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
    }

    protected function sendXmlRpcError($errorMsg)
    {
        return $this->xmlrpc->send_error_message('123', $errorMsg);
    }
}

// END MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
