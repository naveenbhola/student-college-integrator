<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: puneet $:  Author of last commit
$Date: 2009-05-20 07:53:49 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: Upload_client.php,v 1.10 2009-05-20 07:53:49 puneet Exp $:

*/
class Upload_client
{
	var $uploadFileUrl = '';
	var $getImageUrl = '';

	function init()
	{
//		$this->uploadFileUrl = "http://172.16.1.14/mediadata/UploadinServer.php";
		$this->getImageUrl = MDB_SERVER."MediaDataForBlog.php";
	}


	/*
	The .
	*/
	function uploadFile($AppId,$Mediatype,$FILES,$ImageCaptions,$productId,$type,$mediaArrName)
	{
		
		$this->init();
		$this->uploadFileUrl = MDB_SERVER."Upload.php";
		$post_array = array();
		$ret_array = array();
                $iCount = 1 ;
                $responseImageCaption;
		if($Mediatype == "ytvideo")
		{
            foreach ($FILES as $key=>$value)
			{
				$post_array['file'.$iCount] = $FILES[$key];
				$post_array['description'.$iCount]=$ImageCaptions[$key];
				$post_array['name'.$iCount] = $ImageCaptions[$key];
				$responseImageCaption[$iCount]=$ImageCaptions[$key];
				$iCount = $iCount + 1;
			}
		}
		else if($Mediatype == "spliceMedia"){
			foreach ($FILES as $key => $value) {			
				//$post_array['file'.$iCount] = "@".$FILES[$key]['tmp_name'];
                $post_array['file'.$iCount] = new CURLFile($FILES[$key]['tmp_name']);
				$post_array['type'.$iCount] = $FILES[$key]['type'];
				$post_array['name'.$iCount] = $FILES[$key]['name'];
				$post_array['description'.$iCount]='spliceDocument';
				$responseImageCaption[$iCount]=$key;
				$iCount = $iCount + 1;

			}
		}else
		{


                       while(list($key,$value) = each($FILES[$mediaArrName]['tmp_name']))
			{
				if(!empty($value))
				{
					//$post_array['file'.$iCount] = "@".$FILES[$mediaArrName]['tmp_name'][$key];
                    $post_array['file'.$iCount] = new CURLFile($FILES[$mediaArrName]['tmp_name'][$key]);
					$post_array['type'.$iCount] = $FILES[$mediaArrName]['type'][$key];
					$post_array['name'.$iCount] = $FILES[$mediaArrName]['name'][$key];
					$post_array['description'.$iCount]=$ImageCaptions[$key];
					$responseImageCaption[$iCount]=$ImageCaptions[$key];
					$iCount = $iCount + 1;

				}
			}
		}
		$post_array['mediatype']=$Mediatype;
		$post_array['count'] = $iCount;
		$post_array['Id'] = $productId;
		$post_array['type'] = $type;



                error_log("post array is ".print_r($post_array,true));

                $output=  $this->makeCurlCall($post_array,$this->uploadFileUrl);



               error_log_shiksha($output);
		$responseObj = unserialize($output);


		$mediadata = array();
		$i = 0;
	//	error_log("response obj".print_r($responseObj,true));
		error_log_shiksha($Mediatype);
		error_log_shiksha("TESTTESTTEST".$responseObj["status"]);




           if($responseObj["status"] == 1)
            {



                for($i=0;$i < (count($responseObj) - 1); $i++ )
                {
                error_log_shiksha(count($responseObj));
                error_log_shiksha($Mediatype);
                    switch($Mediatype) {
                        case 'image':
                        array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['imageurl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['thumburl'],'thumburl_m' => $responseObj[$i]['thumburl_m']));
                            break;
                        case 'pdf':
                        case 'email':
error_log_shiksha($Mediatype);
                        array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['pdfurl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['pdfurl'], 'filename' => $responseObj[$i]['name']));
                            break;
                        case 'text':
                        array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['texturl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['texturl']));
                            break;
                        case 'video':
                        array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['videourl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['thumburl']));
                            break;
                        case 'ytvideo':
                        array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['videourl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['thumburl']));
                            break;

                        case 'spliceMedia':
                        case 'saApplyMedia':
						array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['pdfurl'],'title' => $responseImageCaption[$i+1],'thumburl' => $responseObj[$i]['pdfurl']));
                            break;
                    }
                }
                $ret_array['max'] = $i;
                $ret_array['status'] = 1;
                return $ret_array;
            }
		else
		{

                        return $responseObj["error_msg"];
		}
	}

	function getImageInfo($AppId,$productId,$type)
	{
		$this->init();

		$this->uploadFileUrl = MDB_SERVER."UploadinServer.php";
		$post_array = array();
		$blogmages = array();
		$post_array['type'] = $type;
		$post_array['id'] = $productId;

		$output=  $this->makeCurlCall($post_array,$this->getImageUrl);
	        $responseObj = unserialize($output);

	        $i = 0;
	        while($i < $responseObj['count'])
	        {
	            array_push($blogmages,array('image' => $responseObj['url'.$i],'caption' => $responseObj['description'.$i]));
	            //$responseObj['thumburl'.$i];
	            $i = $i + 1;
	        }
	   return $blogmages;
	}

	function makeCurlCall($post_array,$url)
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


}
?>
