<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class my_Test_QDP extends Toast {

    private $urlPrefix = "http://172.16.3.146/v1/";
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

    
    //Test question detail page api without questionId
    public function test_getQuestionDetailWithAnswers_withOutQuestionId() {
	
        $url = "AnA/getQuestionDetailWithAnswers";
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => 'true',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'questionId', 'errorMessage' => 'Please enter your questionId.'))
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test question detail page api with incorrect questionId
    public function test_getQuestionDetailWithAnswers_incorrectQuestionId() {

	$questionId = 434242324;
	$start = 0;
	$count = 10;
        $url = "AnA/getQuestionDetailWithAnswers/".$questionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => 'true',
                                'responseCode' => '1',
                                'responseMessage' => 'Question doesn\'t exist.',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => ''
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test question detail page api with alpha-numeric questionId
    public function test_getQuestionDetailWithAnswers_alphaNumericQuestionId() {

	$questionId = 'fefefewfe';
        $url = "AnA/getQuestionDetailWithAnswers/".$questionId;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => 'true',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'questionId', 'errorMessage' => 'Please fill the questionId with correct numeric value'))
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test question detail page api with correct questionId,start,count
    public function test_getQuestionDetailWithAnswers_correctQuestionId() {
	
	$questionId = 1000068;
	$start = 0;
	$count = 50;

        $url = "AnA/getQuestionDetailWithAnswers/".$questionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['entityDetails']['viewCount']);
	unset($outputArray['entityDetails']['creationDate']);
	unset($outputArray['entityDetails']['levelName']);
	unset($outputArray['entityDetails']['userLevelDesc']);
	unset($outputArray['entityDetails']['followerCount']);
	unset($outputArray['entityDetails']['overflowTabs']);
	unset($outputArray['entityDetails']['hasUserReportedAbuse']);
	unset($outputArray['entityDetails']['isEntityOwner']);
	unset($outputArray['entityDetails']['showViewMore']);
	unset($outputArray['entityDetails']['hasUserAnswered']);
	unset($outputArray['entityDetails']['hasUserFollowed']);
	unset($outputArray['entityDetails']['tagsDetail']);

	unset($outputArray['childDetails'][0]['levelName']);
	unset($outputArray['childDetails'][0]['userLevelDesc']);
	unset($outputArray['childDetails'][0]['formattedTime']);
	unset($outputArray['childDetails'][0]['hasUserReportedAbuse']);
	unset($outputArray['childDetails'][0]['hasUserVotedUp']);
	unset($outputArray['childDetails'][0]['hasUserVotedDown']);
	unset($outputArray['childDetails'][0]['overflowTabs']);
	unset($outputArray['childDetails'][0]['shareUrl']);
        unset($outputArray['entityDetails']['shareUrl']);
        unset($outputArray['entityDetails']['picUrl']);

        //Check if the Output is same as expected output
        $expectedOutput = array('entityDetails'=>array('msgId'=>'1000068','queryType'=>'Q','title' => ' Hello I am a student who has just enrolled in Bachelors in Mass Media course(advertising) and want to know which additional course like editing or graphic design should i join to maximise my skill set so that i can land up a nice internship right after the first year instead of the ususal second year...I have a science background and am well versed in photoshop ... kindly advise at the best possible way ?','status'=>'live','childCount' => '1', 'displayName' => 'Anonymous', 'firstname' => 'shiksha', 'lastname' => '', 'userId' => '18','description' => ''),
                                'childDetails'=>array('0'=>array('msgId' => '1000069', 'queryType'=>'Q','msgTxt' => ' Graphic Design has a lot of application in the advertising world and is a good additional skill set to have. As is a basic course in Multi Media, Film Making or Photography. Editing would not be very useful. We assume that you are specialising the creative side of advertising. ','threadId'=>'1000068','parentId'=>'1000068', 'creationDate' => '2008-05-12 19:29:56', 'displayName' => 'Shiksha Counselors', 'firstname' => 'shiksha', 'lastname' => '', 'userId' =>'15','picUrl'=>'http://images.shiksha.com/mediadata/images/1227084370phpDzb0rO.gif','digUp' => '0', 'digDown' => '0','childCount'=>'0')),
                                'forceUpgrade' => 1,
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => ''
                            ); 

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test question detail page api with correct questionId and without start and count
    public function test_getQuestionDetailWithAnswers_withOutStartCount() {
	
	$questionId = 1000068;

        $url = "AnA/getQuestionDetailWithAnswers/".$questionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);
	
	unset($outputArray['entityDetails']['viewCount']);
	unset($outputArray['entityDetails']['creationDate']);
	unset($outputArray['entityDetails']['levelName']);
	unset($outputArray['entityDetails']['userLevelDesc']);
	unset($outputArray['entityDetails']['followerCount']);
	unset($outputArray['entityDetails']['overflowTabs']);
	unset($outputArray['entityDetails']['hasUserReportedAbuse']);
	unset($outputArray['entityDetails']['isEntityOwner']);
	unset($outputArray['entityDetails']['showViewMore']);
	unset($outputArray['entityDetails']['hasUserAnswered']);
	unset($outputArray['entityDetails']['hasUserFollowed']);
	unset($outputArray['entityDetails']['tagsDetail']);

	unset($outputArray['childDetails'][0]['levelName']);
	unset($outputArray['childDetails'][0]['userLevelDesc']);
	unset($outputArray['childDetails'][0]['formattedTime']);
	unset($outputArray['childDetails'][0]['hasUserReportedAbuse']);
	unset($outputArray['childDetails'][0]['hasUserVotedUp']);
	unset($outputArray['childDetails'][0]['hasUserVotedDown']);
	unset($outputArray['childDetails'][0]['overflowTabs']);
	unset($outputArray['childDetails'][0]['shareUrl']);
        unset($outputArray['entityDetails']['shareUrl']);
        unset($outputArray['entityDetails']['picUrl']);

        //Check if the Output is same as expected output
        $expectedOutput = array('entityDetails'=>array('msgId'=>'1000068','queryType'=>'Q','title' => ' Hello I am a student who has just enrolled in Bachelors in Mass Media course(advertising) and want to know which additional course like editing or graphic design should i join to maximise my skill set so that i can land up a nice internship right after the first year instead of the ususal second year...I have a science background and am well versed in photoshop ... kindly advise at the best possible way ?','status'=>'live','childCount' => '1', 'displayName' => 'Anonymous', 'firstname' => 'shiksha', 'lastname' => '', 'userId' => '18','description' => ''),
                                'childDetails'=>array('0'=>array('msgId' => '1000069', 'queryType'=>'Q','msgTxt' => ' Graphic Design has a lot of application in the advertising world and is a good additional skill set to have. As is a basic course in Multi Media, Film Making or Photography. Editing would not be very useful. We assume that you are specialising the creative side of advertising. ','threadId'=>'1000068','parentId'=>'1000068', 'creationDate' => '2008-05-12 19:29:56', 'displayName' => 'Shiksha Counselors', 'firstname' => 'shiksha', 'lastname' => '', 'userId' =>'15','picUrl'=>'http://images.shiksha.com/mediadata/images/1227084370phpDzb0rO.gif','digUp' => '0', 'digDown' => '0','childCount'=>'0')),
                                'forceUpgrade' => 1,
                                'responseCode' => '0',
                                'responseMessage' => 'Success',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => ''
                            ); 

        $this->_assert_equals($outputArray,$expectedOutput);
    }
    
    //Test question detail page api with alpha-numeric start value
    public function test_getQuestionDetailWithAnswers_alphaNumericStartValue() {
	
	$questionId = 3228518;
	$start = 'efeff3213';
	$count = 50;

        $url = "AnA/getQuestionDetailWithAnswers/".$questionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => 'true',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'start', 'errorMessage' => 'Please fill the start with correct numeric value'))
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }   
    
    //Test question detail page api with alpha-numeric count value
    public function test_getQuestionDetailWithAnswers_alphaNumericCountValue() {
	
	$questionId = 3228518;
	$start = '0';
	$count = 'grgrgr2323';

        $url = "AnA/getQuestionDetailWithAnswers/".$questionId.'/'.$start.'/'.$count;
	
        $post = "";
	
	$header = array('AuthChecksum'=>'95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0');
        $output = $this->makeCurlCall($url, $post,$header);

        //Now, parse the output
        $outputArray = json_decode($output,true);

        //Check if the Output is same as expected output
        $expectedOutput = array(
                                'forceUpgrade' => 'true',
                                'responseCode' => '1',
                                'responseMessage' => 'Unsuccessful',
                                'authChecksum' => '95201fbf065f16d7553afa2a520cb13214ce7012284eee8d176057c16c8fb50ea679f5c74eea69b94dd92274a90287f157aa8f95b6b570350af0172025f11dd801eaa71c43535fbf73a46ea0f606927a2a459291793a6178f42187ce640c58d47234b04aafbc9d417e524540420591a947fa4a5d54528dcffd34fac265ab074fd31afa0e0486397812e458c9a40027847c3fb0d616829c6ea00125c4b790b5a0',
                                'error' => array(array('field' => 'count', 'errorMessage' => 'Please fill the count with correct numeric value'))
                            );

        $this->_assert_equals($outputArray,$expectedOutput);
    }    
    
}

