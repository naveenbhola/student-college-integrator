<?php


class Call{

    private $CI;
	private $callURL;
	private $curlObj;
	
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->callURL = 'http://www.smartivr.in//api/voice/click2connect/connect/';
		$this->curlObj = $this->CI->load->library('curl');
	}
	
	public function connectCall($caller,$called,$param){
		$data = $this->curlObj->_simple_call('get',$this->callURL,array(
															  'username' => 'Shiksha',
															  'password' => '123456',
															  'format' => 'xml',
															  'cmd' => 'CALL',
															  'ivr_id' => '800057952',
															  'callernumber' => $called,
															  'param1' => ereg_replace('[^A-Za-z0-9]'," ",$param),
															  'callednumber' => $caller,
															  'agent_first' => 1
															  ));
	}

}
