<?php
/**
 * This class provides web services to student dashboeard client
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class StudentDashboardServer extends MX_Controller
{
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index()
	{

		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('userconfig');

	        $this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('OnlineForms');
        	$args = func_get_args();
		$arrayTest = array();
		$arrayTest[] = $args[1];

		$config['functions']['updateDocumentDetails'] = array('function' => 'StudentDashboardServer.updateDocumentDetails');
		$config['functions']['getDocumentDetails'] = array('function' => 'StudentDashboardServer.getDocumentDetails');
		$config['functions']['insertDocument'] = array('function' => 'StudentDashboardServer.insertDocument');
		$config['functions']['getCourseIdForInstituteId'] = array('function' => 'StudentDashboardServer.getCourseIdForInstituteId');
		$config['functions']['checkDocumentTitle'] = array('function' => 'StudentDashboardServer.checkDocumentTitle');
		$config['functions']['getTheIdsOfInstituteHavingOF'] = array('function' => 'StudentDashboardServer.getTheIdsOfInstituteHavingOF');
		$config['functions']['returnOfInstitutesOfferandOtherDetails'] = array('function' => 'StudentDashboardServer.returnOfInstitutesOfferandOtherDetails');
		$method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function getTheIdsOfInstituteHavingOF($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$original_keyword=trim($parameters['0']);
		$category_id = trim($parameters['1']);
		$department = trim($parameters['2']);
		//$this->userconfig->getDbConfig($appID,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getReadHandle();

		if($dbHandle == ''){
			log_message('error','adduser can not create db handle');
		}
		// parse logic starts for key words
		if(!empty($original_keyword)) {
			$pattern = array('/\\bpresent\\b/i','/\\bfuture\\b/i','/\\bpast\
\b/i','/(\?)/i','/\\bis\\b/i','/\\bdetails\\b/i','/\\bdetail\\b/i','/\
\bprovide\\b/i','/\\bme\\b/i','/\\bdo\\b/i','/\\bwant\\b/i','/\\bi\
\b/i','/\\ba\\b/i','/\\babout\\b/i','/\\babove\\b/i','/\\bacross\
\b/i','/\\bafter\\b/i','/\\bafterwards\\b/i','/\\bagain\\b/i','/\
\bagainst\\b/i','/\\ball\\b/i','/\\balmost\\b/i','/\\balone\\b/i','/\
\balong\\b/i','/\\balready\\b/i','/\\balso\\b/i','/\\balthough\\b/i','/\
\balways\\b/i','/\\bam\\b/i','/\\bamong\\b/i','/\\bamongst\\b/i','/\
\bamoungst\\b/i','/\\bamount\\b/i','/\\ban\\b/i','/\\band\\b/i','/\
\banother\\b/i','/\\bany\\b/i','/\\banyhow\\b/i','/\\banyone\\b/i','/\
\banything\\b/i','/\\banyway\\b/i','/\\banywhere\\b/i','/\\bare\
\b/i','/\\baround\\b/i','/\\bas\\b/i','/\\bat\\b/i','/\\bback\\b/i','/\
\bbe\\b/i','/\\bbecame\\b/i','/\\bbecause\\b/i','/\\bbecome\\b/i','/\
\bbecomes\\b/i','/\\bbecoming\\b/i','/\\bbeen\\b/i','/\\bbefore\
\b/i','/\\bnow\\b/i' ,'/\\bnowhere\\b/i','/\\bof\\b/i','/\\boff\
\b/i','/\\boften\\b/i','/\\bon\\b/i','/\\bonce\\b/i','/\\bonly\\b/i','/\
\bonto\\b/i','/\\bor\\b/i','/\\bother\\b/i','/\\bothers\\b/i','/\
\botherwise\\b/i','/\\bour\\b/i','/\\bours\\b/i','/\\bourselves\
\b/i','/\\bout\\b/i','/\\bover\\b/i','/\\bown\\b/i','/\\bper\\b/i','/\
\bperhaps\\b/i','/\\bplease\\b/i','/\\bput\\b/i','/\\brather\\b/i','/\
\bre\\b/i','/\\bsame\\b/i','/\\bsee\\b/i','/\\bseem\\b/i','/\\bseemed\
\b/i','/\\bseeming\\b/i','/\\bseems\\b/i','/\\bserious\\b/i','/\
\bseveral\\b/i','/\\bshe\\b/i','/\\bshould\\b/i','/\\bshow\\b/i','/\
\bside\\b/i','/\\bsince\\b/i','/\\bsincere\\b/i','/\\bsir\\b/i','/\\bso\
\b/i','/\\bsome\\b/i','/\\bsomehow\\b/i','/\\bsomeone\\b/i','/\
\bsomething\\b/i','/\\bsometime\\b/i','/\\bsometimes\\b/i','/\
\bsomewhere\\b/i','/\\bstill\\b/i','/\\bsuch\\b/i','/\\btake\\b/i','/\
\bthan\\b/i','/\\bthat\\b/i','/\\bthe\\b/i','/\\btheir\\b/i','/\\bthem\
\b/i','/\\bthemselves\\b/i','/\\bthen\\b/i','/\\bthence\\b/i','/\\bthere
\\b/i','/\\bthereafter\\b/i','/\\bthereby\\b/i','/\\btherefore\\b/i','/\
\btherein\\b/i','/\\bthereupon\\b/i','/\\bthese\\b/i','/\\bthey\
\b/i','/\\bthick\\b/i','/\\bthin\\b/i','/\\bthird\\b/i','/\\bthis\
\b/i','/\\bthose\\b/i','/\\bthough\\b/i','/\\bthree\\b/i','/\\bthrough\
\b/i','/\\bthroughout\\b/i','/\\bthru\\b/i','/\\bthus\\b/i','/\\bto\
\b/i','/\\btogether\\b/i','/\\btoo\\b/i','/\\btop\\b/i','/\\btoward\
\b/i','/\\btowards\\b/i',
            '/\\bun\\b/i','/\\bunder\\b/i','/\\buntil\\b/i','/\\bup\
\b/i','/\\bupon\\b/i','/\\bus\\b/i','/\\bvery\\b/i','/\\bvia\\b/i','/\
\bwas\\b/i','/\\bwe\\b/i','/\\bwell\\b/i','/\\bwere\\b/i','/\\bwhat\
\b/i','/\\bwhatever\\b/i','/\\bwhen\\b/i','/\\bwhence\\b/i','/\
\bwhenever\\b/i','/\\bwhere\\b/i','/\\bwhereafter\\b/i','/\\bwhereas\
\b/i','/\\bwhereby\\b/i','/\\bwherein\\b/i','/\\bwhereupon\\b/i','/\
\bwherever\\b/i','/\\bwhether\\b/i','/\\bwhich\\b/i','/\\bwhile\
\b/i','/\\bwhither\\b/i','/\\bwho\\b/i','/\\bwhoever\\b/i','/\\bwhole\
\b/i','/\\bwhom\\b/i','/\\bwhose\\b/i','/\\bwhy\\b/i','/\\bwill\
\b/i','/\\bwith\\b/i','/\\bwithin\\b/i','/\\bwithout\\b/i','/\\bwould\
\b/i','/\\byet\\b/i','/\\byou\\b/i','/\\byour\\b/i','/\\byours\\b/i','/\
\byourself\\b/i','/\\byourselves\\b/i','/\\bof\\b/i','/\\bable\\b/i','/\
\babout\\b/i','/\\babove\\b/i','/\\baccording\\b/i','/\\baccordingly\
\b/i','/\\bacross\\b/i','/\\bactually\\b/i','/\\bafterwards\\b/i','/\
\bagain\\b/i','/\\bagainst\\b/i','/\\bain\\b/i','/\\ball\\b/i','/\
\ballow\\b/i','/\\ballows\\b/i','/\\balmost\\b/i','/\\balone\\b/i','/\
\balong\\b/i','/\\balready\\b/i','/\\balso\\b/i','/\\balthough\\b/i','/\
\balways\\b/i','/\\bamong\\b/i','/\\bamongst\\b/i','/\\banother\
\b/i','/\\bany\\b/i','/\\banybody\\b/i','/\\banyhow\\b/i','/\\banyone\
\b/i','/\\banything\\b/i','/\\banyway\\b/i','/\\banyways\\b/i','/\
\banywhere\\b/i','/\\bapart\\b/i','/\\bappear\\b/i','/\\bappreciate\
\b/i','/\\bappropriate\\b/i','/\\baren\\b/i','/\\baround\\b/i','/\
\baside\\b/i','/\\bask\\b/i','/\\basking\\b/i','/\\bavailable\\b/i','/\
\baway\\b/i','/\\bawfully\\b/i','/\\bbecame\\b/i','/\\bbecause\\b/i','/\
\bbecome\\b/i','/\\bbecomes\\b/i','/\\bbecoming\\b/i','/\\bbeen\
\b/i','/\\bbefore\\b/i','/\\bbeforehand\\b/i','/\\bbehind\\b/i','/\
\bbeing\\b/i','/\\bbelieve\\b/i','/\\bbelow\\b/i','/\\bbeside\\b/i','/\
\bbesides\\b/i','/\\bbest\\b/i','/\\bbetter\\b/i','/\\bbetween\\b/i','/\
\bbeyond\\b/i','/\\bboth\\b/i','/\\bbrief\\b/i','/\\bcame\\b/i','/\\ban\
\b/i','/\\bcannot\\b/i','/\\bcant\\b/i','/\\bcause\\b/i','/\\bcauses\
\b/i','/\\bcertain\\b/i','/\\bcertainly\\b/i','/\\bchanges\\b/i','/\
\bclearly\\b/i','/\\bcome\\b/i','/\\bcomes\\b/i','/\\bconcerning\
\b/i','/\\bconsequently\\b/i','/\\bconsider\\b/i','/\\bconsidering\
\b/i','/\\bcontain\\b/i','/\\bcontaining\\b/i','/\\bcontains\\b/i','/\
\bcorresponding\\b/i','/\\bcould\\b/i','/\\bcouldnot\\b/i','/\\bcourse\
\b/i','/\\bdefinitely\\b/i','/\\bdescribed\\b/i','/\\bdespite\\b/i','/\
\bdid\\b/i','/\\bdidnot\\b/i','/\\bdifferent\\b/i','/\\bdoes\\b/i','/\
\bdoesnot\\b/i','/\\bdoing\\b/i','/\\bdonot\\b/i','/\\bdone\\b/i','/\
\bdown\\b/i','/\\bdownwards\\b/i','/\\bduring\\b/i','/\\beach\\b/i','/\
\beither\\b/i','/\\belse\\b/i','/\\belsewhere\\b/i','/\\benough\
\b/i','/\\bentirely\\b/i','/\\bespecially\\b/i','/\\betc\\b/i','/\\beven
\\b/i','/\\bever\\b/i','/\\bevery\\b/i','/\\beverybody\\b/i',
            '/\\beveryone\\b/i','/\\beverything\\b/i','/\\beverywhere\
\b/i','/\\bexactly\\b/i','/\\bexample\\b/i','/\\bexcept\\b/i','/\\bfar\
\b/i','/\\bfew\\b/i','/\\bfollowed\\b/i','/\\bfollowing\\b/i','/\
\bfollows\\b/i','/\\bformer\\b/i','/\\bformerly\\b/i',
            '/\\bforth\\b/i','/\\bfour\\b/i','/\\bfrom\\b/i','/\
\bfurther\\b/i','/\\bfurthermore\\b/i','/\\bget\\b/i','/\\bgets\
\b/i','/\\bgetting\\b/i','/\\bgiven\\b/i','/\\bgives\\b/i','/\\bgoes\
\b/i','/\\bgoing\\b/i','/\\bgone\\b/i','/\\bgot\\b/i','/\\bgotten\
\b/i','/\\bgreetings\\b/i','/\\bhad\\b/i','/\\bhadnot\\b/i','/\\bhappens
\\b/i','/\\bhardly\\b/i','/\\bhas\\b/i','/\\bhasnot\\b/i','/\\bhave\
\b/i','/\\bhavenot\\b/i','/\\bhaving\\b/i','/\\bhello\\b/i','/\\bhence\
\b/i','/\\bher\\b/i','/\\bhere\\b/i','/\\bhereafter\\b/i','/\\bhereby\
\b/i','/\\bherein\\b/i','/\\bhereupon\\b/i','/\\bhers\\b/i','/\\bherself
\\b/i','/\\bhim\\b/i','/\\bhimself\\b/i','/\\bhis\\b/i','/\\bhither\
\b/i','/\\bhopefully\\b/i','/\\bhow\\b/i','/\\bhowbeit\\b/i','/\
\bhowever\\b/i','/\\bignored\\b/i','/\\bimmediate\\b/i','/\\binasmuch\
\b/i','/\\bindeed\\b/i','/\\bindicate\\b/i','/\\bindicated\\b/i','/\
\bindicates\\b/i','/\\binner\\b/i','/\\binsofar\\b/i','/\\binstead\
\b/i','/\\binto\\b/i','/\\binward\\b/i','/\\bisnot\\b/i','/\\bits\
\b/i','/\\bitself\\b/i','/\\bjust\\b/i','/\\bkeep\\b/i','/\\bkeeps\
\b/i','/\\bkept\\b/i','/\\bknow\\b/i','/\\bknows\\b/i','/\\bknown\
\b/i','/\\blast\\b/i','/\\blately\\b/i','/\\blater\\b/i','/\\blatter\
\b/i','/\\blatterly\\b/i','/\\bleast\\b/i','/\\bless\\b/i','/\\blest\
\b/i','/\\blet\\b/i','/\\blike\\b/i','/\\bliked\\b/i','/\\blikely\
\b/i','/\\blittle\\b/i','/\\blook\\b/i','/\\blooking\\b/i','/\\blooks\
\b/i','/\\bmainly\\b/i','/\\bmany\\b/i','/\\bmay\\b/i','/\\bmaybe\
\b/i','/\\bmean\\b/i','/\\bmeanwhile\\b/i','/\\bmerely\\b/i','/\\bmight\
\b/i','/\\bmore\\b/i','/\\bmoreover\\b/i','/\\bmost\\b/i','/\\bmostly\
\b/i','/\\bmuch\\b/i','/\\bmust\\b/i','/\\bmyself\\b/i','/\\bname\
\b/i','/\\bnamely\\b/i','/\\bnear\\b/i','/\\bnearly\\b/i','/\\bnecessary
\\b/i','/\\bneed\\b/i','/\\bneeds\\b/i','/\\bneither\\b/i','/\\bnever\
\b/i','/\\bnevertheless\\b/i','/\\bnext\\b/i','/\\bnobody\\b/i','/\
\bnone\\b/i','/\\bnoone\\b/i','/\\bnormally\\b/i','/\\bnot\\b/i','/\
\bnothing\\b/i','/\\bnow\\b/i','/\\bnowhere\\b/i','/\\bobviously\
\b/i','/\\boff\\b/i','/\\boften\\b/i','/\\boh\\b/i','/\\bok\\b/i','/\
\bokay\\b/i','/\\bonce\\b/i','/\\bones\\b/i','/\\bonly\\b/i','/\\bonto\
\b/i','/\\bother\\b/i','/\\bothers\\b/i','/\\botherwise\\b/i','/\\bought
\\b/i','/\\bour\\b/i','/\\bours\\b/i','/\\bourselves\\b/i','/\\boutside\
\b/i','/\\boverall\\b/i','/\\bown\\b/i','/\\bparticular\\b/i','/\
\bparticularly\\b/i','/\\bperhaps\\b/i','/\\bplaced\\b/i','/\\bplease\
\b/i','/\\bpossible\\b/i','/\\bpresumably\\b/i','/\\bprobably\\b/i','/\
\bprovides\\b/i','/\\bquite\\b/i','/\\bqv\\b/i','/\\brather\\b/i','/\
\breally\\b/i','/\\breasonably\\b/i','/\\bregarding\\b/i','/\
\bregardless\\b/i','/\\bregards\\b/i','/\\brelatively\\b/i','/\
\brespectively\\b/i','/\\bin\\b/i');
			$count=0;
			$replace =' ';
			$dTxt1 =  trim(preg_replace($pattern, $replace, $original_keyword,
			-1 , $count));
			$keyword =  trim(preg_replace('/(\s)+/', ' ', $dTxt1, -1 ,
$count));
            $keyword = str_replace(array(',',"%"), " ", $keyword);
			$keyword_query = "";
			$institute_keyword_query = "";
			$course_keyword_query = "";
			$institute_like = "";
			$course_like = "";
			// if key word is a comma seperated value then spit it
			if(stripos($keyword,' ')) {
				$keyword_array = split(' ', $keyword);
				$count = count($keyword_array);
				for($i=0;$i<$count;$i++) {
					$keyword_array[$i] = trim(strip_tags(trim(addslashes($keyword_array[$i]))));
					if(!empty($keyword_array[$i]) && $keyword_array[$i]!='') {
					$institute_like .= ($institute_like=='')?'institute_name like '."'%".$keyword_array[$i]."%'":' OR institute_name like '."'%".$keyword_array[$i]."%'";
					$course_like .= ($course_like=='')?'cd.courseTitle like '."'%".$keyword_array[$i]."%'":' OR cd.courseTitle like '."'%".$keyword_array[$i]."%'";
					/*if($i != $count-1) {
						$institute_like = $institute_like." OR ";
						$course_like = $course_like." OR ";
					}*/
				}
				}
				// queries needs to be fired on course and institute tables
				$institute_keyword_query = 'select distinct institute_id from institute where status="live" and '.$institute_like;
				$course_keyword_query = 'select distinct institute_id from course_details cd, OF_InstituteDetails OF where OF.instituteId=cd.institute_id and OF.courseId=cd.course_id and OF.status="live" and cd.status="live" and ('.$course_like.')';
			} else {
				// only one is keyword is there
				$keyword = trim($keyword);
				$keyword = trim(strip_tags(trim(addslashes($keyword))));
				/// queries needs to be fired on course and institute tables
				if(!empty($keyword)) {
				$institute_keyword_query = 'select distinct institute_id from institute where status="live" and institute_name like '."'%".$keyword."%'";
				$course_keyword_query = 'select distinct institute_id from course_details cd, OF_InstituteDetails OF where OF.instituteId=cd.institute_id and OF.courseId=cd.course_id and OF.status="live" and cd.status="live" and courseTitle like '."'%".$keyword."%'";
				}
			}
			// parse result set incase of institute and course
			if(!empty($institute_keyword_query)) {
			$query = $dbHandle->query($institute_keyword_query);
			$results = $query->result();
			if(!empty($results)) {
				foreach ($results as $row){
					$institute_keyword_array[] = $row->institute_id;
				}
			}
			}
			if(!empty($course_keyword_query)) {
			$query = $dbHandle->query($course_keyword_query);
			$results = $query->result();
			if(!empty($results)) {
				foreach ($results as $row){
					$course_keyword_array[] = $row->institute_id;
				}
			}
			}
			// logic to do intersection of ids using keyword on institute and location table
			if(is_array($institute_keyword_array) && is_array($course_keyword_array)) {
				$result_keyword_array = array_merge($institute_keyword_array, $course_keyword_array);
			} else if(is_array($institute_keyword_array)) {
				$result_keyword_array = $institute_keyword_array;
			} else if(is_array($course_keyword_array)) {
				$result_keyword_array = $course_keyword_array;
			}
		}
		if(!empty($category_id)) {
			// remove white spaces
			$category_id = trim($category_id);
			// get all the sub categories of a given category
			$this->load->model('ListingModel');
			$listingmodel_obj = new ListingModel();
			$child_ids = $listingmodel_obj->getChildIds($dbHandle,$category_id);
			$query_to_get_institute_ids = 'SELECT DISTINCT institute_id FROM categoryPageData WHERE status="live" and category_id IN ('.$child_ids.')';
			$query = $dbHandle->query($query_to_get_institute_ids);
			$results = $query->result();
			if(!empty($results)) {
				foreach ($results as $row){
					$category_keyword_array[] = $row->institute_id;
				}
			}
			if(!empty($keyword)) {
				$result_keyword_array = array_intersect($result_keyword_array, $category_keyword_array);
			} else {
				$result_keyword_array = $category_keyword_array;
			}
		}
		$this->load->library('Online_form_client');
		$online_object = new Online_form_client();
		$results = json_decode($online_object->getInstitutesForOnlineHomepage(1,'true',array(),$department));
		// finally intersect with the of institutes having online form
		if(!empty($results) && is_array($results)) {
			$institute_array = $results;
			if(!empty($original_keyword) || !empty($category_id)) {
				$institute_array_results = array_intersect($institute_array, $result_keyword_array);
				error_log(print_r($result_keyword_array,true)."OFresult");
			} else {
				$institute_array_results = $institute_array;
			}
		}
		// send the response back
		return $this->xmlrpc->send_response(json_encode($institute_array_results));
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function returnOfInstitutesOfferandOtherDetails($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$institute_ids = json_decode($parameters['0'],true);
		$department = json_decode($parameters['1'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->returnOfInstitutesOfferandOtherDetails($institute_ids, $department);
		if(!empty($rows) && is_array($rows) && count($rows)!=0) {
			foreach ($rows as $row) {
				$result_array[$row->instituteId] = $row;
			}
		}
		// send the response back
		return $this->xmlrpc->send_response(json_encode($result_array));
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getCourseIdForInstituteId($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$institute_id_array = json_decode($parameters['0'],true);
		$department = json_decode($parameters['1'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->getCourseIdForInstituteId($institute_id_array, $department);
		if(!empty($rows) && is_array($rows) && count($rows)!=0) {
			foreach ($rows as $row) {
				$result_array[$row['instituteId']] = $row['courseId'];
			}
		}
		// send the response back
		return $this->xmlrpc->send_response(json_encode($result_array));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function checkDocumentTitle($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$doc_title = json_decode($parameters['0'],true);
		$user_id = json_decode($parameters['1'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->checkDocumentTitle($doc_title, $user_id);
		if(!empty($rows) && is_array($rows) && count($rows)!=0) {
			foreach ($rows as $row) {
				$result_array[] = $row->count;
			}
		}
		// send the response back
		return $this->xmlrpc->send_response(json_encode($result_array));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function insertDocument($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$document_title = json_decode($parameters['0'],true);
		$document_saved_path = json_decode($parameters['1'],true);
		$instituteId = json_decode($parameters['2'],true);
		$status = json_decode($parameters['3'],true);
		$user_id = json_decode($parameters['4'],true);
		$doc_type = json_decode($parameters['5'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->insertDocument($document_title,$document_saved_path,$instituteId,$status,$user_id,$doc_type);
		// send the response back
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getDocumentDetails($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$type = json_decode($parameters['0'],true);
		$userid = json_decode($parameters['1'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->getDocumentDetails($type,$userid);
		if(!empty($rows) && is_array($rows) && count($rows)!=0) {
			foreach ($rows as $row) {
				$result_array[] = $row;
			}
		}
		// send the response back
		return $this->xmlrpc->send_response(json_encode($result_array));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateDocumentDetails($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$column_name = json_decode($parameters['0'],true);
		$column_value = json_decode($parameters['1'],true);
		$id = json_decode($parameters['2'],true);
		$result_array = array();
		$this->load->model('studentdashboardmodel');
		$model_obj = new StudentDashBoardModel();
		$rows = $model_obj->updateDocumentDetails($column_name,$column_value,$id);
		/*if(!empty($rows) && is_array($rows) && count($rows)!=0) {
			foreach ($rows as $row) {
				$result_array[] = $row;
			}
		}*/
		// send the response back
		return $this->xmlrpc->send_response(json_encode($rows));
	}
}
?>
