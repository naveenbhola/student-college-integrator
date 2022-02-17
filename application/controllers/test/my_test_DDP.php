<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class my_Test_DDP extends Toast {

    private $urlPrefix = "http://shikshatest03.infoedge.com/v1/";
    private $headerValues = array('SOURCE: AndroidShiksha','authKey:55ba14620e26e');
    
    function __construct() {
        parent::Toast(__FILE__); // Remember this
    }

    //Common function to make CURL call to our API
    private function makeCurlCall($url, $post,$header=array()){
	$allHeaders = $this->headerValues;
	if(!empty($header))
	{
		foreach($header as $key=>$value)
		{
			$allHeaders[] = $key.': '.$value;
		}
	}
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urlPrefix.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$allHeaders);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //Test comment api for pagination of question detail page without entityId
    public function test_getCommentDetails_withOutEntityId(){
	
        $url = "AnA/getCommentDetails";
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'entityId', 'errorMessage' => 'Please enter your entityId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test comment api for pagination of question/discussion detail page with incorrect entityId
    public function test_getCommentDetails_incorrectEntityId() {

	$entityId = 434242324;
	$start = 0;
	$count = 10;
        $url = "AnA/getCommentDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Entity doesn\'t exist.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test comment api for pagination of question/discussion detail page with alpha-numeric entityId
    public function test_getCommentDetails_alphaNumericQuestionId() {

	$entityId = 'fefefewfe';
        $url = "AnA/getCommentDetails/".$entityId;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'entityId', 'errorMessage' => 'Please fill the entityId with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test comment api for pagination of question/discussion detail page with correct entityId,start,count
    public function test_getCommentDetails_correctAnswerId() {
	
	$entityId = 3228519;
	$start = 0;
	$count = 50;

        $url = "AnA/getCommentDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
	
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['childDetails']);
	
error_log("4nov:::::".print_r($outputArray,true));
        //Check if the Output is same as expected output
        $expectedOutput = array(
				'showViewMore'=>'',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test comment api for pagination of question/discussion detail page with correct entityId and without start and count
    public function test_getCommentDetails_withOutStartCount() {
	
	$entityId = 3228519;

        $url = "AnA/getCommentDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['childDetails']);
error_log("4nov:::::".print_r($outputArray,true));
        //Check if the Output is same as expected output
	$expectedOutput = array(
				'showViewMore'=>'',
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test comment api for pagination of question/discussion detail page with alpha-numeric start value
    public function test_getCommentDetails_alphaNumericStartValue() {
	
	$entityId = 3228519;
	$start = 'efeff3213';
	$count = 50;

        $url = "AnA/getCommentDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'start', 'errorMessage' => 'Please fill the start with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }   
    
    //Test comment api for pagination of question/discussion detail page with alpha-numeric count value
    public function test_getCommentDetails_alphaNumericCountValue() {
	
	$entityId = 3228519;
	$start = '0';
	$count = 'grgrgr2323';

        $url = "AnA/getCommentDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'count', 'errorMessage' => 'Please fill the count with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    
    //Test discussion detail page api without discussionId
    public function test_getDiscussionDetailWithComments_withOutDiscussionId(){
	
        $url = "AnA/getDiscussionDetailWithComments";
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'discussionId', 'errorMessage' => 'Please enter your discussionId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test discussion detail page api with incorrect discussionId
    public function test_getDiscussionDetailWithComments_incorrectEntityId() {

	$discussionId = 434242324;
	$start = 0;
	$count = 10;
        $url = "AnA/getDiscussionDetailWithComments/".$discussionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Discussion doesn\'t exist.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test discussion detail page api with alpha-numeric discussionId
    public function test_getDiscussionDetailWithComments_alphaNumericDiscussionId() {

	$discussionId = 'fefefewfe';
        $url = "AnA/getDiscussionDetailWithComments/".$discussionId;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'discussionId', 'errorMessage' => 'Please fill the discussionId with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test discussion detail page api with correct discussionId,start,count
    public function test_getDiscussionDetailWithComments_correctDiscussionId() {
	
	$discussionId = 1491375;
	$start = 0;
	$count = 50;

        $url = "AnA/getDiscussionDetailWithComments/".$discussionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['entityDetails']['viewCount']);
	unset($outputArray['entityDetails']['levelName']);
	unset($outputArray['entityDetails']['userLevelDesc']);
	unset($outputArray['entityDetails']['followerCount']);
	unset($outputArray['entityDetails']['shareUrl']);
	unset($outputArray['entityDetails']['tagsDetail']);
	unset($outputArray['childDetails'][0]['levelName']);
	unset($outputArray['childDetails'][0]['formattedTime']);
	unset($outputArray['childDetails'][0]['shareUrl']);
	unset($outputArray['childDetails'][1]['levelName']);
	unset($outputArray['childDetails'][1]['formattedTime']);
	unset($outputArray['childDetails'][1]['shareUrl']);

        //Check if the Output is same as expected output
        $expectedOutput = array('entityDetails'=>array('msgId' => '1491376','threadId'=>'1491375','queryType'=>'D','title' => ' Why Only MBA is the ultimate uption? If Not MBA then What Else?','status'=>'live','creationDate' => '4 years ago','discussionStatus' => 'live','displayName' => 'Vikas Naidu', 'firstname' => 'Vikas Naidu', 'lastname' => '', 'userId' =>'120484','description'=>'Please Discuss on this matter. People blindly following this line without knowing the importance of MBA.','picUrl'=>'http://images.shiksha.com/mediadata/images/1340984346phpEr0dbP.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'7','isEntityOwner'=>'false','showViewMore'=>'false','hasUserFollowed'=>'false','isLinked'=>'0','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse'),'1'=>array('id'=>'117','label'=>'Comment Later'))),
				'childDetails'=>array('0'=>array('msgId' => '1494328','queryType'=>'D','msgTxt' => ' I would agree with Nithya regarding the experience part. Students tend to believe that this qualification of MBA would be providing them with a well paid job and a reputation in the corporate sector and that is basically why they all go for it even without knowing what will be the kind of commitment that is required from them. For the institutions too it is just a business aspect enrolling anyone and everyone to the program without thinking about the finished products that they would be giving to the market, whether they would be capable or not. This trend should end and may be the institutions should try to make the students understand that experience of the real life situations is something which is required before they come in to study an MBA','threadId'=>'1491375','parentId'=>'1491376','creationDate'=>'2010-12-11 15:24:43','digUp'=>'0','digDown'=>'0','threadStatus'=>'live','displayName'=>'Sajid Nalakath','firstname'=>'Sajid Nalakath','lastname'=>'','userId'=>'837875','picUrl'=>'http://images.shiksha.com/mediadata/images/1343387851php6v0Nd7.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'0','hasUserVotedUp'=>'false','hasUserVotedDown'=>'false','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse'))),
						      
						      '1'=>array('msgId' => '1493744','queryType'=>'D','msgTxt' => ' Good discussion topic. It is true that lot of people do MBA because it is a popular choice and not because they understand and want to do it. It would also be true that MBA continues to be a dominant choice in the corporate world. I have personally seen lot of technocrat who opt for MBA because they have had enough technical input for 4 years. MBA no doubt is worth the value and importance attached to the qualification. It would be better if kids can do some more ground work understand the career outcome, their interests, aspirations and capabilities before doing it.','threadId'=>'1491375','parentId'=>'1491376','creationDate'=>'2010-12-10 22:36:49','digUp'=>'0','digDown'=>'0','threadStatus'=>'live','displayName'=>'Nithya Kishore','firstname'=>'Nithya','lastname'=>'Kishore','userId'=>'915489','picUrl'=>'http://images.shiksha.com/mediadata/images/1364875539phpDpYRWX.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'0','hasUserVotedUp'=>'false','hasUserVotedDown'=>'false','isLinked'=>'0','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse')))),
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test discussion detail page api with correct discussionId and without start and count
    public function test_getDiscussionDetailWithComments_withOutStartCount() {
	
	$discussionId = 1491375;

        $url = "AnA/getDiscussionDetailWithComments/".$discussionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        unset($outputArray['entityDetails']['viewCount']);
	unset($outputArray['entityDetails']['levelName']);
	unset($outputArray['entityDetails']['userLevelDesc']);
	unset($outputArray['entityDetails']['followerCount']);
	unset($outputArray['entityDetails']['tagsDetail']);
	unset($outputArray['childDetails']['levelName']);
	unset($outputArray['childDetails']['formattedTime']);
	
	

        //Check if the Output is same as expected output
        $expectedOutput = array('entityDetails'=>array('msgId' => '1491376','threadId'=>'1491375','queryType'=>'D','title' => 'Why Only MBA is the ultimate uption? If Not MBA then What Else?','status'=>'live','creationDate' => '4 years ago','discussionStatus' => 'live','displayName' => 'Vikas Naidu', 'firstname' => 'Vikas Naidu', 'lastname' => '', 'userId' =>'120484','description'=>'Please Discuss on this matter. People blindly following this line without knowing the importance of MBA.','picUrl'=>'http://images.shiksha.com/mediadata/images/1340984346phpEr0dbP.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'7','isEntityOwner'=>'false','showViewMore'=>'false','hasUserFollowed'=>'false','isLinked'=>'0','shareUrl'=>'http://ask.shikshatest03.infoedge.com/getTopicDetail/1491375/Why-Only-Mba-Is-The-Ultimate-Uption-If-Not-Mba-Then-What-Else','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse'),'1'=>array('id'=>'117','label'=>'Comment Later'))),
				'childDetails'=>array('0'=>array('msgId' => '1494328','queryType'=>'D','msgTxt' => 'I would agree with Nithya regarding the experience part. Students tend to believe that this qualification of MBA would be providing them with a well paid job and a reputation in the corporate sector and that is basically why they all go for it even without knowing what will be the kind of commitment that is required from them. For the institutions too it is just a business aspect enrolling anyone and everyone to the program without thinking about the finished products that they would be giving to the market, whether they would be capable or not. This trend should end and may be the institutions should try to make the students understand that experience of the real life situations is something which is required before they come in to study an MBA','threadId'=>'1491375','parentId'=>'1491376','creationDate'=>'2010-12-11 15:24:43','digUp'=>'0','digDown'=>'0','threadStatus'=>'live','displayName'=>'Sajid Nalakath','firstname'=>'Sajid Nalakath','lastname'=>'','userId'=>'837875','picUrl'=>'http://images.shiksha.com/mediadata/images/1343387851php6v0Nd7.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'0','hasUserVotedUp'=>'false','hasUserVotedDown'=>'false','shareUrl'=>'http://ask.shikshatest03.infoedge.com/getTopicDetail/1491375/Why-Only-Mba-Is-The-Ultimate-Uption-If-Not-Mba-Then-What-Else?referenceEntityId=1494328','isLinked'=>'0','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse'))),
						      
						      '1'=>array('msgId' => '1493744','queryType'=>'D','msgTxt' => 'Good discussion topic. It is true that lot of people do MBA because it is a popular choice and not because they understand and want to do it. It would also be true that MBA continues to be a dominant choice in the corporate world. I have personally seen lot of technocrat who opt for MBA because they have had enough technical input for 4 years. MBA no doubt is worth the value and importance attached to the qualification. It would be better if kids can do some more ground work understand the career outcome, their interests, aspirations and capabilities before doing it.','threadId'=>'1491375','parentId'=>'1491376','creationDate'=>'2010-12-10 22:36:49','digUp'=>'0','digDown'=>'0','threadStatus'=>'live','displayName'=>'Nithya Kishore','firstname'=>'Nithya','lastname'=>'Kishore','userId'=>'915489','picUrl'=>'http://images.shiksha.com/mediadata/images/1364875539phpDpYRWX.jpeg','hasUserReportedAbuse'=>'false','childCount'=>'0','hasUserVotedUp'=>'false','hasUserVotedDown'=>'false','shareUrl'=>'http://ask.shikshatest03.infoedge.com/getTopicDetail/1491375/Why-Only-Mba-Is-The-Ultimate-Uption-If-Not-Mba-Then-What-Else?referenceEntityId=1493744','isLinked'=>'0','overflowTabs'=>array('0'=>array('id'=>'116','label'=>'Report Abuse')))),
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );


        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test discussion detail page api with alpha-numeric start value
    public function test_getDiscussionDetailWithComments_alphaNumericStartValue() {
	
	$discussionId = 3228941;
	$start = 'efeff3213';
	$count = 50;

        $url = "AnA/getDiscussionDetailWithComments/".$discussionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'start', 'errorMessage' => 'Please fill the start with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }   
    
    //Test discussion detail page api with alpha-numeric count value
    public function test_getDiscussionDetailWithComments_alphaNumericCountValue() {
	
	$discussionId = 3228941;
	$start = '0';
	$count = 'grgrgr2323';

        $url = "AnA/getDiscussionDetailWithComments/".$discussionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'count', 'errorMessage' => 'Please fill the count with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    
    //Test reply api for pagination of discussion detail page without entityId
    public function test_getReplyDetails_withOutEntityId(){
	
        $url = "AnA/getReplyDetails";
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'commentId', 'errorMessage' => 'Please enter your commentId.')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test reply api for pagination of discussion detail page with incorrect entityId
    public function test_getReplyDetails_incorrectEntityId() {

	$entityId = 434242324;
	$start = 0;
	$count = 10;
        $url = "AnA/getReplyDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Entity doesn\'t exist.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test reply api for pagination of discussion detail page with alpha-numeric entityId
    public function test_getReplyDetails_alphaNumericCommentId() {

	$entityId = 'fefefewfe';
        $url = "AnA/getReplyDetails/".$entityId;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'commentId', 'errorMessage' => 'Please fill the commentId with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test reply api for pagination of question/discussion detail page with correct entityId,start,count
    public function test_getReplyDetails_correctCommentId() {
	
	$entityId = 1491640;
	$start = 0;
	$count = 50;

        $url = "AnA/getReplyDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['childDetails']['levelName']);
	unset($outputArray['childDetails']['userLevelDesc']);
	unset($outputArray['childDetails']['formattedTime']);
	unset($outputArray['childDetails']['picUrl']);

        //Check if the Output is same as expected output
        $expectedOutput = array('childDetails'=>array('0'=>array('msgId' => '1491939', 'queryType'=>'D','msgTxt' => 'true but can I wear Jeans and Shirt to the interview? Please telme the dress code?','threadId'=>'1491407','parentId'=>'1491640', 'creationDate' => '2010-12-09 15:25:02','digUp' => '0', 'digDown' => '0','displayName' => 'Priprat', 'firstname' => 'Priprat', 'lastname' => '', 'userId' =>'917398','overflowTabs'=>array('0'=>array('id'=>'120','label'=>'Report Abuse')))),
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test reply api for pagination of discussion detail page with correct entityId and without start and count
    public function test_getReplyDetails_withOutStartCount() {
	
	$entityId = 1491640;

        $url = "AnA/getReplyDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['childDetails']['levelName']);
	unset($outputArray['childDetails']['userLevelDesc']);
	unset($outputArray['childDetails']['formattedTime']);

        //Check if the Output is same as expected output
        $expectedOutput = array('childDetails'=>array('0'=>array('msgId' => '1491939', 'queryType'=>'D','msgTxt' => 'true but can I wear Jeans and Shirt to the interview? Please telme the dress code?','threadId'=>'1491407','parentId'=>'1491640', 'creationDate' => '2010-12-09 15:25:02','digUp' => '0', 'digDown' => '0','displayName' => 'Priprat', 'firstname' => 'Priprat', 'lastname' => '', 'userId' =>'917398','picUrl'=>'http://shikshatest03.infoedge.com/public/images/photoNotAvailable.gif','overflowTabs'=>array('0'=>array('id'=>'120','label'=>'Report Abuse')))),
                                'forceUpgrade' => '',
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => '',
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test reply api for pagination of discussion detail page with alpha-numeric start value
    public function test_getReplyDetails_alphaNumericStartValue() {
	
	$entityId = 1491640;
	$start = 'efeff3213';
	$count = 50;

        $url = "AnA/getReplyDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'start', 'errorMessage' => 'Please fill the start with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }   
    
    //Test reply api for pagination of question/discussion detail page with alpha-numeric count value
    public function test_getReplyDetails_alphaNumericCountValue() {
	
	$entityId = 3231299;
	$start = '0';
	$count = 'grgrgr2323';

        $url = "AnA/getReplyDetails/".$entityId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => '',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'count', 'errorMessage' => 'Please fill the count with correct numeric value')),
                                'notificationCount' => '0'
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    
}

