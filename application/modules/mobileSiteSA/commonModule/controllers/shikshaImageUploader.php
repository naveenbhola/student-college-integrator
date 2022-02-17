<?php 
class shikshaImageUploader extends MX_Controller
{
	private $validateuser;
	function __construct(){
		parent::__construct();
		$this->validateuser = $this->checkUserValidation();
	}

	public function removeMyProfilePic(){
		if(!$this->input->is_ajax_request()){
			echo '-1';exit;
		}
		if($this->validateuser == 'false'){
			echo '0';
			exit;
		}
		$usermodel = $this->load->model('user/usermodel');
		$usermodel->updateProfileImageUrl($this->validateuser[0]['userid'], '');
		$usermodel->addUserToIndexingQueue($this->validateuser[0]['userid']);
		$this->refreshUserLoggedInContentInCache();
		echo '1';
		exit;
	}

	public function saveTheCroppedImage(){
		if(!$this->input->is_ajax_request()){
			echo '-1';exit;
		}
		$curlUrl = MDB_SERVER.'uploadCroppedImage.php';
		$data = $this->input->post('dataImage', true);
		if(empty($data) || $this->validateuser == 'false'){
			echo '0';
			exit;
		}
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);
		
		$imgUrl = '/images/'.time().$this->validateuser[0]['userid'].'.'.basename($type);
		$postData = array('data'=>$data, 'imgPath'=>MEDIA_BASE_PATH.$imgUrl);
		$this->makeCurlCall($postData, $curlUrl);
		$imgUrl = '/mediadata'.$imgUrl;
		if($this->validateuser[0]['userid'] > 0 && !empty($imgUrl)){
			//update tuser's avtarimageurl column
			$usermodel = $this->load->model('user/usermodel');
			$usermodel->updateProfileImageUrl($this->validateuser[0]['userid'], $imgUrl);
			$usermodel->addUserToIndexingQueue($this->validateuser[0]['userid']);
		}
		$this->refreshUserLoggedInContentInCache();
		echo '1';
		exit;
	}
	private function makeCurlCall($post_array,$url)
	{
		$c = curl_init();
      	curl_setopt($c, CURLOPT_URL,$url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, 1);
		/*if (defined('CURLOPT_SAFE_UPLOAD')) {
		  curl_setopt($c, CURLOPT_SAFE_UPLOAD, FALSE);
		}*/
		curl_setopt($c,CURLOPT_POSTFIELDS,$post_array);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $output =  curl_exec($c);
		curl_close($c);
		return $output;
	}
	private function refreshUserLoggedInContentInCache(){
		//LDB provided this code to refresh data
		$this->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        $key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
        $this->cacheLib->clearCacheForKey($key);
	}
	public function logUploadError(){
		$data = array();
		$data['obj'] = $this->input->post('obj');
		$data['txt'] = $this->input->post('txt');
		$data['exp'] = $this->input->post('exp');
		$data = json_encode($data);
		$myfile = file_put_contents('/var/www/html/shiksha/mediadata/uploadErr.txt', $data.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
	}
}