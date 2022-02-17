<?php
switch($this->config->item('ga_code')){
	case 'old': $this->load->view('common/ga_old');
	break;
	case 'new_minified': $this->load->view('common/ga_new_minified');
	break;
	case 'new_src': $this->load->view('common/ga_src');
	break;
	default: $this->load->view('common/ga_new_minified');
}

parse_str($_SERVER['QUERY_STRING'], $_REQUEST);

if($_REQUEST['mmpbeacon'] == 1) {
	
        //error_log('XXXXXXXXXX0'.print_r($_REQUEST,true));
} else {
        //loadBeaconTracker($beaconTrackData);

}

?>
