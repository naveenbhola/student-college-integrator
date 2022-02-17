<?php



class Welcome extends MX_Controller {

	function index()
	{
		$this->load->view('welcome_message');
	}
	
	function checkCron()
    {
        $fh = fopen("/tmp/checkCronLog.txt","a");
        fwrite($fh,date("Y-m-d H:i:s"));
        fwrite($fh,"\n");
        fclose($fh);    
    }


    function dynamicContentExp($arg)
    {
		$version = $_GET['version'];
        $this->load->view('contentExp',array('arg'=>$arg, 'version'=>$version));
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
