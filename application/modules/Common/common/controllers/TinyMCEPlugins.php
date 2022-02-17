<?php 
class TinyMCEPlugins extends MX_Controller
{
	public function uploadDocFile() {
		$response = array();
		if(!empty($_FILES['shikshaFileDialog']['tmp_name']) && count($_FILES['shikshaFileDialog']['tmp_name'] > 0)) {
			$this->load->library('upload_client');
			$uploadclient = new upload_client();
			$upload_array = $uploadclient->uploadFile($appId, 'pdf', $_FILES, array(), "-1", "exam", 'shikshaFileDialog');
			if($upload_array['status'] == 1){
				$response = array(
					'title'   => $this->_getCleanName($_FILES['shikshaFileDialog']['name'][0]),
					'fileUrl' => addingDomainNameToUrl(array('url' => $upload_array[0]['imageurl'], 'domainName' => MEDIA_SERVER)),
					'mediaId' => $upload_array[0]['mediaid']
				);
			}else{
				$response = $upload_array;
			}
		}
		echo json_encode($response);
	}

	private function _getCleanName($string){
		if(empty($string)){
			return '';
		}
		$parts = explode('.', $string);
		unset($parts[count($parts)-1]);
		$string = implode('.', $parts);
		$string = str_replace(' ', '_', $string);
		return $string;
	}
}