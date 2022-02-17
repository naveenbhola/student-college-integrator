<?php
class CoursePageCms extends MX_Controller {

	private $_validateuser = NULL;
	private $_coursepagecmsmodel = NULL;

	public function __construct()  {

		$this->load->model('coursepages/coursepagecmsmodel');
		$this->load->model('coursepages/coursepagemodel');
		$this->cache = $this->load->library('coursepages/cache/CoursePagesCache');
		$this->_coursepagecmsmodel = $this->coursepagecmsmodel;
		$this->_coursepagemodel = $this->coursepagemodel;
		$this->_coursepagesurlrequest = $this->load->library('coursepages/CoursePagesUrlRequest'); 
		//set user details
		$this->_validateuser = $this->checkUserValidation();
		$byepass = strip_tags(trim($_REQUEST['byepass']));
		
		if(empty($byepass)) {
			if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
				header('location:'.ENTERPRISE_HOME);exit();
			}

			if(is_array($this->_validateuser) && $this->_validateuser['0']['usergroup']!='cms') {
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}

		}
	}

	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function featuredInstitute($edit=0,$carousel_id=0,$faq_flag=0) {

		$data['headerContentaarray'] = $this->loadHeaderContent();
                $courseCommonLib=$this->load->library('coursepages/CoursePagesCommonLib');
                $data['COURSE_PAGES_HOME_ARRAY']=$courseCommonLib->getCourseHomePageDictionary(0);
                if($_GET['faq_test']==1){
			$this->loadFAQContainer();
		}
		else{
			$this->load->view('coursepages/coursepageCmsFeaturedInstitute',$data);
		}

	}

	public function saveNewFaqs() {
		//_p($_REQUEST);

		$coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
		//$coursePagesCache = $this->load->library('coursepages/cache/CoursePagesCache');
		//sub category id
		$courseHomePageId      = $this->input->post('courseHomePageId');
		$faqHeadingId  = $this->input->post('faqHeadingId');
		if($faqHeadingId>0)
		{	//this is saved only when in edit mode
			$this->EditOldFaqs($courseHomePageId,$faqHeadingId);
			return;
		}
		//group headings / section headings
		$groupHeadings  = $this->input->post('groupHeading');
		//faqs
			$questionTexts  = $this->input->post('faq_ques');
			$answerTexts    = $this->input->post('faq_ans',false);
			$sequences      = $this->input->post('faq_sequence');
			$totalFaqHeadings= $this->input->post('totalFaqHeadings');
			error_log("grp head-------".$totalFaqHeadings);
		for($key = 0;$key<$totalFaqHeadings;$key++){
			$groupHeading=$groupHeadings[$key];
			//if the heading entered exists do not save the heading just get its id to save the faqs in it.
			$groupHeadingId = $this->_coursepagecmsmodel->searchFaqHeading($groupHeading,$courseHomePageId);
			if($groupHeadingId == FALSE){
				//...else save new heading/section & get it's id
				$groupHeadingId = $this->_coursepagecmsmodel->addFaqHeading($groupHeading,$courseHomePageId);
			}
				
			for($i=0;$i<count($questionTexts[$key]);$i++)
			{
				//save the faq under new heading if provided or use the old one.
				$questionId = $this->_coursepagecmsmodel->addFaqDetails($groupHeadingId,$questionTexts[$key][$i],$answerTexts[$key][$i]);//,$sequences[$key][$i],$questionUrl);
				//create url for question
			}
		}
		//invalidate cache
		$this->cache->deleteFaqWidgetData($courseHomePageId);
		$newGroupHeading = $this->_coursepagecmsmodel->searchFaqHeading($groupHeadings[0],$courseHomePageId);
		echo json_encode(array($newGroupHeading,"Thanks, Data has been saved successfully."));


	}
	
	public function EditOldFaqs($courseHomePageId,$faqHeadingId) {
		//_p($_REQUEST);
		//echo json_encode($_REQUEST);

		$coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
		//$coursePagesCache = $this->load->library('coursepages/cache/CoursePagesCache');
		//sub category id
		$courseHomePageId      = $this->input->post('courseHomePageId');
		//group headings / section headings
		$groupHeadings  = $this->input->post('groupHeading');
		$key = 0; $i = 0;

		//faq data
		$questionTexts    = $this->input->post('faq_ques');
		$answerTexts      = $this->input->post('faq_ans',false);
		$sequences        = $this->input->post('faq_sequence');
		$headingSequence  = $this->input->post('faq_heading_sequence');
		$faqId            = $this->input->post('faqId');
		
		error_log("----------------".$faqHeadingId);
		//use the heading id passed to update the particular heading first(no need to search)
		$updateHeadingResult = $this->_coursepagecmsmodel->updateFaqHeading($courseHomePageId,$faqHeadingId,$headingSequence,$groupHeadings[0]);
	
		//update the faq under the given headingId
		$updateFaqResult = $this->_coursepagecmsmodel->updateFaqDetails($faqHeadingId,$faqId[$key][$i],$questionTexts[$key][$i],$answerTexts[$key][$i],$sequences[$key][$i]);
		//create url for question
				
		//invalidate cache
		$this->cache->deleteFaqWidgetData($courseHomePageId);
		$newGroupHeading = $this->_coursepagecmsmodel->searchFaqHeading($groupHeadings[$key],$courseHomePageId);
		echo json_encode(array($newGroupHeading,"Thanks, Data has been updated successfully."));

		
	}

	public function loadFaqSequence($groupHeadingId,$courseHomePageId)
	{ //echo "--".$groupHeadingId."--".$courseHomePageId;
		$sequence = $this->_coursepagecmsmodel->getFaqSequence($groupHeadingId);
		$response = array();
		foreach($sequence as $seq)
		{
			array_push($response, $seq['position']);
		}
		echo json_encode($response);
	}

	public function searchFaqHeading($groupHeading,$courseHomePageId)
	{ //echo "--".$groupHeading."--".$courseHomePageId;
		_p($_REQUEST);die;
		$groupHeadingId = $this->_coursepagecmsmodel->searchFaqHeading($groupHeading,$courseHomePageId);
		if($groupHeadingId == FALSE)
		{
			echo "false";
		}
		else{
			echo $groupHeadingId;
		}
	}
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function loadHeaderContent() {

		$headerComponents = array(
	        'css'   =>  array('headerCms','mainStyle','course-cms','common_new'),
	        'js'    =>  array('common','coursepages'),
	        'displayname'=> (isset($this->_validateuser[0]['displayname'])?$this->_validateuser[0]['displayname']:""),
	        'tabName'   =>  '',
	        'taburl' => site_url('enterprise/Enterprise'),
	        'metaKeywords'  =>'',
	        'prodId'=>59
		);

		$this->load->library('enterprise_client');
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);
		$headerTabs['0']['prodId'] = 59;
		$headerComponents['headerTabs'] = $headerTabs;
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}

	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function uploadImage($files, $vcard = 0,$appId = 1){

		if($files['myImage']['tmp_name'][0] == '')
		$data['error_message'] = "Please select a photo to upload";
		else
		{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->_validateuser['0']['userid'],"user", 'myImage');

			if(!is_array($upload)) {
				$data['error_message_upload'] =  $upload;
			} else
			{
				$imageURL = addingDomainNameToUrl(array('url' => $upload[0]['imageurl'] , 'domainName' =>MEDIA_SERVER));
				
				list($width, $height, $type, $attr) = getimagesize($imageURL);
				$data['data']            =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
				$data['width']           = $width;
				$data['height']          = $height;
			}

		}

		return $data;

	}

	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function deleteSlide($carousel_id,$carousel_order,$courseHomePageId) {
                $rows = $this->_coursepagecmsmodel->deleteSlide($carousel_id,$carousel_order,$courseHomePageId);
		// delete slide info in cacche
		$this->cache->deleteSlideInfo($courseHomePageId);
		$this->cache->deleteSlideSlotId($courseHomePageId);
		$data = $this->_coursepagecmsmodel->getSlides("",$courseHomePageId);
		$html = $this->_getSlidesHtml($data);
		$response = json_encode(array("count_sildes"=>count($data),"RESPONSE_ID"=>$rows,"HTML"=>$html,"DBDATA"=>json_encode($data)));
		echo $response;
	}

	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function reorderSlide($org_order,$orgnl_id,$new_order,$courseHomePageId) {

		$array = array($org_order,$orgnl_id,$new_order,$courseHomePageId);
		if(is_array($array) && count($array)>0) {
			$rows = $this->_coursepagecmsmodel->reorderSlide($array);
		}
	}

	public function addSlide($edit = 0) {
		$result_id = NULL;
		$courseHomePageId = $this->input->get_post('subcatId',true);
                $imageTitle = $this->input->get_post('imageTitle',true);

		$landingUrl = $this->input->get_post('landingUrl',true);
		$open_new_tab = $this->input->get_post('open_new_tab',true);
		$slide_id = $this->input->get_post('slide_id',true);
		$original_slide_order = $this->input->get_post('original_slide_order',true);
		$slidePosition = $this->input->get_post('slidePosition',true);
		$count_slides = $this->input->get_post('count_slides',true);

		$imageTitle = str_replace("&amp;","&",$imageTitle);
		if(array_key_exists('open_new_tab', $_POST)) {
			$open_new_tab = 'YES';
		} else {
			$open_new_tab = 'NO';
		}

		$carousel_photo_data = $this->uploadImage($_FILES);
		if(is_array($carousel_photo_data) && !empty($carousel_photo_data['data'])) {
			$imageUrl = $carousel_photo_data['data'];
			//$carousel_photo_data['width'] =455;
			//$carousel_photo_data['height'] =280;
			if(!($carousel_photo_data['width'] ==455 && $carousel_photo_data['height'] ==280)) {
				$data = $this->_coursepagecmsmodel->getSlides("",$courseHomePageId);
				$html = $this->_getSlidesHtml($data);
				$response = json_encode(array("count_sildes"=>count($data),"RESPONSE_ID"=>"Please upload image of required dimensions","HTML"=>$html,"DBDATA"=>json_encode($data)));
				echo $response;
				return;
			}
		} else if(array_key_exists("error_message_upload", $carousel_photo_data)){
			$data = $this->_coursepagecmsmodel->getSlides("",$courseHomePageId);
			$html = $this->_getSlidesHtml($data);
			$response = json_encode(array("count_sildes"=>count($data),"RESPONSE_ID"=>$carousel_photo_data['error_message_upload'],"HTML"=>$html,"DBDATA"=>json_encode($data)));
			echo $response;
			return;
		} else {
			$imageUrl = '';
		}

		if(empty($count_slides)) {
			$edit = 0;
		}

		if($edit ==1) {
			$array_to_update = array(
				'courseHomePageId'=>$courseHomePageId,
				'imageTitle'=>$imageTitle,
				'landingUrl'=>$landingUrl,
	            'open_new_tab'=>$open_new_tab,
				'imageUrl'=>$imageUrl,
				'id'=>$slide_id
			);

			$result_id = $this->_coursepagecmsmodel->updateSlide($array_to_update);

			if($original_slide_order != $slidePosition) {
				$this->reorderSlide($original_slide_order, $slide_id, $slidePosition,$courseHomePageId);
			}

		} else {
			error_log('ADITYATAB0'.$open_new_tab);
			$result_id = $this->_coursepagecmsmodel->addSlide($imageTitle,$landingUrl,$imageUrl,$courseHomePageId,$open_new_tab);
		}

		$data = $this->_coursepagecmsmodel->getSlides("",$courseHomePageId);
		// delete cache
		$this->cache->deleteSlideInfo($courseHomePageId);
		$this->cache->deleteSlideSlotId($courseHomePageId);
		$html = $this->_getSlidesHtml($data);
		$response = json_encode(array("count_sildes"=>count($data),"RESPONSE_ID"=>$result_id,"HTML"=>$html,"DBDATA"=>json_encode($data)));
		echo $response;
	}

	private function _getSlidesHtml($data) {

		$html = "";
		$header_html = "<h5 style='margin-bottom:5px'>Previously Added Slides</h5>".
				   "<table border='1' cellspacing='0' cellpadding='5' bordercolor='#cccccc' class='widget-table'>".
                	"<tr>".
                    	"<th width='70'>Slide No.</th>".
                        "<th width='214'>Photo</th>".
                        "<th width='250'>Title</th>".
                        "<th width='300'>URL</th>".
                        "<th width='80'>Action</th>".
                    "</tr>";

		if(count($data)>0) {
			foreach ($data as $row) {

				$landing_url = urldecode($row['landingUrl']);
				if(strpos($landing_url,"http://") === FALSE && strpos($landing_url,"https://") === FALSE) {
					$landing_url = "http://".$landing_url;
				}

				$html = $html.
					"<tr><td valign='top' align='center'>".$row['slidePosition']."</td>".
					"<td valign='top'><img src=".$row['imageUrl']." /></td>".
					"<td valign='top'>".htmlspecialchars(stripslashes($row['imageTitle']),ENT_QUOTES)."</td>".
					"<td valign='top'>".
				    "<div style='word-wrap:break-word;width:200px;'>".
				    "<a href=".htmlspecialchars($landing_url,ENT_QUOTES).">".htmlspecialchars(stripslashes($landing_url),ENT_QUOTES)."</a>".
					"<p>Open in new window : <strong>".$row['open_new_tab']."</strong></p>".
				    "</div>".
					"</td>".
			        "<td valign='top'>
                        	<input type='button' value='Edit' style='width:70px' onclick='cp_obj.editSlide(".$row['id'].");'/><br />
                            <input type='button' onclick='cp_obj.deleteSlide(".$row['id'].",".$row['slidePosition'].",".$row['courseHomePageId'].");' value='Delete' style='width:70px; margin-top:5px' />
					</td>
                    </tr>";
			}
		}

		if(!empty($html)) {
			$html = $header_html.$html."</table>";
			$html = htmlentities($html);
			$html = base64_encode($html);
		}

		return $html;
	}

	public function addSection($edit =0) {

		$result_id = NULL;
		$courseHomePageId = $this->input->get_post('courseHomePageId',true);
		$sectionHeading = $this->input->get_post('sectionHeading',true);
		$sectionPosition = $this->input->get_post('sectionPosition',true);
		$link_id = $this->input->get_post('link_id',true);
		$linkTitle = $this->input->get_post('linkTitle',true);
		$landinURL = $this->input->get_post('landinURL',true);
		$displayOrder = $this->input->get_post('displayOrder',true);
		$open_new_tab = $this->input->get_post('open_new_tab',true);
		$edited_link_id = $this->input->get_post('edited_link_id',true);
		$section_id = $this->input->get_post('section_id',true);
		$link_order = $this->input->get_post('link_order',true);
		$section_order = $this->input->get_post('section_order',true);
		$updateSection = $this->input->get_post('updateSection',false);
		$section_url = $this->input->get_post('sectionURL',true);

		if($edit ==1) {

			if(!empty($open_new_tab[0])) {
				$tab = 'YES';
			} else {
				$tab = 'NO';
			}

			$array_to_update = array(

				'courseHomePageId'=>$courseHomePageId,
				'sectionHeading'=>$sectionHeading[0],
				'sectionPosition'=>$sectionPosition[0],
			    'sectionURL'=>$section_url[0],
	            'open_new_tab'=>$tab,
				'linkTitle'=>$linkTitle[0][0],
			    'displayOrder'=>$displayOrder[0][0],
				'id'=>$edited_link_id,
			    'sectionId'=>$section_id,
			    'landinURL'=>$landinURL[0][0],
			    'oldDisplayOrder'=>$link_order,
			    'oldSectionposition'=>$section_order

			);

			$result_id = $this->_coursepagecmsmodel->updateLink($array_to_update);

		} else {
			$result_id = $this->_coursepagecmsmodel->addSection($courseHomePageId,$sectionHeading,$sectionPosition,$link_id,$linkTitle,$landinURL,$displayOrder,$open_new_tab,$updateSection,$section_order,$section_id,$section_url);
		}

		$data = $this->_coursepagecmsmodel->getSectionsDetails("",$courseHomePageId);
		$html = $this->_getSectionHtml($data);
		$response = json_encode(array("COUNTSECTION"=>count($data),"RESPONSE_ID"=>$result_id,"HTML"=>$html,"DBDATA"=>json_encode($data)));
		echo $response;
	}

	private function _getSectionHtml($data) {

		$html = "";
		$header_html = "<h5 style='margin-bottom:5px'>Previously Added Section</h5>".
		"<table border='1' cellspacing='0' cellpadding='5' bordercolor='#cccccc' class='widget-table'>".		            	                     
                	"<tr>".
                    	"<th width='180'>Section Heading</th>".
                        "<th width='250'>Titles</th>".
                        "<th width='100'>Title Position</th>".
                        "<th width='300'>URL</th>".
                        "<th width='80'>Action</th>".
                    "</tr> ";
		$final_section_html = "";
		foreach ($data as $section_id =>$section) {
			$section_html = "";
			$link_html = "";
			$count_links = count($section);
			foreach($section as $link) {

				$landing_url = urldecode($link['landinURL']);
				$section_url  = urldecode($link['sectionURL']);
				if(strpos($landing_url,"http://") === FALSE && strpos($landing_url,"https://") === FALSE) {
					$landing_url = "http://".$landing_url;
				}

				if(strpos($section_url,"http://") === FALSE && strpos($section_url,"https://") === FALSE) {
					$section_url = "http://".$section_url;
				}

				if(empty($section_html)) {
					$section_html ="<tr id='".'section_data_'.$section_id."'>".
                    	"<td valign='top' rowspan='".$count_links."'>".
					    "<div style='word-wrap:break-word;width:200px;'>".
                        "<p>".htmlspecialchars(stripslashes($link['sectionHeading']),ENT_QUOTES)."</p>".
                        "<p class='graycolor'>Heading Position:".$link['sectionPosition']."</p>".
					    "<a href='".htmlspecialchars(stripslashes($section_url),ENT_QUOTES)."'>".htmlspecialchars($section_url,ENT_QUOTES)."</a>".
						"<p><a onclick='cp_obj.updateSection(".$link[sectionId].",".$link[sectionPosition].",".htmlspecialchars(stripslashes("'".$link[sectionHeading]."'"),ENT_QUOTES).",".htmlspecialchars("'".$section_url."'",ENT_QUOTES).");' href='#complete_section'>+Add more titles</a></p>".
					    "</div>".
						"</td>".
                        "<td valign=top'>".htmlspecialchars(stripslashes($link['linkTitle']),ENT_QUOTES)."</td>".
                        "<td valign='top'>".$link['displayOrder']."</td>".
                        "<td valign='top'>".
						"<div style='word-wrap:break-word;width:200px;'>".
					     "<a href='".htmlspecialchars(stripslashes($landing_url),ENT_QUOTES)."'>".htmlspecialchars($landing_url,ENT_QUOTES)."</a>".					     
							"<p>Open in new window :<strong>".$link['open_new_tab']."</strong></p>".
					    "</div>".
                        "</td>".
                        "<td valign='top'>".
                        	"<input name='".'section_data_link_'.$section_id."' type='button' onclick='cp_obj.editLink(".$link['sectionId'].",".$link['id'].",".$link['displayOrder'].",".$link['sectionPosition'].");' value='Edit' style='width:70px' /><br />".
                            "<input type='button' onclick='cp_obj.deleteLink(".$link['sectionId'].",".$link['id'].",".$link['displayOrder'].",".$link['courseHomePageId'].");' value='Delete' style='width:70px; margin-top:5px'/>".
						"</td>".
                    "</tr>";
				} else {
					$link_html = $link_html.
				"<tr>".				
                    	"<td valign='top'>".htmlspecialchars(stripslashes($link['linkTitle']),ENT_QUOTES)."</td>".
                        "<td valign='top'>".$link['displayOrder']."</td>".
                        "<td valign='top'>".
						"<div style='word-wrap:break-word;width:200px;'>".
					    "<a href='".htmlspecialchars($landing_url,ENT_QUOTES)."'>".htmlspecialchars(stripslashes($landing_url),ENT_QUOTES)."</a>".
							"<p>Open in new window : <strong>".$link['open_new_tab']."</strong></p>".
					    "</div>".
                        "</td>".
                        "<td valign='top'>".
                        	"<input name='".'section_data_link_'.$section_id."' type='button' onclick='cp_obj.editLink(".$link['sectionId'].",".$link['id'].",".$link['displayOrder'].",".$link['sectionPosition'].");' value='Edit' style='width:70px' /><br />".
                            "<input type='button' onclick='cp_obj.deleteLink(".$link['sectionId'].",".$link['id'].",".$link['displayOrder'].",".$link['courseHomePageId'].");' value='Delete' style='width:70px; margin-top:5px'/>".
						"</td>".
                    "</tr>";
				}
			}

			$str = $section_html.$link_html;
			$final_section_html = $final_section_html.$str;
		}

		if(!empty($final_section_html)) {
			$html = $header_html.$final_section_html."</table>";
			$html = htmlentities($html);
			$html = base64_encode($html);
		}
			
		return $html;
	}

	function deleteLink($section_id,$link_id,$link_order,$courseHomePageId) {

		if(empty($section_id) || empty($link_id)) {
			// DO NOTHING
		}

		$response_id = $this->_coursepagecmsmodel->deleteLink($section_id,$link_id,$link_order);

		$data = $this->_coursepagecmsmodel->getSectionsDetails("",$courseHomePageId);
		$html = $this->_getSectionHtml($data);

		$response = json_encode(array("COUNTSECTION"=>count($data),"RESPONSE_ID"=>$response_id,"HTML"=>$html,"DBDATA"=>json_encode($data)));
		echo $response;
	}

	function getPreviouslyAddedContent($courseHomePageId) {
                $data = $this->_coursepagecmsmodel->getSlides("",$courseHomePageId);
               
		$html = $this->_getSlidesHtml($data);
		$slideCount = count($data);
		$sectiondata = $this->_coursepagecmsmodel->getSectionsDetails("",$courseHomePageId);
		$sectionhtml = $this->_getSectionHtml($sectiondata);

		$sections = array("COUNTSECTION"=>count($sectiondata),"RESPONSE_ID"=>"","HTML"=>$sectionhtml,"DBDATA"=>json_encode($sectiondata));
		$slides = array("count_sildes"=>count($data),"RESPONSE_ID"=>"","HTML"=>$html,"DBDATA"=>json_encode($data));
		$response = json_encode(array('section'=>$sections,'slides'=>$slides));

		echo $response;
	}
	/**
	 * method called to open edit faq container
	 *
	 * @param none
	 * @return void
	 */
	public function loadFAQContainer() {
		$responseHTML = $this->load->view('coursepages/faqAddedQuestions');
		echo $responseHTML;
	}

	/**
	 * This method gives functionality of reordering widget on page
	 *
	 * @param none
	 * @return void
	 */
	public function reorderCoursepageWidgets() {
            
        $courseCommonLib=$this->load->library('coursepages/CoursePagesCommonLib');
        $data['COURSE_HOME_DICTIONARY']=$courseCommonLib->getCourseHomePageDictionary(0);
		$data['headerContentaarray'] = $this->loadHeaderContent();
		$this->load->view('coursepages/coursepageCmsReorderingWidget',$data);
	}
	
	/*
	 * This function works to show interface for restricted content over pages
	 */
	public function restrictContent(){
        $courseCommonLib=$this->load->library('coursepages/CoursePagesCommonLib');
        $data['COURSE_HOME_DICTIONARY']=$courseCommonLib->getCourseHomePageDictionary(0);
		$data['headerContentaarray'] = $this->loadHeaderContent();
		$this->load->view('coursepages/restrictContentException',$data);
	}
	
	/*
	 * This function works to make entry of restricted content over pages
	 */
	public function saveRestrictContent(){
		$courseHomePageId = $this->input->post('courseHomePageId');
		$contentId = $this->input->post('contentId');
		$contentType = $this->input->post('contentType');
		$flagType = $this->input->post('flagType');
		
		$result = $this->_coursepagecmsmodel->saveRestrictContent($courseHomePageId,$contentId,$contentType,$flagType,$this->_validateuser[0]['userid']);
		if($contentType == 'article'){
			$this->cache->deleteArticlesData($courseHomePageId);
		}elseif($contentType == 'discussion'){
			$this->cache->deleteDiscussionsData($courseHomePageId);
		}elseif($contentType == 'qna'){
			$this->cache->deleteQuestionsData($courseHomePageId);
		}
		
		echo json_encode($result);
	}

	public function getCoursePageWidgetInfoByCategory($courseHomePageId) {

		$widget_list = $this->_coursepagemodel->getWidgetListForCoursePage($courseHomePageId,array("'live'","'history'"));
		$home_tab_url = $this->_coursepagesurlrequest->getHomeTabUrl($courseHomePageId);
		$html = $this->_getCatWidgetListHtml($widget_list,$home_tab_url);

		$response = json_encode(array('HTML'=>$html,'DBDATA'=>json_encode($widget_list)));
		echo $response;
	}

	private function _getCatWidgetListHtml($widget_list,$home_tab_url) {

		$modified_array = array();
		foreach ($widget_list as $widget) {

			if($widget['columnPosition'] == 2) {
				$widget['newdisplayorder'] = 2*$widget['displayorder'];
			} else if($widget['columnPosition'] == 1) {
				$widget['newdisplayorder'] = (2*$widget['displayorder']-1);
			}

			$modified_array[] = $widget;
		}

		unset($widget_list);

		// sort widget based on the new display order scheme
		uasort($modified_array, function ($a,$b) {

			if($a['newdisplayorder'] == $b['newdisplayorder']) {
				return 0;
			}

			return ($a['newdisplayorder'] > $b['newdisplayorder']) ? 1 : -1;
		});

		$html = "";
		$header_html = "<p>Set the Widget Position and click Save <strong style='margin-left:20px; color:green;display:none;'id='success_msg_id'>Saved Successfully</strong></p>".
						"<table border='0' cellpadding='0' cellspacing='0'>".
                        "<tr>".
                          "<th width='5%'>S.No.</th>".
                          "<th width='16%'>Enable / Disable</th>".
                          "<th width='35%'>Title</th>".
                          "<th>Set Position / Sequence</th>".
                      "</tr>";
		//_P($modified_array);exit;
		if(count($modified_array) >0 ) {
			$count = 1;
			foreach ($modified_array as $widget_info) {

				$status = $widget_info['status'];
				if($status == 'live') {
					$checked = "checked";
				} else {
					$checked = "";
				}

				if(in_array($widget_info['widgetKey'],array('FeaturedInstituteWidget'))) {
					$chek_box_val = "style='display:none'";
				} else {
					$chek_box_val = "";
				}
					
				if($count == 1) {
					$widget_last_html = "<td class='widget-last'><span><a style='display:none;' onclick='moveCpgsWidgetUp(this);' href='javascript:void(0);' class='flLt'>Move Up<i class='move-up-icon'></i></a><a onclick='moveCpgsWidgetDown(this);' href='javascript:void(0);' class='flRt'>Move Down<i class='move-dwn-icon'></i></a></span></td>";
				} else if($count == count($modified_array)) {
					$widget_last_html = "<td class='widget-last'><span><a onclick='moveCpgsWidgetUp(this);' href='javascript:void(0);' class='flLt'>Move Up<i class='move-up-icon'></i></a><a style='display:none;' onclick='moveCpgsWidgetDown(this);' href='javascript:void(0);' class='flRt'>Move Down<i class='move-dwn-icon'></i></a></span></td>";
				} else {
					$widget_last_html = "<td class='widget-last'><span><a onclick='moveCpgsWidgetUp(this);' href='javascript:void(0);' class='flLt'>Move Up<i class='move-up-icon'></i></a><a onclick='moveCpgsWidgetDown(this);' href='javascript:void(0);' class='flRt'>Move Down<i class='move-dwn-icon'></i></a></span></td>";
				}

				$html = $html."<tr id='".$widget_info['id']."'>".
                        "<td>".$widget_info['newdisplayorder']."</td>".
                          "<td><input onchange='observeCheckBox(this);'type='checkbox' $checked $chek_box_val/></td>".
                          "<td>".$widget_info['widgetHeading']."</td>".
                           "$widget_last_html".
                      "</tr>";

				$count++;

			}

		}

		if(!empty($html)) {
				
			$html = $header_html.$html."</table>".
			"<input onclick='saveWidgetOrder()' type='submit' value='Save' class='gray-button' style='width:144px;margin-bottom:10px;'/>".
			"<p style='font-size:12px;'><a href="."'".$home_tab_url."'"."> $home_tab_url </a><br/><br/><span>To view widget placement: Please copy the above link and open this in a new window after logging out from your account</span></p>";
			$html = htmlentities($html);
			$html = base64_encode($html);
		}

		return $html;
	}

	public function saveWidgetsOrder($courseHomePageId,$get_data) {

		$get_data = strip_tags(rtrim(base64_decode($get_data),"="));
		$get_array = explode("=", $get_data);

		$request_array = array();
		if(count($get_array)>0) {
			foreach ($get_array as $value) {
				$params = explode("_", $value);
				$request_array[$params[0]] = array('order'=>$params[1],'status'=>$params[2]);
			}
		}

		$this->_coursepagecmsmodel->updateCoursePageWidgetsMapping($request_array);
		$this->cache->deleteWidetList($courseHomePageId);
		echo "Order and status updated successfully";
	}
	
	public function loadAddedFaqData($courseHomePageId,$groupHeadingId)
	{
		//get the list of faq headings(only if page is loaded forst time)
		//if($groupHeadingId == 0)
		//{
			$addedFaqHeadings = $this->_coursepagecmsmodel->getAddedFaqHeadings($courseHomePageId);
		//}
		//else $addedFaqHeadings = 0;
		//get the faqs of $groupHeadingId
		$faqData = $this->_coursepagecmsmodel->getAddedFaqData($courseHomePageId, $groupHeadingId);
		//if no data exists for givemn subcategory
		if($faqData == false) return false;
		$groupHeading = "";
		$addedFaqData = array();
		foreach($faqData as $k=>$faqRow)
		{
			if($groupHeading!=$faqRow['headingId'])
			{
				$addedFaqData[$faqRow['headingId']]=array();
				$groupHeading=$faqRow['headingId'];
			}
		
			array_push($addedFaqData[$faqRow['headingId']],
				   array(   'faqAnswer' => formatArticleTitle(str_replace("&nbsp;", " ", strip_tags($faqRow['faqAnswer'],"<br>")), 80),
										'faqId'=> $faqRow['faqId'],
										'faqPosition'=> $faqRow['faqPosition'],
										'faqQuestion'=> formatArticleTitle(strip_tags($faqRow['faqQuestion']), 80),
										'headingId'=> $faqRow['headingId'],
									        'headingText'=> $faqRow['headingText']
									     )); 
		}
		$faqResult = array(
			'addedFaqHeadings'=> $addedFaqHeadings ,
			'faqData'=> $addedFaqData
		);
		//_p($addedFaqData);
		echo json_encode($faqResult);
		
	}
	
	public function deleteFaqData($courseHomePageId,$faqId)
	{
		$response = $this->_coursepagecmsmodel->deleteSingleFaq($courseHomePageId,$faqId);
		if($response[1]>0){
			$this->cache->deleteFaqWidgetData($courseHomePageId);
			echo json_encode(array($response[0],"The FAQ has been deleted.",$response[2]));
		}
		else{
			echo json_encode(array(0,"Error deleting",0));
		}
		
	}
	public function loadSingleFaqDataForEdit($faqId)
	{
		$response = $this->_coursepagecmsmodel->loadSingleFaq($faqId);
		echo json_encode($response );
	}
	public function loadAllHeadingPositions($courseHomePageId)
	{
		$response = $this->_coursepagecmsmodel->getFaqHeadingsPosition($courseHomePageId);
		echo json_encode($response );
	}

	
	public function updateQuestionClick($question_id = '') {
		
		if(empty($question_id)) {
			return;
		}
		
		$this->_coursepagecmsmodel->updateQuestionClick($question_id);
		echo "success";
	}
	
	public function addCpgsFaqFeedback() {
		
		$question_id = trim($this->input->post('question_id',true));
		$session_id = trim($this->input->post('session_id',true));
		$type_of_feedback = trim($this->input->post('type_of_feedback',true));
		$user_id = trim($this->input->post('user_id',true));
		
		if(empty($question_id) || empty($type_of_feedback)) {
			echo "Something is not right";
			return;
		}
		
		$feedback_id = $this->_coursepagecmsmodel->addCpgsFaqFeedback($question_id,$session_id,$type_of_feedback,$user_id);
		echo $feedback_id;
	}
	
	public function updateCpgsFaqFeedback() {
		
		$question_id = trim($this->input->post('question_id',true));
		$reason_for_no = trim($this->input->post('reson_for_no',true));
		$feedback_id = trim($this->input->post('feedback_id',true));
		
		if(empty($question_id) || empty($reason_for_no) || empty($feedback_id)) {
			echo "Something is not right";
			return;
		}
		
		$this->_coursepagecmsmodel->updateCpgsFaqFeedback($question_id,$reason_for_no,$feedback_id);
		echo "Success";
	}
}
