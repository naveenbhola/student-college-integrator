<?php
class AnACommonLib {

    private $CI;
    private $validationLibObj;
    private $urlCount = 1;
    private $tmpURLArr = array();

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
        $this->validationLibObj = new APIValidationLib();
        $this->CI->load->helper('image');
    }

    function getUserDetails($userId){
        $userLevel = array();
	$userData  = array();
	if($userId > 0){
	    $this->CI->load->model('messageBoard/AnAModel');
            $userLevel = $this->CI->AnAModel->getOwnerLevel($userId);
            $this->usermodel = $this->CI->load->model('user/usermodel');
            $userData = $this->CI->usermodel->getUserBasicInfoById($userId);
	}
        
        if(empty($userData['avtarimageurl'])){
                    $userData['avtarimageurl'] = NULL;
        }else 
        {
            $userData['avtarimageurl'] = addingDomainNameToUrl(array('url' =>$userData['avtarimageurl'], 'domainName' => MEDIA_SERVER ));   
        }
        /*if(strpos($userData['avtarimageurl'],'https') === false){
                    $userData['avtarimageurl'] = SHIKSHA_HOME.$userData['avtarimageurl'];
        }*/
        if($userData['aboutMe'] == ''){
            $userData['aboutMe'] = NULL;
        }else{
            $userData['aboutMe'] = html_entity_decode($userData['aboutMe']);
        }
        
        return $userDetailsArray = array(
                                                'userId'      => $userData['userid'],
                                                'firstName'   => $userData['firstname'],
                                                'lastName'    => $userData['lastname'],
                                                'picUrl'      => $userData['avtarimageurl'],
                                                'displayName' => $userData['displayname'],
                                                'email'       => $userData['email'],
                                                'level'       => (isset($userLevel['levelName']))?$userLevel['levelName']:'Beginner-Level 1',
                                                'levelId'     => $userLevel['levelId'],
                                                'userpoints'  => $userLevel['userpointvaluebymodule'],
                                                'aboutMe'     => $userData['aboutMe']
        );
    }

    function getTagsRecommendations($userId){
    	$this->CI->load->library('common/personalization/PersonalizationLibrary');
		$this->CI->personalizationlibrary->setUserId($userId);// set userId for this library before accessing any of its functionality.
		$this->CI->personalizationlibrary->setVisitorId(getVisitorId());
		//$personalizatonLibrary = new PersonalizationLibrary($userId);
		$tagRecommendationsArray = $this->CI->personalizationlibrary->getTagRecommendations();
		if(empty($tagRecommendationsArray)){
			return $tagRecommendationsArray;
		}
		$userIdForWhichDetaisToBeFetched = array();
		foreach ($tagRecommendationsArray as $data){
			$userIdForWhichDetaisToBeFetched = array_merge($userIdForWhichDetaisToBeFetched,array($data['usersFromNetwork'][0]));
		}
		$userIdForWhichDetaisToBeFetched = array_unique($userIdForWhichDetaisToBeFetched);
		$userData = array();
		$userIdForWhichDetaisToBeFetched = array_filter($userIdForWhichDetaisToBeFetched);
		if(count($userIdForWhichDetaisToBeFetched) > 0){
			$this->CI->load->model('user/usermodel');
			//$userModel = new UserModel();
			$userData = $this->CI->usermodel->getUsersBasicInfoById($userIdForWhichDetaisToBeFetched);
		}
		$finalRecommendedTagsArray = array();
		foreach ($tagRecommendationsArray as $data){
			$tmp = array();
			$tmp['tagName'] 		= $data['tagName'];
			$tmp['tagId']			= $data['tagId'];
			$tmp['tagFollowers']	= $data['followers'];
			if(array_key_exists($data['usersFromNetwork'][0], $userData)){
				//$tmp['heading'] = 'Followed by '.ucfirst($userData['firstname']).' '.ucfirst($data['lastname']);
				$tmp['heading'] = 'Followed by '.ucfirst($userData[$data['usersFromNetwork'][0]]['firstname']).' '.ucfirst($userData[$data['usersFromNetwork'][0]]['lastname']);
				$networkCount = count($data['usersFromNetwork']);
				if($networkCount > 1){
					//$tmp['heading'] .= ($networkCount - 1).' and other'.(($networkCount > 2)?'s':'');
					$tmp['heading'] .= ' and '.($networkCount - 1).' other'.(($networkCount > 2)?'s':'');
				}
			}elseif ($data['reason'] == 'topTags'){
				$tmp['heading']	= 'Popular tags on shiksha';
			}elseif ($data['reason'] == 'relatedTags'){
				$tmp['heading']	= 'Based on your interest in '.$data['reasonTag'];
			}elseif ($data['reason'] == 'activeTags') {
				$tmp['heading']	= 'Based on your activity';
			}else{
				$tmp['heading']	= 'Based on your activity';
			}
			$finalRecommendedTagsArray[] = $tmp;
		}
		return $finalRecommendedTagsArray;
    }

    function getUserRecommendations($userId){
        $this->CI->load->library('common/personalization/PersonalizationLibrary');
        $this->CI->personalizationlibrary->setUserId($userId);// set userId for this library before accessing any of its functionality.
        $this->CI->personalizationlibrary->setVisitorId(getVisitorId());
        // $personalizatonLibrary = $this->CI->load->library('common/PredisLibrary');
        $userRecommendationsArray = $this->CI->personalizationlibrary->getUserRcommendation();
        if(empty($userRecommendationsArray)){
        	return $userRecommendationsArray;
        }
        $this->CI->load->model('v1/tagsmodel');
        $this->CI->load->model('user/usermodel');
        $this->CI->load->model('messageBoard/anamodel');
        //$userModel				= new UserModel();
        //$tagsmodel              = new tagsmodel();
        //$anamodel				= new AnAModel();
        $predisLibrary			= PredisLibrary::getInstance();//new PredisLibrary();
        //$personalizatonLibrary	= new PersonalizationLibrary($userId);
        
        //_p($userRecommendationsArray);die;
        //die;
        $tagIds = array();
        $userIdsInRecommendation	= array();
        $userIdsForAnaLevel			= array();
        $finalUserRecommendationsArray = array();
        foreach ($userRecommendationsArray as $data){
        	if(in_array($data['reason'],array('userNetwork','facebookFriend'))){
        		$userIdsInRecommendation[]	= intval($data['userId']);
        		if(isset($data['userIds'][0])){
        			$userIdsInRecommendation[]	= intval($data['userIds'][0]);
        		}
        		$userIdsForAnaLevel[]		= intval($data['userId']);
        	}elseif ($data['reason'] == 'topTagContributor'){
        		$userIdsInRecommendation[]	= intval($data['userId']);
        		$userIdsForAnaLevel[]		= intval($data['userId']);
        		$tagIds[] = $data['tagId'];
        	}
        }
        if(!empty($tagIds)){
        	$tagRelatedData = $this->CI->tagsmodel->getTagsDetailsById($tagIds);
        }
        $userIdsInRecommendation 	= array_unique($userIdsInRecommendation);
        $userIdsForAnaLevel			= array_unique($userIdsForAnaLevel);
        //$userDetailData	= $userModel->getUsersBasicInfoById($userIdsInRecommendation);
        $userDetailData	= $this->CI->usermodel->getUsersBasicInfoAndFlagDetails($userIdsInRecommendation);
        $userAnaLevel	= $this->CI->anamodel->getAnAUsersLevel($userIdsForAnaLevel);
        foreach ($userRecommendationsArray as $data){
        	if(isset($userDetailData[$data['userId']]['abused']) && $userDetailData[$data['userId']]['abused'] == '1'){
        		continue;
        	}
        	$temp = array();
        	$temp['userFirstName']	= $userDetailData[$data['userId']]['firstname'];
        	$temp['userLastName']	= (isset($userDetailData[$data['userId']]['lastname']))? $userDetailData[$data['userId']]['lastname'] : '';
        	$temp['userId']			= $data['userId'];
        	if(empty($userDetailData[$data['userId']]['avtarimageurl'])){
        		$temp['picUrl'] = NULL;
        	}/*elseif(strpos($userDetailData[$data['userId']]['avtarimageurl'],'http') === false){
        		$temp['picUrl'] = SHIKSHA_HOME.$userDetailData[$data['userId']]['avtarimageurl'];
        	}*/else{
        	//	$temp['picUrl']			= $userDetailData[$data['userId']]['avtarimageurl'];
                $temp['picUrl'] = addingDomainNameToUrl(array('url' =>$userDetailData[$data['userId']]['avtarimageurl'], 'domainName' => MEDIA_SERVER ));   
        	}
        	if($data['reason'] == 'userNetwork'){
        		$temp['heading']			= ucfirst($userDetailData[$data['userIds'][0]]['firstname']);
        		if(count($data['userIds']) == 1){
        			$temp['heading']	.=	" is following";
        		}elseif (count($data['userIds']) == 2){
        			$temp['heading']	.=	" and 1 other is following";
        		}elseif (count($data['userIds']) > 2){
        			$temp['heading']	.=	" and ".(count($data['userIds']) - 1)." others are following";
        		}else{
        			$temp['heading'] = "";
        		}
        	}elseif ($data['reason'] == 'facebookFriend'){
        		$temp['heading']	= "Your facebook friend";
        	}elseif ($data['reason'] == 'topTagContributor'){
        		$temp['heading']			= "Top contributor on ".ucfirst($tagRelatedData[$data['tagId']]['tags']);
        	}
        	$temp['userFollowers']	= count($predisLibrary->getMembersOfSet('userFollowers:user:'.$data['userId']));
        	$temp['userLevel']		= (isset($userAnaLevel[$data['userId']]))?$userAnaLevel[$data['userId']]:'Beginner-Level 1';
        	$finalUserRecommendationsArray[] = $temp;
        }
        
        //_p($finalUserRecommendationsArray);die;
        return  $finalUserRecommendationsArray;
    }

    function getHomepage($userId, $start, $count, $filter){
	$dummyEntries = array(
			array(
				'type'		=> 'Q',
				'uniqueId'	=> 1,
				'id'		=> '1146399',
				'title'		=> 'What are the job prospects after completing L.L.M. in corporate law ?',
				'activityTime'  => '3 Hrs ago',
				'answerCount'	=> '2',
				'followerCount'	=> '20',
				'URL'		=> 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-1146399',
				'heading'	=> 'upvoted this',
				'headingUsername' => 'Anil',
				'headingUserId' => '123',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
				'answerText'	=> 'Hi, Corporate law in India has become very attractive. Areas such as international finance and project finance are very rewarding. More and more of law graduates today are opting for corporate law. Once you complete your LLM, you should join a Law firm or the legal department of a large corporate house or an MNC or Investment Banks, government organisations or NGOs. Here, as a trainee solicitor, your focus will be more towards research in law and case preparation in the first few years. As a corporate lawyer your role will be to advise the company about its legal rights and duties in relation to its client companies, partner companies and draw up legal drafts for their partnerships. Once you complete your LLM in Corporate law, you will not only find opportunities with law firms but also with many major corporations and financial institutions. Most Indian law firms are bringing themselves in line with the best that the west has to offer in terms of infrastructure and technology. There is more competition these days and that has helped upgrade the quality of practice of law in India. Another affiliated field is the Legal Process Outsourcing which is an emerging business in India and is expected to grow fast in the next ten years. Private corporate and consultancies offer a better pay package than the ones in the government sector. With foreign law firms entering  India, salaries have shot up and corporate law has become a lucrative career option.',
				'answerOwnerName'  => 'Poshika Singh',
				'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
				'answerOwnerLevel' => 'Beginner-Level1 | Professor, Corporate law @NLSIU',
				'answerOwnerUserId'=> '567',
				'tags' => array(
							array('tagId'=>'425','tagName'=>'LLM'),
							array('tagId'=>'131','tagName'=>'Corporate Law'),
						),
				'viewCount'	=> '300'
			),
			array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2740412',
                                'title'         => 'Will I be able to practice law in India if I pursue law from Singapore? ',
                                'activityTime'  => '20 min ago',
                                'answerCount'   => '1',
                                'followerCount' => '40',
                                'URL'           => 'http://ask.shiksha.com/which-are-the-good-colleges-in-delhi-to-pursue-pgdm-qna-2740412',
                                'heading'       => 'following this',
                                'headingUsername' => 'Padmaja',
                                'headingUserId' => '1234',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
				'answerText'    => 'The Bar Council of India recognizes degrees from certain colleges outside India. For a full list of these degrees, and the relevant notification, click on the link below-

http://www.barcouncilofindia.org/wp-content/uploads/2010/05/List-of-Foreign-Universities-May-2012.pdf 

There are, therefore, two different methods of registration for students who complete their LLB degrees from outside India:

For students who complete their LLB degrees from recognised universities:
These students are required to apply for registration to their respective State Bar Councils upon their return to India. The State Bar Councils then forward their applications to the Bar Council of India, which conducts an exam twice a year, on six subjects. Successful candidates are permitted to practice in India.

For students who complete their LLB degrees from non-recognised universities:
These students are required to apply directly to the Bar Council of India for registration. These applications are considered on a case-to case basis, and, upon approval, these students may be allowed to practice in India.',
                                'answerOwnerName'  => 'Poshika Singh',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => ' Guide-Level12 | LLM student, Aspiring legal eagle',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'15','tagName'=>'Law'),
                                                        array('tagId'=>'188274','tagName'=>'Singapore'),
                                                ),
                                'viewCount'     => '100'
			),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2633076',
                                'title'         => 'What is the scope of B.Com Honors?',
                                'activityTime'  => '2 Hrs ago',
                                'answerCount'   => '3',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/which-are-the-good-colleges-to-pursue-pgdft-course-through-distance-qna-2633076',
                                'heading'       => 'answered this',
                                'headingUsername' => 'Indu Prasad',
                                'headingUserId' => '14234',
                                'likeCount'     => '45',
                                'dislikeCount'  => '567',
				'answerText'    => 'B.com (H) is a good course to pursue and it offers good career prospects and excellent growth opportunities.

As a B.COM graduate, you may look for careers in sectors such as-

BPO:

- Financial Services
- Marketing
- Broking
- Banking
- Insurance
- ITES
- FMCG

Profiles that you can look for-

- Company Law Assistant
- Relationship manager
- Assistant Manager/Consultant
- Articled Trainee
- Sales Officer Accounts

You have the option of going for higher education. M.Com, MBA, LLB, CA are some of those courses.',
                                'answerOwnerName'  => 'Neha Aggarwal',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => 'Contributor-Level6 | Biomedical researcher at  IISc ',
                                'answerOwnerUserId'=> '24545',
                                'tags' => array(
                                                        array('tagId'=>'391','tagName'=>'B.Com'),
                                                ),
                                'viewCount'     => '1450'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2560997',
                                'title'         => 'What is the scope of MA English after completing B.Com?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-2560997',
                                'heading'       => 'upvoted this',
                                'headingUsername' => 'Anil',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
				'answerText'    => 'The ideal options after B.com are-

M.COM
CA/CS or ICWA
LLB
MBA 

You should stick by your domain in order to have an added advantage in the competitive market. At the same time, it is very important that you opt for or look at doing something that you are good at and would enjoy keeping in mind your personality, aptitude, ability, strengths and career goals.

If at all, you want to switch over, go ahead and pursue a career with English.

M.A English is a fairly flexible course and offers various diverse careers such as-

1. Management careers - Marketing, Human Resources etc

2. Hospitality, Event Management, Public Relations, Corporate Communications

3. Media careers like Advertising, Journalism and Film Making 

4. Academic careers like Teaching and Research and particularly Training. 
English Language Training is a big and growing sector in India with jobs freelance and full-time options as an English language trainer or a voice and accent trainer. 

5. You could also do a B.Ed, or MBA or take up some of the Professional courses. 

6. Studying a foreign language full time can be an excellent career option. Getting an advanced degree (Master’s / PhD) in studying foreign languages can have an exciting value proposition.
',
                                'answerOwnerName'  => 'Yamini Bisht',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => ' Guide-Level14 | Linguistics lecturer, MCC Chennai',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'391','tagName'=>'B Com'),
                                                        array('tagId'=>'411','tagName'=>'M.A'),
                                                ),
                                'viewCount'     => '300'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '1256433',
                                'title'         => 'Can I study and settle in New Zealand after completing  B.Com with 2 to 3 years of work experience in Banking sector?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-1256433',
                                'heading'       => 'upvoted this',
                                'headingUsername' => 'Anil',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'Hi, you may surely study and settle down in countries like New Zealand. To start with, you can be granted permission to work for upto 15 hours a week while pursuing the MBA. If you have a student permit, you may be granted a work permit or a variation of your student permit to allow you to gain practical experience on completion of a three-year course, or over the summer vacation. The New Zealand rules allow you to take up work after completion of atleast 3 years of full time education. But most students who can get a job after completion of their MBA, do manage to get a permit for work there. The New Zealand job market for MBAs is not really flourishing. Its a small country with a small population - jobs can be limited at the management positions. But if you are smart, and can manage a job with your abilities, it can be a gateway to the rest of the world. Most people work in New Zealand to later move on to the western countries where it would be very difficult to find work in when applying directly from India. A few universities in New Zealand you could pursue your MBA from are: University of AucklandVictoria University of WellingtonMassey UniversityUniversity of CanterburyUniversity of OtagoUniversity of WaikatoYou may check with the universities for the individual entry requirements. However there are a lot of better options in India too',
                                'answerOwnerName'  => 'Poshika Singh',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => 'Contributor-Level9 |  Linguistics lecturer, MCC Chennai',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'188271','tagName'=>'New Zealand'),
                                                        array('tagId'=>'391','tagName'=>'B Com'),
                                                ),
                                'viewCount'     => '670'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2470276',
                                'title'         => 'Which course is better BA in English or BA in hospitality management?',
                                'activityTime'  => '2 Days ago',
                                'answerCount'   => '2',
                                'followerCount' => '789',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-2470276',
                                'heading'       => 'answered this',
                                'headingUsername' => 'Ankur',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'BA in English will help you with future jobs in regular offices, schools, industries in admin departments whereas BA in Hospitality will help you in Travel, Tourism, Hotels and Event Management jobs. After BA you can do MA or MBA is any stream or Hospitality Management',
                                'answerOwnerName'  => 'Ankur',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => '  Beginner-Level4 |  LLM student, Aspiring legal eagle',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'410','tagName'=>'B.A'), 
                                                ),
                                'viewCount'     => '345'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '1202177',
                                'title'         => 'Is it possible to pursue MBA in Software after completing BA?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-1202177',
                                'heading'       => 'upvoted this',
                                'headingUsername' => 'Anil',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'Hello, in short, yes you can do your MBA after BA. It is best to have some industry experience after your graduation to get into a good MBA school. Most of the reputed institutes also need you to give some kind of entrance exam like CAT/GMAT.',
                                'answerOwnerName'  => 'Poshika Singh',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => 'Contributor-Level6 | CA, Pune',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'599559','tagName'=>'MBA'),
                                                        array('tagId'=>'410','tagName'=>'B.A'), 
                                                ),
                                'viewCount'     => '300'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '1484323',
                                'title'         => 'Which course is better: B.Sc. CS or B.Sc. IT?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-1484323',
                                'heading'       => 'followed this',
                                'headingUsername' => 'Yamini',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'I may describe it as a hierarchy, IT is a part of Computer Science , which deals with the study of the various technologies that are in use. Computer Science is the main root which more defines the significance of the same in the industry. 

Computer Science is a core field of study in which you can also pursue Research as well where as IT is the derived field which provides education regarding the technologies that have been already introduced and implemented.

Both are good and choosing among the two will depend on your interest for the technologies or the study of the core and research. The best thing to advise you is not get confused and plainly decide on the basis of your interests.',
                                'answerOwnerName'  => 'Neha Jain',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => '  Contributor-Level9 | Studying statistics & more @ TIFR',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'572468','tagName'=>'BSc'),
                                                        array('tagId'=>'399','tagName'=>'CS'),
                                                         array('tagId'=>'266','tagName'=>'Information Technology'),
							
                                                ),
                                'viewCount'     => '456'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '1208085',
                                'title'         => 'What are the career prospects after BSc?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-1208085',
                                'heading'       => 'upvoted this',
                                'headingUsername' => 'Anil',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'Hi, you have not mentioned your subject in B.SC. However with a B Sc degree under your belt you could consider options ranging from Biotechnology, Pharmaceutical research and IT to Management, Media, Academics, Social Work and Law, to name a few. Any of the choices listed above will require a post graduate degree in that area like a M Sc for Biotech, an MBA for Management or a Mass Comm. degree for Media. However, we recommend that you work for a couple of years before jumping into a post graduation. Some work experience will help you know your strengths and areas of interest better so you will make a more informed choice for your post graduate studies, if later you decide to go for one. Work experience will also help you perform better in admission interviews for post graduate institutes in any area. Also, we recommend that you take the time to explore your deepest talents, interests and career goals before you decide on what the next step should be. However, doing some additional courses in latest technologies adds value to your resume and profile and provides an edge over your competitors at the time of recruitment. Therefore it is advisable to go for some short-term fast track courses.',
                                'answerOwnerName'  => 'Poshika Singh',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => 'Guide-Level12 | LLM student, Aspiring legal eagle',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'572468','tagName'=>'Bsc'),
                                                        array('tagId'=>'2','tagName'=>'Career'),
                                                ),
                                'viewCount'     => '300'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2192271',
                                'title'         => 'Is it possible to pursue BA in journalism through online mode?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '2',
                                'followerCount' => '20',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-chances-that-csab-announces-a-spot-round-counselling-this-year-qna-2192271',
                                'heading'       => 'upvoted this',
                                'headingUsername' => 'Anil',
                                'headingUserId' => '123',
                                'likeCount'     => '110',
                                'dislikeCount'  => '30',
                                'answerText'    => 'There are not colleges in India that offer B.A Journalism. However, I can suggest you-

1. Professional Diploma in Medical Journalism- James Lind Institute

2. Training course in Science Journalism- Indian Science Communication Society

Also you may check out the training options offered by the Online Centre for Media Studies.

You can even look for B.A Journalism that I.G.N.O.U offers.',
                                'answerOwnerName'  => 'Poshika Singh',
                                'answerOwnerImage' => 'http://ask.shiksha.com/public/images/girlav4_t.gif',
                                'answerOwnerLevel' => 'Contributor-Level9 | Linguistics lecturer, MCC Chennai',
                                'answerOwnerUserId'=> '567',
                                'tags' => array(
                                                        array('tagId'=>'117','tagName'=>'Journalism'),
                                                        array('tagId'=>'410','tagName'=>'B.A'),
                                                ),
                                'viewCount'     => '300'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '3232268',
                                'title'         => 'Can I appear for 2nd or 3rd grade RPSC examinations after completing B.Com and B.Ed?',
                                'activityTime'  => '3 Hrs ago',
                                'answerCount'   => '0',
                                'followerCount' => '0',
                                'URL'           => 'http://ask.shiksha.com/Can-I-appear-for-2nd-or-3rd-grade-RPSC-examinations-after-completing-B.Com-and-B.Ed-qna-3232268',
                                'heading'       => 'followed this',
                                'headingUsername' => 'Manish',
                                'headingUserId' => '8',
                                'likeCount'     => '10',
                                'dislikeCount'  => '5',
                                'answerText'    => 'I have completed my graduate this year.Can I appear for 2nd or 3rd grade RPSC examinations after completing B.Com and B.Ed?',
                                'answerOwnerName'  => 'Manish Verma',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/men1.gif',
                                'answerOwnerLevel' => 'Beginner-Level 1 | CA, Pune',
                                'answerOwnerUserId'=> '8',
                                'tags' => array(
                                                        array('tagId'=>'391','tagName'=>'B.Com'),
                                                        array('tagId'=>'572549','tagName'=>'BEd'),
                                                ),
                                'viewCount'     => '3'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '3232270',
                                'title'         => 'What are the career options available in Zoology after BSc?',
                                'activityTime'  => '2 Hrs ago',
                                'answerCount'   => '0',
                                'followerCount' => '0',
                                'URL'           => 'http://ask.shiksha.com/What-are-the-career-options-available-in-Zoology-after-BSc-qna-3232270',
                                'heading'       => 'followed this',
                                'headingUsername' => 'Nishtha',
                                'headingUserId' => '2',
                                'likeCount'     => '0',
                                'dislikeCount'  => '0',
                                'answerText'    => 'Sa asf question',
                                'answerOwnerName'  => 'nishtha gulati',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav6.gif',
                                'answerOwnerLevel' => 'Beginner-Level 4',
                                'answerOwnerUserId'=> '2',
                                'tags' => array(
                                                        array('tagId'=>'572468','tagName'=>'BSc'),
                                                        array('tagId'=>'166','tagName'=>'Zoology'),
                                                ),
                                'viewCount'     => '1'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '2169117',
                                'title'         => 'What after B.com – Job or higher studies?',
                                'activityTime'  => '2 days ago',
                                'answerCount'   => '175',
                                'followerCount' => '200',
                                'URL'           => 'http://ask.shiksha.com/What-after-B.com-Job-or-higher-studies-dscns-2169117',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'aru',
                                'headingUserId' => '1544835',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'answerText'    => 'B.Com. is one of the most famous degree of graduation in India and comes with a wide variety of options for higher studies. The degree is the base of various fields such as Finance, Marketing, Management, Commerce, etc. In many cases the degree is considered enough to start working immediately. But in today’s competitive environment is it a good move? Should the students go for higher studies after B.com or straight away jump into the job world? While a job can give you experience, higher studies may give you competitive advantage over your peers. Share your thoughts here.',
                                'answerOwnerName'  => 'aru chopra',
                                'answerOwnerLevel' => 'Guide-Level 11 | Studying statistics & more  @ TIFR',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav6.gif',
                                'answerOwnerUserId'=> '1544835',
                                'tags' => array(
                                                        array('tagId'=>'391','tagName'=>'B.Com'),
                                                ),
                                'viewCount'     => '0'
                        )
                        /*array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '1624186',
                                'title'         => 'what will be good to do after doing 12th in commerce stream?',
                                'activityTime'  => '2 days ago',
                                'answerCount'   => '175',
                                'followerCount' => '200',
                                'URL'           => 'http://ask.shiksha.com/what-will-be-good-to-do-after-doing-12th-in-commerce stream-dscns-1624186',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'pooja shrivas',
                                'headingUserId' => '1144040',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'answerText'    => 'suggest me the courses that can i do in my city gwalior.',
                                'answerOwnerName'  => 'pooja shrivas',
                                'answerOwnerLevel' => 'Beginner-Level 1 | Studying Bcom',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav6.gif',
                                'answerOwnerUserId'=> '1144040',
                                'tags' => array(
                                                        array('tagId'=>'391','tagName'=>'B.Com'),
                                                        array('tagId'=>'190433','tagName'=>'class 12'),
                                                        
                                                ),
                                'viewCount'     => '322'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '2894956',
                                'title'         => 'How to choose the right specialization to build a career in Law?',
                                'activityTime'  => '2 days ago',
                                'answerCount'   => '27',
                                'followerCount' => '200',
                                'URL'           => 'http://ask.shiksha.com/How-to-choose-the-right-specialization-to-build-a-career-in-Law-dscns-2894956',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'Neda9412',
                                'headingUserId' => '1144040',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'answerText'    => 'Aspiring for a career in Law? CLAT 2014 is around the corner, 11th May, 2014.
Law offers various specialization including Corporate Law, Intellectual Property Law, Criminal Law, Taxation Law, Business Law etc.

Students need to know what it takes to pursue the course and the prospects offered to be able to choose the right career.

Please share your thoughts and information on the best career specialization that boost a lucrative career in Law.',
                                'answerOwnerName'  => 'Neda9412',
                                'answerOwnerLevel' => 'Beginner-Level 4',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav6.gif',
                                'answerOwnerUserId'=> '1144040',
                                'tags' => array(
                                                        array('tagId'=>'15','tagName'=>'Law'),
                                                        array('tagId'=>'2','tagName'=>'Career'),
                                                ),
                                'viewCount'     => '500'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '2062849',
                                'title'         => 'What are the various career options for finance students?',
                                'activityTime'  => '2 days ago',
                                'answerCount'   => '79',
                                'followerCount' => '200',
                                'URL'           => 'http://ask.shiksha.com/What-are-the-various-career-options-for-finance-students-dscns-2062849',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'NSJ',
                                'headingUserId' => '1790041',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'answerText'    => 'The money it brings is huge, a career in finance could very fulfilling too as it opens your way to many industries and many types of jobs including Financial advisors, bankers and many more. What could be the possible career options in finance? Are their other avenues apart from banks? What can be the possible degree combinations to build a career in Finance? Share your thoughts.',
                                'answerOwnerName'  => 'NSJ',
                                'answerOwnerLevel' => 'Contributor-Level 9',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav4.gif',
                                'answerOwnerUserId'=> '1790041',
                                'tags' => array(
                                                        array('tagId'=>'524732','tagName'=>'Finance Department Scope'),
                                                        array('tagId'=>'391','tagName'=>'B.Com'),
                                                ),
                                'viewCount'     => '25'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '1898047',
                                'title'         => 'What is better : CA or CS',
                                'activityTime'  => '2 days ago',
                                'answerCount'   => '191',
                                'followerCount' => '200',
                                'URL'           => 'http://ask.shiksha.com/What-is-better-CA-or-CS-dscns-1898047',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'aru.chopra',
                                'headingUserId' => '1544835',
				'likeCount'	=> '200',
				'dislikeCount'  => '100',
                                'answerText'    => 'Chartered Accountancy (CA) and Company Secretary (CS) are two most popular courses for Commerce students. Which one do you think offers better Job prospects to students?',
                                'answerOwnerName'  => 'aru chopra',
                                'answerOwnerLevel' => 'Guide-Level 11 | Biomedical researcher at  IISc',
                                'answerOwnerImage' => 'http://images.shiksha.com/public/images/girlav6.gif',
                                'answerOwnerUserId'=> '1544835',
                                'tags' => array(
                                                        array('tagId'=>'398','tagName'=>'CA'),
                                                        array('tagId'=>'399','tagName'=>'CS'),
                                                ),
                                'viewCount'     => '100'
                        )
                        /*array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '13422',
                                'title'         => '[Cutoffs] Top MBA colleges accepting NMAT SNAP and IIFT scores. ',
                                'activityTime'  => '1 week ago',
                                'answerCount'   => '0',
                                'followerCount' => '12',
                                'URL'           => 'http://ask.shiksha.com/top-mba-colleges-accepting-nmat-snap-and-iift-scores-dscns-2748317',
                                'heading'       => 'New Discussion under MBA',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'tags' => array(
                                                        array('tagId'=>'10','tagName'=>'NMAT'),
                                                        array('tagId'=>'20','tagName'=>'SNAP'),
                                                        array('tagId'=>'20','tagName'=>'IIFT'),
                                                        array('tagId'=>'20','tagName'=>'MBA Exams'),
							array('tagId'=>'20','tagName'=>'Top MBA Colleges')
                                                ),
                                'viewCount'     => '678'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2723214',
                                'title'         => 'What are the career opportunities after M.Sc Environmental Science?',
                                'activityTime'  => '1 year ago',
                                'answerCount'   => '0',
                                'followerCount' => '400',
                                'URL'           => 'http://ask.shiksha.com/what-are-the-career-opportunities-after-m-sc-environmental-science-qna-2723214',
                                'heading'       => 'posted this',
                                'headingUsername' => 'Gaurav',
                                'headingUserId' => '1234',
                                'tags' => array(
                                                        array('tagId'=>'30','tagName'=>'M.Sc Environmental Science'),
                                                        array('tagId'=>'40','tagName'=>'Career'),
                                                ),
                                'viewCount'     => '100'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2722772',
                                'title'         => 'Which is the better among banking and CS?',
                                'activityTime'  => '1 year ago',
                                'answerCount'   => '0',
                                'followerCount' => '34',
                                'URL'           => 'http://ask.shiksha.com/which-is-the-better-among-banking-and-cs-qna-2722772',
                                'heading'       => 'posted this',
                                'headingUsername' => 'Shanaya',
                                'headingUserId' => '1234',
                                'tags' => array(
                                                        array('tagId'=>'30','tagName'=>'Banking'),
                                                        array('tagId'=>'40','tagName'=>'CS'),
                                                ),
                                'viewCount'     => '100'
                        ),
                        array(
                                'type'          => 'Q',
                                'uniqueId'      => 1,
                                'id'            => '2722645',
                                'title'         => 'Which are the best institutes in Mumbai to pursue Hotel Management course?',
                                'activityTime'  => '1 year ago',
                                'answerCount'   => '0',
                                'followerCount' => '674',
                                'URL'           => 'http://ask.shiksha.com/which-are-the-best-institutes-in-mumbai-to-pursue-hotel-management-course-qna-2722645',
                                'heading'       => 'following this',
                                'headingUsername' => 'Ankur',
                                'headingUserId' => '1234',
                                'tags' => array(
                                                        array('tagId'=>'30','tagName'=>'Best Colleges in Mumbai'),
                                                        array('tagId'=>'40','tagName'=>'Hotel Management'),
                                                ),
                                'viewCount'     => '100'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '1911160',
                                'title'         => 'Should you study journalism after Class 12? A perspective',
                                'activityTime'  => '2 years ago',
                                'answerCount'   => '0',
                                'followerCount' => '98',
                                'URL'           => 'http://ask.shiksha.com/should-you-study-journalism-after-class-12-a-perspective-dscns-1911160',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'heading'       => 'New Discussion under Journalism',
                                'tags' => array(
                                                        array('tagId'=>'10','tagName'=>'Journalism'),
                                                        array('tagId'=>'20','tagName'=>'After 12')
                                                ),
                                'viewCount'     => '1678'
                        ),
                        array(
                                'type'          => 'D',
                                'uniqueId'      => 2,
                                'id'            => '2670087',
                                'title'         => '[Country Connect] Top Reasons to Study in Newzealand?',
                                'activityTime'  => '1 week ago',
                                'answerCount'   => '53',
                                'followerCount' => '111',
                                'URL'           => 'http://ask.shiksha.com/top-reasons-to-study-in-newzealand-dscns-2670087',
                                'heading'       => 'commented on this',
                                'headingUsername' => 'Romil',
                                'headingUserId' => '134324',
				'likeCount'	=> '110',
				'dislikeCount'  => '30',
                                'answerText'    => 'I have scored CGPA 10/10 in CBSE 10th and 9/10 in 11th and am expected to do well +90% in 12th too PCM. I am a national level sailor, having represented Madhya Pradesh twice in national sailing competitions. I am also winner of international Horlicks wiz kid competition from MadhyaPradesh and made it to top 10 positions in the international competition. I have participated and won a number of debate and quiz cometitions at inter- school level. I intend studying Mass Media or graduate in Arts majoring in Psychology, abroad provided i get a 80-100% scholarship. Can you please guide me ?',
                                'answerOwnerName'  => 'Romil Goel',
                                'answerOwnerImage' => 'http://images.shiksha.com/mediadata/images/1427873034phpV7N9xX_s.jpeg',
                                'answerOwnerLevel' => 'Chief Advisor',
                                'answerOwnerUserId'=> '5672',
                                'tags' => array(
                                                        array('tagId'=>'10','tagName'=>'Study in New Zealand')
                                                ),
                                'viewCount'     => '6785'
                        )*/
		);		
	//$dummyEntries2 = $dummyEntries;
	//return array_merge($dummyEntries,$dummyEntries2);

	if($filter=="discussion" || $filter == 'unanswered'){
		$returnArray = array();
		foreach ($dummyEntries as $entry){
			if( ($entry['type']=='D' && $filter=="discussion") || ($entry['type']=='Q' && $filter == 'unanswered' && $entry['answerCount']==0) ){
				$returnArray[] = $entry;
			}
		}
		$j = 0;
		for ($i = 0; $i < 10; $i++){
			if(isset($returnArray[$i])){
				continue;
			}
			$returnArray[$i] = $returnArray[$j];
			$j++;
		}
		return array_filter($returnArray);
	}

	return array_filter($dummyEntries);
    }


    function getSimilarQuestions($entityText, $count){
	$questionArray = array();

        $this->CI->load->library("search/Discussionsearchcontent");
        $searchResults = $this->CI->discussionsearchcontent->getQuestionsByText($entityText,$count);

        if(isset($searchResults['content']) && is_array($searchResults['content'])){
                 $questionObjArr = $searchResults['content'];
                 $i = 0;
                 global $isMobileApp;
                 foreach($questionObjArr as $questionObj)
                 {
                         $tmp = array();
                         $tmp['questionTitle'] =  strip_tags(preg_replace('/(Ask Questions on various .*)/','',$questionObj->getTitle()));
                         $tmp['answerCount'] = $questionObj->getAnswerCount();
                         if($isMobileApp && empty($tmp['answerCount'])){
                                $tmp['answerCount'] = "0";
                         }
                         $tmp['questionId'] = $questionObj->getId();
                         if($isMobileApp){
                            $tmp['questionTitle'] = sanitizeAnAMessageText($tmp['questionTitle']);
                         }
                         $tmp['creationDate'] = date('Y-m-d',strtotime($questionObj->getCreatedTime()));
                         $questionArray[$i] = $tmp;
                         if($i==($count-1)){      //Show only 6 similar questions
                                  break;
                         }
                         $i++;
                  }
        }
	return $questionArray;
    }

    function getCountryFromTags($tagArray){
	//Check if any of the Tags/its Parents/its Grand-Parent is a Country
        //Create a String of all Tags
        $tagIdArray = "";
        foreach ($tagArray as $tags){
              $tagIdArray[] = $tags['tagId'];
        }
        $this->CI->load->model('Tagging/taggingmodel');
        $this->tagmodel = new TaggingModel();
	$country = $this->tagmodel->checkTagForEntity($tagIdArray, 'Country');
	if(is_array($country) && isset($country['tagName'])){
		$countryName = $country['tagName'];

		//Get Country Id from Country Name
                $this->CI->load->library('user/Register_client');
                $register_client = new Register_client();
		$countryArray = $register_client->getIdForCountryName($countryName);
		if(isset($countryArray[0]['countryId'])){
			return $countryArray[0]['countryId'];
		}

		return $countryId = "2";
	}
	else{
		return "2";
	}
	
    }

    function getCategoryFromTags($tagArray){
        //Check if any of the Tags/its Parents/its Grand-Parent is a Category
        //Create a String of all Tags
        $tagIdArray = "";
        foreach ($tagArray as $tags){
              $tagIdArray[] = $tags['tagId'];
        }
        $this->CI->load->model('Tagging/taggingmodel');
        $this->tagmodel = new TaggingModel();
        $country = $this->tagmodel->checkTagForEntity($tagIdArray, 'Stream');
        if(is_array($country) && isset($country['tagName'])){
                $categoryName = $country['tagName'];

                //Get Category Id from Category Name
                $this->CI->load->config('anaConfig',TRUE);
                $streamMappingArray = $this->CI->config->item('streamMapping','anaConfig');
		if(isset($streamMappingArray[$categoryName])){
			return $streamMappingArray[$categoryName]['id'];
		}

                return $categoryId = "1";
        }
        else{
                return "1";
        }

    }

    function trackEditOperation($editEntityId, $entityType, $userId, $automoderationFlag=0){
        $this->CI->load->model('messageBoard/AnAModel');
        $this->CI->AnAModel->trackEditOperation($editEntityId, $entityType, $userId, $automoderationFlag);
	return true;
    }

    
    function formatQuestionDetailPageData($userId,$questionId,$start,$count,$sortOrder,$referenceAnswerId){
        
        $this->CI->load->model('messageBoard/AnAModel');
         
         $quesDetailPageData = $this->CI->AnAModel->getQuestionDetailPageData($userId,$questionId,$start,$count,$sortOrder,$referenceAnswerId);
        
         if(!empty($quesDetailPageData['entityDetails'])){
            
                $quesDetailPageData['entityDetails']['description'] = strip_tags(html_entity_decode($quesDetailPageData['entityDetails']['description']));
                                     
                if($quesDetailPageData['entityDetails']['hasUserReportedAbuse'] == 0){
                        $quesDetailPageData['entityDetails']['hasUserReportedAbuse'] = false;   
                }else{
                         $quesDetailPageData['entityDetails']['hasUserReportedAbuse'] = true;  
                }
                
                if($quesDetailPageData['entityDetails']['hasUserAnswered'] == 0){
                        $quesDetailPageData['entityDetails']['hasUserAnswered'] = false;   
                }else{
                         $quesDetailPageData['entityDetails']['hasUserAnswered'] = true;  
                }

                if(empty($quesDetailPageData['entityDetails']['levelName'])){
                    $quesDetailPageData['entityDetails']['levelName'] = 'Beginner-Level 1';
                }

                $questionDate = date('Y-m-d',strtotime($quesDetailPageData['entityDetails']['creationDate']));
                  
                $quesDetailPageData['entityDetails']['creationDate'] = makeRelativeTime($quesDetailPageData['entityDetails']['creationDate']);
                
                $quesDetailPageData['entityDetails']['shareUrl']  =  getSeoUrl($quesDetailPageData['entityDetails']['msgId'], 'question', $quesDetailPageData['entityDetails']['msgTxt'],array(),'NA',$questionDate);
                
                if(empty($quesDetailPageData['entityDetails']['picUrl'])){
                    $quesDetailPageData['entityDetails']['picUrl'] = NULL;
                }else
                {
                    $quesDetailPageData['entityDetails']['picUrl'] = addingDomainNameToUrl(array('url' =>$quesDetailPageData['entityDetails']['picUrl'], 'domainName' => MEDIA_SERVER ));   
                } /*if(strpos($quesDetailPageData['entityDetails']['picUrl'],'http') === false){
                    $quesDetailPageData['entityDetails']['picUrl'] = SHIKSHA_HOME.$quesDetailPageData['entityDetails']['picUrl'];
                }*/
                
                if($quesDetailPageData['entityDetails']['aboutMe'] == ''){
                    $quesDetailPageData['entityDetails']['aboutMe'] = NULL;
                }else{
                    $quesDetailPageData['entityDetails']['aboutMe'] = html_entity_decode($quesDetailPageData['entityDetails']['aboutMe']);
                }
                
                
                
                foreach($quesDetailPageData['childDetails'] as $key=>$val){
                   
                        if($val['hasUserReportedAbuse'] == 0){
                              $quesDetailPageData['childDetails'][$key]['hasUserReportedAbuse'] = false; 
                        }else{
                              $quesDetailPageData['childDetails'][$key]['hasUserReportedAbuse'] = true; 
                        }
                       
                        $quesDetailPageData['childDetails'][$key]['formattedTime']  =  makeRelativeTime($quesDetailPageData['childDetails'][$key]['creationDate']);

                        $answerDate = date('Y-m-d',strtotime($quesDetailPageData['childDetails'][$key]['creationDate']));
                
                        $quesDetailPageData['childDetails'][$key]['shareUrl']  = getSeoUrl($quesDetailPageData['entityDetails']['msgId'], 'question', $quesDetailPageData['entityDetails']['msgTxt'],array(),'NA',$answerDate).'?referenceEntityId='.$quesDetailPageData['childDetails'][$key]['msgId'];
                
   
                         if(empty($quesDetailPageData['childDetails'][$key]['picUrl'])){
                            
                            $quesDetailPageData['childDetails'][$key]['picUrl'] = NULL;
                         }else{
                            $quesDetailPageData['childDetails'][$key]['picUrl'] = addingDomainNameToUrl(array('url' =>$quesDetailPageData['childDetails'][$key]['picUrl'], 'domainName' => MEDIA_SERVER ));   
                         } /*if(strpos($quesDetailPageData['childDetails'][$key]['picUrl'],'http') === false){
                             $quesDetailPageData['childDetails'][$key]['picUrl'] = SHIKSHA_HOME.$quesDetailPageData['childDetails'][$key]['picUrl'];
                         }*/

    					if($quesDetailPageData['childDetails'][$key]['aboutMe'] == ''){
                                    $quesDetailPageData['childDetails'][$key]['aboutMe'] = NULL;
                                }else{
                                    $quesDetailPageData['childDetails'][$key]['aboutMe'] = html_entity_decode($quesDetailPageData['childDetails'][$key]['aboutMe']);
                                }

                        if(empty($quesDetailPageData['childDetails'][$key]['levelName'])){
                                $quesDetailPageData['childDetails'][$key]['levelName'] = 'Beginner-Level 1';
                            }
                        
                }
                
        }
        
        return $quesDetailPageData;
            
    }
    
    
    function formatCommentDetailsPagination($entityId,$userId,$start,$count,$sortOrder='latest',$referenceCommentId){
        
        $this->CI->load->model('messageBoard/AnAModel');
        
        $commentDetailData = $this->CI->AnAModel->getCommentDetails($entityId,$userId,$start,$count,$sortOrder,$referenceCommentId);
                
            foreach($commentDetailData['childDetails'] as $key=>$val){
                   
                   if(empty($commentDetailData['childDetails'][$key]['picUrl'])){
                    
                        $commentDetailData['childDetails'][$key]['picUrl'] = NULL;
                    
                   }else
                   {
                     $commentDetailData['childDetails'][$key]['picUrl'] = addingDomainNameToUrl(array('url' =>$commentDetailData['childDetails'][$key]['picUrl'], 'domainName' => MEDIA_SERVER ));
                   } /*if(strpos($commentDetailData['childDetails'][$key]['picUrl'],'http') === false){
                    
                        $commentDetailData['childDetails'][$key]['picUrl']  =  SHIKSHA_HOME.$commentDetailData['childDetails'][$key]['picUrl'];
                    
                   }*/
                    
                    $commentDetailData['childDetails'][$key]['formattedTime']  =  makeRelativeTime($commentDetailData['childDetails'][$key]['creationDate']);
                    
                    if($commentDetailData['childDetails'][$key]['aboutMe'] == ''){
                                $commentDetailData['childDetails'][$key]['aboutMe'] = NULL;
                    }else{
                                $commentDetailData['childDetails'][$key]['aboutMe'] = html_entity_decode($commentDetailData['childDetails'][$key]['aboutMe']);
                    }

                    if(empty($commentDetailData['childDetails'][$key]['levelName'])){
                            $commentDetailData['childDetails'][$key]['levelName'] = 'Beginner-Level 1';
                        }
                    
           }
                
        return $commentDetailData;
    }
    
    
    function formatDiscussionDetailPageData($discussionId,$userId,$start,$count,$sortOrder='latest',$referenceCommentId){
        
         $this->CI->load->model('messageBoard/AnAModel');
         $discussionDetailData = $this->CI->AnAModel->getDiscussionPageData($userId,$discussionId,$start,$count,$sortOrder,$referenceCommentId);
         
          if(!empty($discussionDetailData['entityDetails'])){
            
                $discussionDetailData['entityDetails']['description'] = strip_tags(html_entity_decode($discussionDetailData['entityDetails']['description']));

                $discussionDate = date('Y-m-d',strtotime($discussionDetailData['entityDetails']['creationDate']));
                
                $discussionDetailData['entityDetails']['creationDate']  =  makeRelativeTime($discussionDetailData['entityDetails']['creationDate']);
                
                $discussionDetailData['entityDetails']['shareUrl'] = getSeoUrl($discussionDetailData['entityDetails']['threadId'], 'discussion', $discussionDetailData['entityDetails']['msgTxt'],array(),'NA',$discussionDate);
                
                
                if(empty($discussionDetailData['entityDetails']['picUrl'])){
                    $discussionDetailData['entityDetails']['picUrl'] = NULL;
                    
                }else{
                    $discussionDetailData['entityDetails']['picUrl'] = addingDomainNameToUrl(array('url' =>$discussionDetailData['entityDetails']['picUrl'], 'domainName' => MEDIA_SERVER ));
                } /*if(strpos($discussionDetailData['entityDetails']['picUrl'],'http') === false){
                    $discussionDetailData['entityDetails']['picUrl'] = SHIKSHA_HOME.$discussionDetailData['entityDetails']['picUrl'];
                }*/
                
                if($discussionDetailData['entityDetails']['aboutMe'] == ''){
                                $discussionDetailData['entityDetails']['aboutMe'] = NULL;
                }else{
                                $discussionDetailData['entityDetails']['aboutMe'] = html_entity_decode($discussionDetailData['entityDetails']['aboutMe']);
                }

                if(empty($discussionDetailData['entityDetails']['levelName'])){
                    $discussionDetailData['entityDetails']['levelName'] = 'Beginner-Level 1';
                }
                
                foreach($discussionDetailData['childDetails'] as $key=>$val){
                    
                        $discussionDetailData['childDetails'][$key]['formattedTime']  =  makeRelativeTime($discussionDetailData['childDetails'][$key]['creationDate']);
                        
                        if(empty($discussionDetailData['childDetails'][$key]['picUrl'])){
                            $discussionDetailData['childDetails'][$key]['picUrl'] = NULL;
                            
                        }else{
                            $discussionDetailData['childDetails'][$key]['picUrl'] = addingDomainNameToUrl(array('url' =>$discussionDetailData['childDetails'][$key]['picUrl'], 'domainName' => MEDIA_SERVER ));
                        } /*if(strpos($discussionDetailData['childDetails'][$key]['picUrl'],'http') === false){
                            $discussionDetailData['childDetails'][$key]['picUrl'] = SHIKSHA_HOME.$discussionDetailData['childDetails'][$key]['picUrl'];
                        }*/

                        $commentDate = date('Y-m-d',strtotime($discussionDetailData['childDetails'][$key]['creationDate']));
                        
                        $discussionDetailData['childDetails'][$key]['shareUrl']  = getSeoUrl($discussionId, 'discussion', $discussionDetailData['entityDetails']['msgTxt'],array(),'NA',$commentDate).'?referenceEntityId='.$discussionDetailData['childDetails'][$key]['msgId'];
                        
                        if($discussionDetailData['childDetails'][$key]['aboutMe'] == ''){
                                  $discussionDetailData['childDetails'][$key]['aboutMe'] = NULL;
                        }else{
                                  $discussionDetailData['childDetails'][$key]['aboutMe'] = html_entity_decode($discussionDetailData['childDetails'][$key]['aboutMe']);
                        }

                        if(empty($discussionDetailData['childDetails'][$key]['levelName'])){
                            $discussionDetailData['childDetails'][$key]['levelName'] = 'Beginner-Level 1';
                        }

                        
                }
                
                
        
        }
        
        return $discussionDetailData;
    }
    
    function setUniqueTags($tagArray){
        $finalArray = array();
        $tagIdAdded = array();
        $numberOfTags = count($tagArray);
        for ($i = ($numberOfTags-1); $i>=0; $i--){
                $tagId = $tagArray[$i]['tagId'];
                if(!in_array($tagId, $tagIdAdded)){
                        $finalArray[] = $tagArray[$i];
                        $tagIdAdded[] = $tagId;
                }
        }
        return $finalArray;
    }

function formatReplyDetailsPagination($commentId,$userId,$start,$count){
        
        $this->CI->load->model('messageBoard/AnAModel');
        $replyDetailData['childDetails'] = $this->CI->AnAModel->getReplyDetails($commentId,$userId,$start,$count);
                
            foreach($replyDetailData['childDetails'] as $key=>$val){
                   
                   if(empty($replyDetailData['childDetails'][$key]['picUrl'])){
                    
                        $replyDetailData['childDetails'][$key]['picUrl'] = NULL;
                    
                   }else{
                    $replyDetailData['childDetails'][$key]['picUrl'] = addingDomainNameToUrl(array('url' =>$replyDetailData['childDetails'][$key]['picUrl'], 'domainName' => MEDIA_SERVER ));
                   } /*if(strpos($replyDetailData['childDetails'][$key]['picUrl'],'http') === false){
                    
                        $replyDetailData['childDetails'][$key]['picUrl']  =  SHIKSHA_HOME.$replyDetailData['childDetails'][$key]['picUrl'];
                    
                   }*/
                   $replyDetailData['childDetails'][$key]['formattedTime']  =  makeRelativeTime($replyDetailData['childDetails'][$key]['creationDate']);
                   
                   if($replyDetailData['childDetails'][$key]['aboutMe'] == ''){
                                  $replyDetailData['childDetails'][$key]['aboutMe'] = NULL;
                   }else{
                                  $replyDetailData['childDetails'][$key]['aboutMe'] = html_entity_decode($replyDetailData['childDetails'][$key]['aboutMe']);
                   }

                   if(empty($replyDetailData['childDetails'][$key]['levelName'])){
                        $replyDetailData['childDetails'][$key]['levelName'] = 'Beginner-Level 1';
                    }
                   
           }
                
        return $replyDetailData;
    }


    function getHomepageData($userId, $start, $pageNumber, $filter, $debugFlag = 0, $visitorId = '', $totalResponsePerRequest = 10){

        $feedData            = array();
        $questionIds         = array();
        $discussionIds       = array();
        $userIds             = array();
        $commentAndAnswerIds = array();
        $tagIds = array();

        $this->CI->load->model("user/usermodel");
        $this->CI->load->model("messageBoard/anamodel");
        $this->CI->load->model('v1/tagsmodel');
        $this->CI->load->library("common/personalization/PersonalizationLibrary");
        $this->CI->personalizationlibrary->setUserId($userId);// set userId for this library before accessing any of its functionality.
        $this->CI->personalizationlibrary->setVisitorId($visitorId);
        
        if($debugFlag)
            $this->CI->personalizationlibrary->setDebugFlag(1);
        
        // 1. fetch all objects of personalized data
	$this->CI->benchmark->mark('get_home_feed_start');
        $homeFeedRelatedData		= $this->CI->personalizationlibrary->getHomeFeedData($start, $filter, $totalResponsePerRequest);
	$this->CI->benchmark->mark('get_home_feed_end');
        

        $personalizedDataObjects	= $homeFeedRelatedData['homeFeed'];

        // 2. loop over objects to get the details
        $i = 0;
        $threadTagsMapping = array();
        foreach($personalizedDataObjects as $personlizedDataObj){

            if($personlizedDataObj->getThreadType() == 'discussion'){
                $feedData[$i]['type'] = 'D';
                $feedData[$i]['uniqueId'] = '2';
                $discussionIds[] = $personlizedDataObj->getThreadId();
            }
            else{
                $feedData[$i]['type'] = 'Q';
                $feedData[$i]['uniqueId'] = '1';
                $questionIds[] = $personlizedDataObj->getThreadId();
            }

            $feedData[$i]['id']            = $personlizedDataObj->getThreadId();
            $feedData[$i]['questionId']    = $personlizedDataObj->getThreadId();
            if($personlizedDataObj->getReason() != 'topContent'){
            	$feedData[$i]['activityTime']  = makeRelativeTime($personlizedDataObj->getActionDateTime());
            }
            $feedData[$i]['headingUserId'] = $personlizedDataObj->getActionByUserId();
            $feedData[$i]['heading']       = $personlizedDataObj->getHomePageMessage();
            if($personlizedDataObj->isFreind() || $personlizedDataObj->isFollower()){
            	$feedData[$i]['setHeadingUsername'] = TRUE;
            }else{
            	$feedData[$i]['setHeadingUsername'] = FALSE;
            }
            if($personlizedDataObj->getTagId() > 0){
                $feedData[$i]['tagId'] = $personlizedDataObj->getTagId();
                $tagIds[]   = $personlizedDataObj->getTagId();
            }
            $threadTagsMapping[$personlizedDataObj->getThreadId()] = array_keys($personlizedDataObj->getTagsAttachedToThread());
            
            
            $userIds[] = $personlizedDataObj->getActionByUserId();

            if($personlizedDataObj->getActionItemId()){
                $commentAndAnswerIds[] = $personlizedDataObj->getActionItemId();
                $feedData[$i]['answerId'] = $personlizedDataObj->getActionItemId();
            }

            $i++;
        }

        $threadIds = array();
        $threadIds = array_merge($questionIds, $discussionIds);

        // get all question ids
	$this->CI->benchmark->mark('get_question_basic_start');
        $questionsDetail = $this->CI->anamodel->getQuestionsBasicDetails($questionIds);
	$this->CI->benchmark->mark('get_question_basic_end');

        // get all discussion ids;
	$this->CI->benchmark->mark('get_discussion_basic_start');
        $discussionsDetails = $this->CI->anamodel->getDiscussionsBasicDetails($discussionIds);
	$this->CI->benchmark->mark('get_discussion_basic_end');
	$this->CI->benchmark->mark('get_discussion_comment_start');
        $discussionsCommentCount = $this->CI->anamodel->getDiscussionsCommentCount($discussionIds);
	$this->CI->benchmark->mark('get_discussion_comment_end');
        
        // upvote/downvote count
	$this->CI->benchmark->mark('get_rating_start');
        $votes = $this->CI->anamodel->getUpAndDownVotesOfEntities($commentAndAnswerIds);
	$this->CI->benchmark->mark('get_rating_end');

        // get comment/answer text
	$this->CI->benchmark->mark('get_answer_comment_start');
        $commentAnswerDetails = $this->CI->anamodel->getAnswerCommentDetails($commentAndAnswerIds, $userId);
	$this->CI->benchmark->mark('get_answer_comment_end');
        foreach ($commentAnswerDetails as $value) {
            $userIds[] = $value['userId'];
        }
        $userIds = array_filter($userIds);

        // follow count
	$this->CI->benchmark->mark('get_followers_start');
        $followers = $this->CI->anamodel->getThreadFollowers($threadIds);
	$this->CI->benchmark->mark('get_followers_end');
        
        // get comment and reply count on answer and comment of question and discusssion respectively
        if(count($commentAndAnswerIds) > 0){
        	$secondLevelCommentReplyCount = $this->CI->anamodel->getSecondLevelCommentReplyCount($commentAndAnswerIds);
        }

        // get all user-ids
	$this->CI->benchmark->mark('get_user_info_start');
        $userDetails = $this->CI->usermodel->getUsersBasicInfoById($userIds);
        $userAdditionalDetails = $this->CI->usermodel->getUsersAdditionalInfo($userIds);
	$this->CI->benchmark->mark('get_user_info_end');

        // level of user
	$this->CI->benchmark->mark('get_user_level_start');
        $userLevelDetails = $this->CI->anamodel->getAnAUsersLevel($userIds);
	$this->CI->benchmark->mark('get_user_level_end');

        // get tags of threads
        //$threadTagsMapping = $tagsmodel->getTagsOfThreads($threadIds);
        foreach ($threadTagsMapping as $value) {
            $tagIds = array_merge($tagIds, $value);
        }

        // tag details
	$this->CI->benchmark->mark('get_tag_details_start');
        $tagDetails = $this->CI->tagsmodel->getTagsDetailsById($tagIds);
	$this->CI->benchmark->mark('get_tag_details_end');
        
        // for every thread having tags, sort tagIds in order of tag_entity in ('Colleges','University')
        foreach ($threadTagsMapping as $key => &$tagIdsArray){
        	usort($tagIdsArray, function($a , $b) use($tagDetails){
        		if(in_array($tagDetails[$a]['tag_entity'], array('Colleges','University'))){
        			return -1;
        		}elseif (in_array($tagDetails[$b]['tag_entity'], array('Colleges','University'))){
        			return 1;
        		}else{
        			return -1;
        		}
        	});
        	
        }
        
        // thread follow status
	$this->CI->benchmark->mark('get_is_following_start');
        $isthreadFollowing = $this->CI->tagsmodel->isUserFollowingEntity($userId, $threadIds, array('question','discussion'));
	$this->CI->benchmark->mark('get_is_following_end');

        $answeredQuestions = array();
        if($questionIds){
            $questionIdsString = implode(",",$questionIds);
	    $this->CI->benchmark->mark('get_question_answered_start');
            $answeredQuestions = $this->CI->tagsmodel->getQuestionsAnsweredByUser($questionIdsString, $userId);
	    $this->CI->benchmark->mark('get_question_answered_end');
        }

        foreach ($feedData as $key => $feed) {

            $threadType = '';
            $threadId = $feed['id'];

            if($feed['type'] == 'Q'){
                $threadType = 'question';
                $feedData[$key]['title']           = htmlspecialchars_decode($questionsDetail[$threadId]['msgTxt']);
                $feedData[$key]['answerCount']     = $questionsDetail[$threadId]['msgCount'];
                $feedData[$key]['viewCount']       = $questionsDetail[$threadId]['viewCount'];
                $feedData[$key]['threadStatus']    = $questionsDetail[$threadId]['status'];
                $feedData[$key]['questionOwnerName'] = $questionsDetail[$threadId]['firstname'];
                $feedData[$key]['isThreadOwner']   = ($questionsDetail[$threadId]['userId'] == $userId) ? true : false;
                $feedData[$key]['hasUserAnswered'] = in_array($threadId, $answeredQuestions) ? true : false;
                $feedData[$key]['creationDate'] = $questionsDetail[$threadId]['creationDate'];     
            }
            else if($feed['type'] == 'D'){
                $threadType = 'discussion';
                $feedData[$key]['title']         = $discussionsDetails[$threadId]['msgTxt'];
                $feedData[$key]['answerCount']   = $discussionsCommentCount[$threadId] ? $discussionsCommentCount[$threadId] : 0;
                $feedData[$key]['viewCount']     = $discussionsDetails[$threadId]['viewCount'] ? $discussionsDetails[$threadId]['viewCount'] : 0;
                $feedData[$key]['threadStatus']  = $discussionsDetails[$threadId]['status'];
                $feedData[$key]['isThreadOwner'] = ($discussionsDetails[$threadId]['userId'] == $userId) ? true : false;
                $feedData[$key]['creationDate'] = $discussionsDetails[$threadId]['creationDate'];
            }
            if(isset($secondLevelCommentReplyCount[$feed['answerId']])){
            	$feedData[$key]['commentCount'] = $secondLevelCommentReplyCount[$feed['answerId']];
            }

            $feedData[$key]['followerCount']   = $followers[$threadId] ? $followers[$threadId] : 0;

            $entityDate = date('Y-m-d',strtotime($feedData[$key]['creationDate']));
            $feedData[$key]['URL']             = getSeoUrl($threadId, $threadType, $feedData[$key]['title'],array(),'NA',$entityDate);
            $feedData[$key]['isUserFollowing'] = in_array($feed['id'], $isthreadFollowing) ? 'true' : 'false';
            
            if($feedData[$key]['setHeadingUsername']){
            	$feedData[$key]['headingUsername'] = $userDetails[$feed['headingUserId']]['firstname'];
            }

            if($feed['answerId']){
                $commentOwnerId                      = $commentAnswerDetails[$feed['answerId']]['userId'];
                $feedData[$key]['likeCount']         = $votes[$feed['answerId']]['1'] ? $votes[$feed['answerId']]['1'] : 0;
                $feedData[$key]['dislikeCount']      = $votes[$feed['answerId']]['0'] ? $votes[$feed['answerId']]['0'] : 0;
                $feedData[$key]['answerText']        = $commentAnswerDetails[$feed['answerId']]['msgTxt'];
                $feedData[$key]['answerOwnerUserId'] = $commentOwnerId;//$feed['answerCommentUserId'];
                $feedData[$key]['answerOwnerName']   = $userDetails[$commentOwnerId]['firstname']." ".$userDetails[$commentOwnerId]['lastname'];
                if (empty($userDetails[$commentOwnerId]['avtarimageurl'])){
                	$feedData[$key]['answerOwnerImage'] = null;
                }else{
                    $feedData[$key]['answerOwnerImage'] = addingDomainNameToUrl(array('url' =>$userDetails[$commentOwnerId]['avtarimageurl'], 'domainName' => MEDIA_SERVER ));
                }
                /*if (strpos(ltrim($userDetails[$commentOwnerId]['avtarimageurl']), 'http') === FALSE){
                	$feedData[$key]['answerOwnerImage'] = SHIKSHA_HOME.'/'.$userDetails[$commentOwnerId]['avtarimageurl'];
                }else{
                	$feedData[$key]['answerOwnerImage'] = $userDetails[$commentOwnerId]['avtarimageurl'];
                }*/
                //$feedData[$key]['answerOwnerImage']  = $userDetails[$commentOwnerId]['avtarimageurl'];
                $feedData[$key]['answerOwnerLevel']  = $userLevelDetails[$commentOwnerId] ? $userLevelDetails[$commentOwnerId] : 'Beginner-Level 1';
                $feedData[$key]['hasUserVotedUp']    = ($commentAnswerDetails[$feed['answerId']]['hasUserVoted'] == '1') ? true : false;
                $feedData[$key]['hasUserVotedDown']  = ($commentAnswerDetails[$feed['answerId']]['hasUserVoted'] == '0') ? true : false;
                $feedData[$key]['aboutMe']           = $userAdditionalDetails[$commentOwnerId] ? html_entity_decode($userAdditionalDetails[$commentOwnerId]['aboutMe']) : null;
                $feedData[$key]['creationDate']      = makeRelativeTime($commentAnswerDetails[$feed['answerId']]['creationDate']);
                
            }else{
                $feedData[$key]['likeCount']         = 0;
                $feedData[$key]['dislikeCount']      = 0;
                $feedData[$key]['answerText']        = null;
                $feedData[$key]['answerOwnerUserId'] = null;
                $feedData[$key]['answerOwnerName']   = null;
                $feedData[$key]['answerOwnerImage']  = null;
                $feedData[$key]['answerOwnerLevel']  = null;
                $feedData[$key]['hasUserVotedUp']    = false;
                $feedData[$key]['hasUserVotedDown']  = false;
                $feedData[$key]['aboutMe']           = null;
                $feedData[$key]['creationDate']      = null;
            }
            
            if(key_exists('tagId', $feedData[$key]) && $feedData[$key]['tagId'] > 0){
            	$feedData[$key]['heading'] = str_replace('<tagName>', $tagDetails[$feedData[$key]['tagId']]['tags'], $feedData[$key]['heading']);
            }

            $feedData[$key]['tags'] = array();
            if($feedData[$key]['tagId']){
                $feedData[$key]['tags'][] = array("tagId" => $feedData[$key]['tagId'], "tagName" => $tagDetails[$feedData[$key]['tagId']]['tags']);
            }

            foreach ($threadTagsMapping[$threadId] as $value) {
                if($feedData[$key]['tagId'] == $value)
                    continue;
                if(count($feedData[$key]['tags']) >= 2)
                    break;
                $feedData[$key]['tags'][] = array("tagId" => $value, "tagName" => $tagDetails[$value]['tags']);
            }

            unset($feedData[$key]['tagId']);
            
        }

        $finalData = array('homeFeed' => $feedData, 'nextPaginationIndex' => $homeFeedRelatedData['nextPaginationIndex'], 'newHomeFeedItems' => $homeFeedRelatedData['newHomeFeedItems']);

        if($debugFlag){
            $finalData['debugData']               = $this->CI->personalizationlibrary->getDebugData();
        }
        return $finalData;
    }
    
    
    
    function formatListOfUsersDetailActionBased($userId,$entityId,$start,$count,$actiontype,$entityType){
        $this->CI->load->model('messageBoard/AnAModel');

        if($actiontype == 'follow'){
            $userDetails = $this->CI->AnAModel->getUserDetailsWhoFollowedQues($userId,$entityId,$start,$count,$entityType);
        }else if($actiontype == 'upvote'){
            $userDetails = $this->CI->AnAModel->getUserDetailsWhoVotedup($userId,$entityId,$start,$count);
        }
          
         if(!empty($userDetails)){
                foreach($userDetails as $key=>$val){
                    if($userDetails[$key]['aboutMe'] == ''){
                        $userDetails[$key]['aboutMe'] = NULL;
                    }else{
                        $userDetails[$key]['aboutMe'] = html_entity_decode($userDetails[$key]['aboutMe']);
                    }
                                         
                    if($userDetails[$key]['isUserFollowing'] == NULL){
                            $userDetails[$key]['isUserFollowing'] = 'false';   
                    }else{
                             $userDetails[$key]['isUserFollowing'] = 'true';  
                    }

                    if(empty($userDetails[$key]['avtarimageurl'])){
                        $userDetails[$key]['avtarimageurl'] = NULL;
                    }else{
                        $userDetails[$key]['avtarimageurl'] = addingDomainNameToUrl(array('url' =>$userDetails[$key]['avtarimageurl'], 'domainName' => MEDIA_SERVER ));
                    }/*if(strpos($userDetails[$key]['avtarimageurl'],'http') === false){
                        $userDetails[$key]['avtarimageurl'] = SHIKSHA_HOME.$userDetails[$key]['avtarimageurl'];
                    }*/

                    if(empty($userDetails[$key]['levelName'])){
                        $userDetails[$key]['levelName'] = 'Beginner-Level 1';
                    }

                    $userDetails[$key]['userName'] = $userDetails[$key]['firstname'].' '.$userDetails[$key]['lastname'];
                    if($userId == $userDetails[$key]['userId']){
                        $userDetails[$key]['url'] = SHIKSHA_HOME.'/userprofile/edit';
                    }else{
                        $userDetails[$key]['url'] = SHIKSHA_HOME.'/userprofile/'.$userDetails[$key]['userId'];
                    }
                    

                }
        }       
        
        return $userDetails;
    }

    /**
        * @desc Automoderation of an entity like question/answer/comment/reply
        * @date 2016-05-12
        * @author Yamini Bisht
    */

    function autoModerationKeywordReplace($str,$keywordData)
    {
          preg_match_all('/(((http|https):\/\/[^\s]+)|((www.)[^\s]+))/i', $str, $matches);

          $randomValue = array();
          foreach ($matches[0] as $key => $value) {
                $r = rand(5,100);
                $randomValue['$$$$'.$r] = $value;
                $str = str_replace($value, '$$$$'.$r, $str);
          }

          $lingoArray = $keywordData['lingoArray'];
          $actualWordArray = $keywordData['actualWordArray'];
          $str = preg_replace($lingoArray, $actualWordArray, $str);
          $str = str_replace('%age','percentage',$str);
          $str = str_replace('%tile','percentile',$str);
          $str = str_replace(array('lakh p.a.','lac p.a.'),'LPA',$str);
          $str = preg_replace('/\s(in\s{0,1}abroad)/i', ' abroad', $str);
          $str = preg_replace('/\s(etc\.{0,})/i', ' etc.', $str);
          $str = str_replace(array_keys($randomValue), array_values($randomValue), $str);
       
          return $str;
    }

     /**
        * @desc detection of email and phone no in a message
        * @date 2016-05-23
        * @author Yamini Bisht
    */

    function emailAndPhonenoDetectionInString($message)
    {
          $result = array();

          preg_match_all('/(((http|https):\/\/[^\s]+)|((www[.])[^\s]+))/i', $message, $matches);

          $randomValue = array();
          foreach ($matches[0] as $key => $value) {
                $r = rand(5,100);
                $randomValue['$$$$'.$r] = $value;
                $message = str_replace($value, '$$$$'.$r, $message);
                if(strpos($value, "shiksha.com") === FALSE){
                    $result['urlContentFlag'] = 1;
                }
          }

          if(preg_match('/[a-z0-9_\-\+]+([\s])?([@])([\s])?[a-z0-9\-]+([\s])?([\\.]|(dot))([\s])?([a-z]{2,4}+)/i',$message)){

                $result['emailContentFlag'] = 1;
                
          }

          if(preg_match('/\+?[0-9][0-9()-\s+]{5,20}[0-9]/',$message)){

                 $result['phoneNoContentFlag'] = 1;
          }
          
           return $result;

    }

    function getAnAMostActiveUsers($loggedInUserId){
        
        $predisLibrary          = PredisLibrary::getInstance();        
        $activeUsers = $predisLibrary->getMembersInSortedSet("mostActiveAnAUsers", 0 , -1, TRUE, TRUE);
        $userCommonLib = $this->CI->load->library("v1/UserCommonLib");
        $activeUsersIds = array_keys($activeUsers);
        $userData = $userCommonLib->getAnAUserData($activeUsersIds, array("basicDetails","levelDetails","answercount","followercount","isUserFollowing","upvotescount","aboutMe"), $loggedInUserId);//array(11,5135717,1471011,2134483,5137899,5137889,5135105,5135481));
        foreach ($activeUsers as $userId => $points) {
            $userData[$userId]['name'] = $userData[$userId]['firstname']." ".$userData[$userId]['lastname'];
            if(!$userData[$userId]){
                unset($activeUsers[$userId]);
                continue;
            }
            $userData[$userId]['weekPoints'] = $points;
            $activeUsers[$userId] = $userData[$userId];
        }

        $activeUsers = array_values($activeUsers);
        
        return $activeUsers;
    }

    function getThreadTagForMostActiveUsers($threadId, $threadType){

        $tagType = "Stream";
        $finalTag = array();
        $tagsModel = $this->CI->load->model("v1/tagsmodel");

        // Stage 1 : Get all tags of thread
        $tags = $tagsModel->getThreadTagsWithType($threadId, $threadType, array("objective","manual"));

        if(isset($tags[$tagType])){
            return array("tagId" => $tags[$tagType][0]['id'], "tagName" => $tags[$tagType][0]['tags']);
        }

        // Stage 2 : check for the stream tag in parents
        $tagList = array();
        foreach($tags as $value) {
            foreach ($value as $value1) {
                $tagList[] = $value1['id'];
            }
        }
        $parentTags = $tagsModel->getTagsParent($tagList);
        foreach ($parentTags as $value) {
            if($value['tag_entity'] == $tagType){
                return array("tagId" => $value['tag_id'], "tagName" => $value['tags']);
            }
        }

        // Stage 3 : check for stream tag in grandparents
        $tagList = array();
        foreach($parentTags as $value) {
            $tagList[] = $value['tag_id'];
        }
        $parentTags = $tagsModel->getTagsParent($tagList);
        foreach ($parentTags as $value) {
            if($value['tag_entity'] == $tagType){
                return array("tagId" => $value['tag_id'], "tagName" => $value['tags']);
            }
        }

        return false;

    }


   /**
    * @desc - Remove impurities or unwanted elements from (a substance) string.
    * @author akhter
    **/
   function refineElementFromString($text, $isDotSpace=false){
        if(!empty($text)){

            //check URL if found then will omit the URL from the text
            //$text = preg_replace_callback(array("#(ftp:\/\/|www\.|(https|http)?:\/\/){1}[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\S*)#i"),array($this,'replaceURLCallBack'),$text);

            $text = preg_replace_callback(array('#\b(?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’])#i'),array($this,'replaceURLCallBack'),$text);

            //Check if whole sentence is in upper case
            if(!preg_match('/([a-z])+/', $text)){
                $text = strtolower($text);
            }
            //Rule 1- Remove the multiple occurrences of special characters to one and Extra space between words
            $text = preg_replace("/(\W)\\1+/", "$1", $text);
            $text = preg_replace('/\s{0,}(:\s{0,}\/)\s{0,}+/i', '://', $text); // only for URL like :/=>://
            //Rule 2- Removing consecutive duplicate words in a string
            $text = preg_replace('/\b(\S+)(?:\s+\1\b)+/i', '$1', $text);
            //Rule 3- Removing space before a full stop, comma and question mark and adding space after comma, full stop and question mark
            if($isDotSpace){
                // Applicable for Reviews only
                $text = preg_replace('/(?!\d)\s{0,}([.,?])\s{0,}(?!\d)+/', '$1 ', $text); 
            }else{
                $text = preg_replace('/(?!\d)\s{0,}([,?])\s{0,}(?!\d)+/', '$1 ', $text);
            }
            //Rule 4- Adding space before small bracket (),{},[] begins e.g course in law(llb) should be law (llb)
            $text = preg_replace('~\s*([([{])~',  ' $1', $text);
            //Rule 2- Removing consecutive duplicate words in a string
            $text = preg_replace('/\b(\S+)(?:\s+\1)+\s+/i', '$1 ', $text);
            //Rule 1- Remove the multiple occurrences of space to one between words
            //$text = preg_replace('/\s+/', ' ',$text);
            //Rule 5- Remove commas preceding question marks OR exclamation marks
            $text = str_replace(', ?', '?', $text);
            $text = str_replace(', !', '!', $text);
            //Rule 6- add extra rules here    
            $rules   = array('/(\?\s{0,}\.)/', '/(\!\s{0,}\.)/');
            $replace = array('?','!');
            $text    = preg_replace($rules, $replace, $text);
            //Rule 7- Do no apply Rule-3 on these courses.
            $escapeDotArr = array('B.A','B.A. LL.B','B.Arch','B.Com','B.E','B.Tech','B.Ed','B.Pharma','B.Sc','LL.B','M.A','M.Arch','M.Com','M.E','M.Tech','M.Pharma','M.Sc','Ph.D','M.Phil', 'etc.');
            foreach ($escapeDotArr as $key => $value) {
                $rules = '\s{0,}([.])\s{0,}';
                $value = rtrim($value, '.');
                $str   = str_replace('.', $rules, $value);
                $tmpRulesArr[] = '/\b('.$str.')\b/i';
            }
            $text = preg_replace($tmpRulesArr, $escapeDotArr, $text);
            $replaceSpecialChar = array("`", "’", "‘", "×");
            $replaceWith = array("'", "'", "'", "*");
            $text = str_replace($replaceSpecialChar, $replaceWith, $text);

            unset($tmpRulesArr);

            return $text;
        }
   }

   /**
    * @desc - Addition of "The" before superlative
    * @author akhter
    **/
    function findSuperlative($text, $superlativeList){
            foreach ($superlativeList as $key => $value) {
                $tmpArr[] = '/\b('.$value.')\b/i';
                $actualWordArr[] = 'the '.$value; 
            }
            $text = preg_replace($tmpArr, $actualWordArr, $text);
            unset($tmpArr, $actualWordArr);
            $text = str_replace('thethe', 'the', $text);
            $text = preg_replace('/\b(\S+)(?:\s+\1\b)+/i', '$1', $text); // remove duplicate words
            $text = preg_replace('/\b(\S+)(?:\s+\1)+\s+/i', '$1 ', $text);
            return $text;
    }


    function getMappingFromCache(){
        $data    = array();
        //$this->inputArr = array('exam','basecourse','institute');
        $this->inputArr = array('exam','basecourse');
        $this->cache              = $this->CI->load->library('common/CleanUpEntityCache');
        foreach ($this->inputArr as $key => $entityName) {
            $data[$entityName] = $this->cache->getEntity($entityName);
        }
        $resultData = array();
        foreach ($this->inputArr as $key => $entityName) {
            $count = 0;
            foreach ($data[$entityName] as $k => $v) {
                $resultData[$entityName][$count] = $v;
                $count++;
            }
        }
        return $resultData;
    } 

    function replaceURLCallBack($matches)
    {
            $this->tmpURLArr['url'][$this->urlCount] = $matches[0];
            $this->tmpURLArr['replaceKey'][$this->urlCount] = 'Url'.$this->urlCount;
            $finalStr = str_replace($matches[0], 'Url'.$this->urlCount, $matches[0]);
            $this->urlCount++;
            return $finalStr;
    }

    function runCleaningProcess($sentence, $cachedData){
        if(empty($sentence)){
            return;
        }
        $this->inputArr = array('exam','basecourse');
        $this->entitycleanupmodel = $this->CI->load->model("common/entitycleanupmodel");
        foreach ($this->inputArr as $key => $entityName) {
            if(empty($cachedData[$entityName])){
                $returnData = $this->entitycleanupmodel->getData(array($entityName));
                $data[$entityName]    = $returnData[$entityName];
            }else{
                $data[$entityName]   =  $cachedData[$entityName];
            }
        }

        // enable for test01 server
        if(ENVIRONMENT == 'test1'){
            $output = exec("/usr/local/activepython/bin/python /var/www/html/shiksha/application/config/entityAutoModeration.py ".escapeshellarg(json_encode($sentence))." ".escapeshellarg(json_encode($data)));
        }else{
        // enable for live server
            $output = exec("/usr/local/ActivePython-2.7/bin/python /var/www/html/shiksha/application/config/entityAutoModeration.py ".escapeshellarg(json_encode($sentence))." ".escapeshellarg(json_encode($data)));
        }   
        $output = stripcslashes($output);
        if(empty($output)){
            error_log('ERROR :: runCleaningProcess() :: python return blank data for = '.$sentence);
            $output = $sentence;
        }
        //$output = preg_replace("/(\s{0,})+\\\\n+(\s{0,})+/", '\\n', $output);
        //$output = str_replace('\\n','<br>',$output);
        //add extra rules here 
        $rules   = array('/\b(\s{0,}\.\s{0,}\.)+/i');
        $replace = array('. ');
        $output  = preg_replace($rules, $replace, $output);

        $replaceURLKey = str_ireplace($this->tmpURLArr['replaceKey'], $this->tmpURLArr['url'], $output);
        $output        = empty($replaceURLKey) ? $output : $replaceURLKey;
        unset($this->tmpURLArr); $this->urlCount = 1;
        return $output;
    }

    function spellCheckString($fullString){
        global $pspell_link;
        $pspell_link = pspell_new("en", "american", "", "",(PSPELL_FAST));
        $this->anaModel = $this->CI->load->model('messageBoard/AnAModel');
        global $getAllWords;
        $getAllWords = $this->anaModel->getShikshaInternalWords();
        $whiteListArr = array('wifi', 'WiFi', 'Wi-Fi','Wi-fi', 'wi-fi');
        $fullStringArr = explode(' ', $fullString);
        foreach ($fullStringArr as $key => $value) {
            if(filter_var($value, FILTER_VALIDATE_URL) || in_array($value, $whiteListArr)){
                $finalArr[] = $value;
            }else{
            //$finalArr[] = preg_replace_callback('/\b\w+\b/',array($this, 'spellCheckWord'), $value);
              $finalArr[] = preg_replace_callback('/\b(\w)+(|\')+([a-zA-Z])\b/',array($this, 'spellCheckWord'), $value); // changes for doesn't words
            }    
        }

        $finalText = implode(' ', $finalArr);
        
        //Rule 6- remove question with dot => question and this is the temporary code here
        $rules   = array('/(\?\s{0,}\.)/', '/(\!\s{0,}\.)/');
        $replace = array('?','!');
        $finalText    = preg_replace($rules, $replace, $finalText);

        return $finalText;
}

    function spellCheckWord($word) {
        global $pspell_link;
        global $getAllWords;
        $autocorrect = TRUE;
        $word = $word[0];
        $checkWord = pspell_check($pspell_link, $word);
        if (preg_match('/^[A-Z]*$/',$word)){ 
            return $word;
        }

        if(is_numeric($word)){
            return $word;
        }

        $numberOfNumbersFound = preg_match("/[0-9]+/", $word);
        if($numberOfNumbersFound > 0){
            return $word;
        }

        // Return dictionary words
        if ($checkWord){
            return $word;
        }else{
            foreach ($getAllWords as $key => $val) {
                similar_text(strtoupper($word), strtoupper($val), $percent);
                $internalSug[$val] = $percent;
            }
            arsort($internalSug);
            reset($internalSug);
            $first_key = key($internalSug);
            $maxValue = $internalSug[$first_key];
            if($maxValue > 88){
                if(ucwords($word) == $word){
                    $first_key = ucwords($first_key);
                }
                return $first_key;
            }else{
                return $word;
            }

        }     
    }    
}
?>
